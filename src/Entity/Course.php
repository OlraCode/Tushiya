<?php

namespace App\Entity;

use App\Entity\CartItem;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $title = null;

    #[ORM\Column(length: 60)]
    private ?string $description = null;

    #[ORM\Column(length: 15)]
    private ?string $category = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $price = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'user', orphanRemoval: true, cascade: ["REMOVE"])]
    private Collection $cartItems;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCartItems()
    {
        return $this->cartItems;
    }

    public function setCartItems($cartItems)
    {
        $this->cartItems = $cartItems;

        return $this;
    }
}
