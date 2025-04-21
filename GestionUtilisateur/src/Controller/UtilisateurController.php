<?php

namespace App\Controller;

use App\Entity\MailHistory;
use App\Entity\PasswordHistory;
use App\Repository\MailHistoryRepository;
use App\Repository\PasswordHistoryRepository;
use App\Repository\TokenRepository;
use App\Repository\UtilisateurRepository;
use App\Service\AuthService;
use App\Service\DateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UtilisateurController extends AbstractController
{
    private UtilisateurRepository $utilisateurRepository;
    private MailHistoryRepository $mailHistoryRepository;
    private PasswordHistoryRepository $passwordHistoryRepository;
    private TokenRepository $tokenRepository;

    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @param MailHistoryRepository $mailHistoryRepository
     * @param PasswordHistoryRepository $passwordHistoryRepository
     * @param TokenRepository $tokenRepository
     */
    public function __construct(UtilisateurRepository $utilisateurRepository, MailHistoryRepository $mailHistoryRepository, PasswordHistoryRepository $passwordHistoryRepository, TokenRepository $tokenRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->mailHistoryRepository = $mailHistoryRepository;
        $this->passwordHistoryRepository = $passwordHistoryRepository;
        $this->tokenRepository = $tokenRepository;
    }

    #[Route('/utilisateur', name: 'update_user_info', methods: ['POST'])]
    public function update_user_info(Request $request,DateService $dateService,AuthService $authService): Response
    {
        $content = $request->getContent();
        $json = json_decode($content, true);

        $name = $json['name'];
        $old_password = $json['password'];
        $new_password = $json['new_password'];
        $token_value = $json['token'];
        $data = array();

        $token = $this->tokenRepository->findByTokenValue($token_value);
        if($token != null){
            $user = $this->utilisateurRepository->findById($token->getIdUser());
            $token_result = $authService->checkToken($this->tokenRepository, $token->getIdUser(),$token_value);
            $data['token_result'] = $token_result;
            if ($token_result==2){
                $data['error']="token expirer";
            }
            if ($token_result==1){
                $data['error']="wrong token value";
            }
            if ($token_result==0) {
                $authService->updateToken($this->tokenRepository, $user->getId());

                // changer de mot de passe
                if ($old_password!=null && $new_password!=null) {
                    if ($user->getPassword()==$authService->cryptage($old_password)) {
                        $user->setPassword($authService->cryptage($new_password));
                        $this->utilisateurRepository->save($user);
                        $data['alerte'][]="password changed";
                    }
                    else{
                        $data['error']="wrong password";
                    }
                }

                // changer de nom
                if ($name!=null) {
                    $user->setName($name);
                    $this->utilisateurRepository->save($user);
                    $data['alerte'][]="name changed";
                }
            }
        }
        else{
            $data['error']="token invalidate";
        }

        return $this->json($data, 200);
    }
}
