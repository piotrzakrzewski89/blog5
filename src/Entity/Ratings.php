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
    private $Positive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $negative;

    /**
     * @ORM\ManyToOne(targetEntity=Posts::class, inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPositive(): ?int
    {
        return $this->Positive;
    }

    public function setPositive(?int $Positive): self
    {
        $this->Positive = $Positive;

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

}
