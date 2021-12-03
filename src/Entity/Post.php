<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=60)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private $body;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="posts", fileNameProperty="posterName")
     *
     * @var File|null
     */
    private $posterFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $posterName;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $writtenBy;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     */
    private $taggedBy;

    public function __construct()
    {
        $this->taggedBy = new ArrayCollection();
    }

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function setPosterFile(?File $posterFile = null): void
    {
        $this->posterFile = $posterFile;

        if (null !== $posterFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    public function setPosterName(?string $posterName): void
    {
        $this->posterName = $posterName;
    }

    public function getPosterName(): ?string
    {
        return $this->posterName;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getWrittenBy(): ?User
    {
        return $this->writtenBy;
    }

    public function setWrittenBy(?User $writtenBy): self
    {
        $this->writtenBy = $writtenBy;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTaggedBy(): Collection
    {
        return $this->taggedBy;
    }

    public function addTaggedBy(Tag $taggedBy): self
    {
        if (!$this->taggedBy->contains($taggedBy)) {
            $this->taggedBy[] = $taggedBy;
        }

        return $this;
    }

    public function removeTaggedBy(Tag $taggedBy): self
    {
        $this->taggedBy->removeElement($taggedBy);

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
