<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ORM\UniqueConstraint(
    name: "uniq_cart_product",
    columns: ["cart_id", "course_id"]
)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Course $Course = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->Course;
    }

    public function setCourse(?Course $Course): static
    {
        $this->Course = $Course;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
