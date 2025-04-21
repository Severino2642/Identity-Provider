<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\AttemptRepository;
use App\Repository\MailHistoryRepository;
use App\Repository\PasswordHistoryRepository;
use App\Repository\TokenRepository;
use App\Repository\UtilisateurRepository;
use App\Service\AuthService;
use App\Service\DateService;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignInController extends AbstractController
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



    #[Route('/inscription', name: 'inscription', methods: ['POST'])]
    public function inscription(Request $request,DateService $dateService,AuthService $authService): Response
    {

        $content = $request->getContent();
        $json = json_decode($content, true);

        $pin_code = $json['pin'];
        $user_pin = $json['user_pin'];
        $name = $json['name'];
        $email = $json['email'];
        $password = $json['password'];
        $current_date = $dateService->getCurrentDate()->format('Y-m-d');
        $data = array();
        $data['alerte']="inscription failed";
        if ($this->utilisateurRepository->findByEmail($email)==null) {
            if ($pin_code==$authService->cryptage($user_pin)) {
                $utilisateur = new Utilisateur();
                $utilisateur->setName($name);
                $utilisateur->setEmail($email);
                $utilisateur->setPassword($authService->cryptage($password));
                $utilisateur->setInscriptionDate(new \DateTime($current_date));
                $this->utilisateurRepository->save($utilisateur);
                $data['idUser']=$utilisateur->getId();
                $data['alerte']="inscription success";
            }
            else{
                $data['error']="wrong pin code";
            }
        }
        else{
            $data['error']="email existe deja";
        }

        return $this->json($data, 200);
    }

    #[Route('/check-email', name: 'check_email', methods: ['POST'])]
    public function check_email(Request $request,DateService $dateService,AuthService $authService,EmailService $emailService): Response
    {

        $content = $request->getContent();
        $json = json_decode($content, true);
        $email = $json['email'];
        $current_date = $dateService->getCurrentDate()->format('Y-m-d');
        $data = array();
        if ($this->utilisateurRepository->findByEmail($email)==null) {
            $pin_code = mt_rand(1000,9999);
            $data['pin'] = $authService->cryptage($pin_code);

            // Envoy du code pin vers l'adresse email
            $emailService->sendEmail($email,"Authentification","Votre code pin d'inscription est ".$pin_code);
        }
        else{
            $data['error']="email existe deja";
        }

        return $this->json($data, 200);
    }

}
