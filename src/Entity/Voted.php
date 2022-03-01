<?php

namespace App\Entity;

use App\Repository\VotedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VotedRepository::class)
 */
class Voted
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Movie;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $Vote;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->User;
    }

    public function setUser(string $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getMovie(): ?string
    {
        return $this->Movie;
    }

    public function setMovie(string $Movie): self
    {
        $this->Movie = $Movie;

        return $this;
    }

    public function getVote(): ?string
    {
        return $this->Vote;
    }

    public function setVote(string $Vote): self
    {
        $this->Vote = $Vote;

        return $this;
    }
}
