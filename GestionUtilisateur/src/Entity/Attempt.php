<?php

namespace App\Entity;

use App\Repository\AttemptRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttemptRepository::class)]
#[ORM\Table(name:'attempt')]

class Attempt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public ?int $idUser = null;

    #[ORM\Column]
    public ?int $attempt = null;

    #[ORM\Column]
    public ?\DateTime $add_date = null;

    /**
     * @param int|null $idUser
     * @param int|null $attempt
     * @param \DateTime|null $add_date
     */
    public function __construct(?int $idUser, ?int $attempt, ?\DateTime $add_date)
    {
        $this->idUser = $idUser;
        $this->attempt = $attempt;
        $this->add_date = $add_date;
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

    public function getAttempt(): ?int
    {
        return $this->attempt;
    }

    public function setAttempt(?int $attempt): void
    {
        $this->attempt = $attempt;
    }

    public function getAddDate(): ?\DateTime
    {
        return $this->add_date;
    }

    public function setAddDate(?\DateTime $add_date): void
    {
        $this->add_date = $add_date;
    }


}
