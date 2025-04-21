<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(): Response
    {
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(EmailService $emailService): Response
    {
//        $emailService->sendEmail(
//            'divaralychristian@gmail.com',
//            'Sujet de l\'email',
//            'Ceci est un email en texte brut.',
//            '<p>Ceci est un email en <b>HTML</b>.</p>'
//        );

        return new Response('Email envoyé avec succès.',200);
    }
}
