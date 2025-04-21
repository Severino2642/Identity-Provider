<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ORM\Table(name:'token')]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?int $idUser = null;

    #[ORM\Column]
    public ?string $token = null;

    #[ORM\Column]
    public ?\DateTime $expiration_date = null;

    /**
     * @param int|null $idUser
     * @param string|null $token
     * @param \DateTime|null $expiration_date
     */
    public function __construct(?int $idUser, ?string $token, ?\DateTime $expiration_date)
    {
        $this->idUser = $idUser;
        $this->token = $token;
        $this->expiration_date = $expiration_date;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getExpirationDate(): ?\DateTime
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(?\DateTime $expiration_date): void
    {
        $this->expiration_date = $expiration_date;
    }

}
