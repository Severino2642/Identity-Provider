<?php

namespace App\Controller;

use App\Entity\MailHistory;
use App\Entity\PasswordHistory;
use App\Entity\Utilisateur;
use App\Repository\AttemptRepository;
use App\Repository\MailHistoryRepository;
use App\Repository\PasswordHistoryRepository;
use App\Repository\TokenRepository;
use App\Repository\UtilisateurRepository;
use App\Service\AuthService;
use App\Service\DateService;
use App\Service\EmailService;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    private UtilisateurRepository $utilisateurRepository;
    private MailHistoryRepository $mailHistoryRepository;
    private PasswordHistoryRepository $passwordHistoryRepository;
    private TokenRepository $tokenRepository;
    private AttemptRepository $attemptRepository;

    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @param MailHistoryRepository $mailHistoryRepository
     * @param PasswordHistoryRepository $passwordHistoryRepository
     * @param TokenRepository $tokenRepository
     * @param AttemptRepository $attemptRepository
     */
    public function __construct(UtilisateurRepository $utilisateurRepository, MailHistoryRepository $mailHistoryRepository, PasswordHistoryRepository $passwordHistoryRepository, TokenRepository $tokenRepository, AttemptRepository $attemptRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->mailHistoryRepository = $mailHistoryRepository;
        $this->passwordHistoryRepository = $passwordHistoryRepository;
        $this->tokenRepository = $tokenRepository;
        $this->attemptRepository = $attemptRepository;
    }

    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @param MailHistoryRepository $mailHistoryRepository
     * @param PasswordHistoryRepository $passwordHistoryRepository
     * @param TokenRepository $tokenRepository
     */


    #[Route('/auth-email', name: 'auth_email', methods: ['POST'])]
    public function auth_email(Request $request,DateService $dateService,AuthService $authService): Response
    {
        $content = $request->getContent();
        $json = json_decode($content, true);

        $email = $json['email'];
        $data = array();
        $data['email']=null;
        if ($email!=null) {
            $user = $this->utilisateurRepository->findByEmail($email);
            if ($user!=null){
                $data['email'] = $user->getEmail();
            }
            else{
                $data['error']="email inexistant";
            }
        }

        return $this->json($data, 200);
    }

    #[Route('/auth-password', name: 'auth_password', methods: ['POST'])]
    public function auth_password(Request $request,DateService $dateService,AuthService $authService,EmailService $emailService): Response
    {
        $content = $request->getContent();
        $json = json_decode($content, true);

        $email = $json['email'];
        $password = $json['password'];
        $data = array();
        $data['email'] = $email;
        if ($password!=null && $email!=null) {
            $user = $this->utilisateurRepository->findByEmail($email);
            if ($user!=null){
                if ($authService->checkAttempt($this->attemptRepository,$user->getId())) {
                    if ($user->getPassword()==$authService->cryptage($password)) {
                        $pin_code = mt_rand(1000,9999);
                        $data['pin'] = $authService->cryptage($pin_code);
                        $data['idUser'] = $user->getId();
                        $data['nom'] = $user->getName();

                        // Envoy du code pin vers l'adresse email
                        $emailService->sendEmail($email,"Authentification","Votre code pin d'authentification est ".$pin_code);

                        // Renouvelement des tentatives pour proceder a l'etape d'authentification avec le code pin generer
                        $authService->removeAttempt($this->attemptRepository,$user->getId());
                    }
                    else{
                        $data['error']="mot de passe incorrect";
                    }
                }
                else {
                    $data['error'] = 'trop de tentative veuillez patienter !';
                }
            }
            else{
                $data['error']="email inexistant";
            }
        }

        return $this->json($data, 200);
    }

        #[Route('/auth-pin-code', name: 'auth_pin_code', methods: ['POST'])]
    public function auth_pin_code(Request $request,DateService $dateService,AuthService $authService): Response
    {
        $content = $request->getContent();
        $json = json_decode($content, true);

        $idUser = $json['idUser'];
        $true_pinCode = $json['pin'];
        $user_pinCode = $json['user_pin'];
        $date_expiration = $json['date_expiration'];
        $data = array();
        $data['idUser'] = $idUser;
        if ($true_pinCode!=null && $user_pinCode!=null && $idUser!=null) {

            if ($authService->checkExpirationDate($date_expiration)){
                if ($authService->checkAttempt($this->attemptRepository,$idUser)){
                    if ($true_pinCode==$authService->cryptage($user_pinCode)) {
                        $token_value = $authService->makeToken($idUser);
                        $data['token'] = $token_value;
                        $authService->removeToken($this->tokenRepository,$idUser);
                        $authService->addToken($this->tokenRepository,$idUser,$token_value,$date_expiration);
                        $authService->removeAttempt($this->attemptRepository,$idUser);
                    }
                    else{
                        $data['error'] = 'code pin incorrect';
                    }
                }
                else {
                    $data['error'] = 'trop de tentative veuillez patienter !';
                }
            }
            else{
                $data['error'] = "la date d'expiration est invalide";
            }
        }

        return $this->json($data, 200);
    }


}
