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
}
