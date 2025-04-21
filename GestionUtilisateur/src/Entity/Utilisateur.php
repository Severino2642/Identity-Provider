<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;


#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name:'utilisateur')]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?string $name = null;
    #[ORM\Column]
    public ?string $email = null;
    #[ORM\Column]
    public ?string $password = null;
    #[ORM\Column]
    public ?\DateTime $inscription_date = null;

    public function __construct()
    {
    }






    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getInscriptionDate(): ?\DateTime
    {
        return $this->inscription_date;
    }

    public function setInscriptionDate(?\DateTime $inscription_date): void
    {
        $this->inscription_date = $inscription_date;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }


}
