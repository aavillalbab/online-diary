<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\SerializedName("id")
     * @JMS\Groups({"category_list"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @JMS\SerializedName("name")
     * @JMS\Groups({"category_list"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @JMS\SerializedName("slug")
     * @JMS\Groups({"category_list"})
     */
    private ?string $slug;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="category")
     */
    private Collection $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): static
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setCategory($this);
        }

        return $this;
    }

    public function removeNews(News $news): static
    {
        if ($this->news->removeElement($news) && $news->getCategory() === $this) {
            $news->setCategory(null);
        }

        return $this;
    }
}
