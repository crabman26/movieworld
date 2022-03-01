<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $Title;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Name_of_the_user;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_of_publication;

    /**
     * @ORM\Column(type="integer")
     */
    private $Likes;

    /**
     * @ORM\Column(type="integer")
     */
    private $Hates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getNameOfTheUser(): ?string
    {
        return $this->Name_of_the_user;
    }

    public function setNameOfTheUser(string $Name_of_the_user): self
    {
        $this->Name_of_the_user = $Name_of_the_user;

        return $this;
    }

    public function getDateOfPublication(): ?\DateTimeInterface
    {
        return $this->Date_of_publication;
    }

    public function setDateOfPublication(\DateTimeInterface $Date_of_publication): self
    {
        $this->Date_of_publication = $Date_of_publication;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->Likes;
    }

    public function setLikes(int $Likes): self
    {
        $this->Likes = $Likes;

        return $this;
    }

    public function getHates(): ?int
    {
        return $this->Hates;
    }

    public function setHates(int $Hates): self
    {
        $this->Hates = $Hates;

        return $this;
    }
}
