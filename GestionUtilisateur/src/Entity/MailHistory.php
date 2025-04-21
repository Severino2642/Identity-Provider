<?php

namespace App\Entity;

use App\Repository\MailHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailHistoryRepository::class)]
#[ORM\Table(name:'mail_history')]

class MailHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?int $idUser = null;

    #[ORM\Column]
    public ?string $mail = null;

    #[ORM\Column]
    public ?\DateTime $modification_date = null;

    /**
     * @param int|null $idUser
     * @param string|null $mail
     * @param \DateTime|null $modification_date
     */
    public function __construct(?int $idUser, ?string $mail, ?\DateTime $modification_date)
    {
        $this->idUser = $idUser;
        $this->mail = $mail;
        $this->modification_date = $modification_date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    public function getModificationDate(): ?\DateTime
    {
        return $this->modification_date;
    }

    public function setModificationDate(?\DateTime $modification_date): void
    {
        $this->modification_date = $modification_date;
    }


}
