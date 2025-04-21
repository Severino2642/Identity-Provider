<?php
namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class EmailService
{
    private string $from = "promentreprise@gmail.com";

    public function getMailer() :Mailer
    {
        $transport = Transport::fromDsn('gmail://promentreprise@gmail.com:xaeaffbdxbgdfvoa@default');
        $mailer = new Mailer($transport);
        return $mailer;
    }

    public function sendEmail(string $to, string $subject, string $textContent): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->text($textContent)
        ;
        $this->getMailer()->send($email);
    }
}
