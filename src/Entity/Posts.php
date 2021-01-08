<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contents;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modificated_at;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_public;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage = "Proszę załaduj plik w formacje JPEG"
     * )
     */
    private $photo_path;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(?string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModificatedAt(): ?\DateTimeInterface
    {
        return $this->modificated_at;
    }

    public function setModificatedAt(?\DateTimeInterface $modificated_at): self
    {
        $this->modificated_at = $modificated_at;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setIsPublic(?bool $is_public): self
    {
        $this->is_public = $is_public;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photo_path;
    }

    public function setPhotoPath(?string $photo_path): self
    {
        $this->photo_path = $photo_path;

        return $this;
    }

}
