<?php

namespace App\Service;

use App\Entity\Attempt;
use App\Entity\Token;
use App\Entity\Utilisateur;
use App\Repository\AttemptRepository;
use App\Repository\MailHistoryRepository;
use App\Repository\PasswordHistoryRepository;
use App\Repository\TokenRepository;
use App\Repository\UtilisateurRepository;
use phpDocumentor\Reflection\Types\Boolean;
use function PHPUnit\Framework\isEmpty;

class AuthService
{
    private int $max_attempt = 3;

    public function getAttemptDelay(): \DateInterval
    {
        return new \DateInterval("PT2M");
    }

    public function getTokenDelay(): \DateInterval
    {
        return new \DateInterval("PT5M");
    }

    public function checkEmail(UtilisateurRepository $utilisateurRepository, $email) : Utilisateur
    {
        $result = $utilisateurRepository->findByEmail($email);
        return $result;
    }

    public function checkPassword(PasswordHistoryRepository $passwordHistoryRepository, $idUser,$pwd) : int
    {
        $result = $passwordHistoryRepository->findByLastPasswordByIdUser($idUser,$pwd);
        if ($result!=null) {
            return $result->getIdUser();
        }
        return -1;
    }

    public function checkPinCode(PasswordHistoryRepository $passwordHistoryRepository, $idUser,$pwd) : int
    {
        $result = $passwordHistoryRepository->findByLastPasswordByIdUser($idUser,$pwd);
        if ($result!=null) {
            return $result->getIdUser();
        }
        return -1;
    }

    public function addAttempt(AttemptRepository $attemptRepository, $idUser)
    {
        $dateService = new DateService();
        $attempt = new Attempt($idUser,$this->max_attempt,new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s')));
        $attemptRepository->save($attempt);
    }

    public function checkAttempt(AttemptRepository $attemptRepository, $idUser) : bool
    {
        $result = false;
        $dateService = new DateService();
        $attempt = $attemptRepository->findByIdUser($idUser);
        if($attempt==null) {
            $this->addAttempt($attemptRepository,$idUser);
            $attempt = $attemptRepository->findByIdUser($idUser);
        }


        if ($attempt->getAttempt()>0) {
            $attempt->setAttempt($attempt->getAttempt()-1);
            $attempt->setAddDate(new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s')));
            $attemptRepository->save($attempt);
            $result = true;
        }
        else{
            $current_date = new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s'));
            $add_date = new \DateTime($attempt->getAddDate()->format('Y-m-d H:i:s'));
            $add_date->add($this->getAttemptDelay());
            if ($current_date >= $add_date) {
                $attempt->setAttempt($this->max_attempt-1);
                $attempt->setAddDate(new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s')));
                $attemptRepository->save($attempt);
                $result = true;
            }
        }
        return $result;
    }

    public function removeAttempt(AttemptRepository $attemptRepository, $idUser)
    {
        $attemptRepository->deleteByIdUser($idUser);
    }

    public function addToken(TokenRepository $tokenRepository, $idUser,$token_value,$date_expiration)
    {
        $dateService = new DateService();
        $expiration_date = new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s'));
        $expiration_date->add($this->getTokenDelay());
        if ($date_expiration!="") {
            $expiration_date = new \DateTime($date_expiration);
        }

        $token = new Token($idUser,$token_value,$expiration_date);
        $tokenRepository->save($token);
    }

    public function removeToken(TokenRepository $tokenRepository, $idUser)
    {
        $tokenRepository->deleteByIdUser($idUser);
    }

    public function checkToken(TokenRepository $tokenRepository, $idUser,$token_value) : int
    {
        $result = -1;
        $dateService = new DateService();
        $token = $tokenRepository->findByIdUser($idUser);
        if($token!=null) {
            if ($token->getToken()==$token_value) {
                $current_date = new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s'));
                if ($current_date <= $token->getExpirationDate()) {
                    $result = 0;
                }
                else{
                    $tokenRepository->remove($token);
                    $result = 2;
                }
            }
            else{
                $result = 1;
            }
        }
        return $result;
    }

    public function updateToken(TokenRepository $tokenRepository, $idUser) : bool
    {
        $result = false;
        $dateService = new DateService();
        $new_date = new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s'));
        $new_date->add($this->getTokenDelay());
        $token = $tokenRepository->findByIdUser($idUser);
        if ($token!=null) {
            $token->setExpirationDate($new_date);
            $tokenRepository->save($token);
        }

        return $result;
    }

    public function cryptage($value) : string
    {
        return hash('sha256',$value);
    }

    public function makeToken($idUser) : string
    {
        return $this->cryptage($idUser."".mt_rand(1000,9999));
    }

    public function checkExpirationDate($date_expiration) : bool
    {
        $result = false;
        if ($date_expiration!=null){
            $dateService = new DateService();
            $dt_exp = new \DateTime($date_expiration);
            $current_date = new \DateTime($dateService->getCurrentDate()->format('Y-m-d H:i:s'));
            if ($dt_exp>$current_date){
                $result = true;
            }
        }
        else{
            $result = true;
        }
        return $result;
    }
}