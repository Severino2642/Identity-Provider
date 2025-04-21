<?php

namespace App\Entity;

use App\Repository\PasswordHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasswordHistoryRepository::class)]
#[ORM\Table(name:'password_history')]

class PasswordHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?int $idUser = null;

    #[ORM\Column]
    public ?string $password = null;

    #[ORM\Column]
    public ?\DateTime $modification_date = null;

    /**
     * @param int|null $idUser
     * @param string|null $password
     * @param \DateTime|null $modification_date
     */
    public function __construct(?int $idUser, ?string $password, ?\DateTime $modification_date)
    {
        $this->idUser = $idUser;
        $this->password = $password;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
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
