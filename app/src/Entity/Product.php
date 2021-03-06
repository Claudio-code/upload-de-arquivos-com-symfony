<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="products")
 */
class Product implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="o nome não pode ser nulo", payload={"severity"="error"})
     * @Assert\Type(type="string", message="O nome não pode ser uma string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="a descrição não pode ser nula")
     * @Assert\Type(type="string", message="A descrição deve ser uma string")
     */
    private string $description;

    /**
     * @ORM\Column(type="text", length=255)
     * @Assert\NotBlank(message="O conteudo não pode ser nulo")
     * @Assert\Type(type="string", message="O conteudo deve ser uma string")
     */
    private string $content;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="O preço não pode ser nulo")
     * @Assert\Type(type="int", message="O tipo do preço deve ser um inteiro	")
     */
    private int $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="ProductPhoto", mappedBy="product")
     */
    private $photos;

    public function __construct(string $directory)
    {
        $this->categories = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories(ArrayCollection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return null|ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    public function getPhotosAndPath(string $directory): array
    {
        $photosAndPath = $this->photos->map(function (ProductPhoto $photo) use ($directory) {
            return "{$directory}/{$photo->getImage()}";
        });

        return $photosAndPath->toArray();
    }

    public function setPhotos(?ArrayCollection $photos): void
    {
        $this->photos = $photos;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'content' => $this->getContent(),
            'price' => $this->getPrice(),
            'slug' => $this->getSlug(),
            'isActive' => $this->getIsActive(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'categories' => $this->getCategories()->toArray(),
        ];
    }
}