<?php

namespace App\Entity;

use App\Repository\NewsRepository;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use dsarhoya\DSYFilesBundle\Interfaces\IFileEnabledEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News implements IFileEnabledEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\SerializedName("id")
     * @JMS\Groups({"news_list"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\SerializedName("title")
     * @JMS\Groups({"news_list"})
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     * @JMS\SerializedName("description")
     * @JMS\Groups({"news_list"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $imagePath = null;

    /**
     * @ORM\Column(type="boolean")
     * @JMS\SerializedName("isActive")
     */
    private ?bool $isActive = false;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\SerializedName("publishedAt")
     * @JMS\Groups({"news_list"})
     */
    private ?DateTimeInterface $publishedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="news")
     * @ORM\JoinColumn(nullable=false)
     * @JMS\SerializedName("category")
     * @JMS\Groups({"r_news_category"})
     */
    private ?Category $category;

    /**
     * @var UploadedFile|null
     */
    private ?UploadedFile $file = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): self
    {
        $this->file = $file;
        if ($file) {
            $ext = ('bin' !== $file->guessExtension() && null !== $file->guessExtension()) ? $file->guessExtension() : $file->getClientOriginalExtension();
            $this->imagePath = sprintf('news_%s.%s', md5(time()), $ext);
        }
        return $this;
    }

    public function getFileKey(): ?string
    {
        return $this->imagePath;
    }

    public function getFileProperties(): array
    {
        return [
            'ACL' => 'public-read'
        ];
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return 'news';
    }
}

