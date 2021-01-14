<?php

namespace App\Entity;

use App\Repository\RatingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingsRepository::class)
 */
class Ratings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $positive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $negative;

    /**
     * @ORM\ManyToOne(targetEntity=Posts::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPositive(): ?int
    {
        return $this->positive;
    }

    public function setPositive(?int $positive): self
    {
        $this->positive = $positive;

        return $this;
    }

    public function getNegative(): ?int
    {
        return $this->negative;
    }

    public function setNegative(?int $negative): self
    {
        $this->negative = $negative;

        return $this;
    }

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
