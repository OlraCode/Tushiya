<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Mime\Message;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Já existe uma conta com esse e-mail')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_CPF', fields: ['cpf'])]
#[UniqueEntity(fields: ['cpf'], message: 'Já existe uma conta com este CPF.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Campo E-mail é obrigatório')]
    #[Assert\Email(message: 'E-mail deve ser válido')]
    #[Assert\Length(min: 5, max: 60, maxMessage: 'Email deve conter no máximo 60 caracteres', minMessage: 'Email deve conter pelo menos 5 caracteres')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 60)]
    #[Assert\Length(min: 3, max: 60, minMessage: 'Nome deve conter no mínimo 3 caracteres', maxMessage: 'Nome deve conter no máximo { limit } caracteres')]
    #[Assert\NotBlank(message: 'Campo nome é obrigatório')]
    #[Assert\Regex(pattern: "/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/u", message: 'Nome deve conter apenas letras')]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $subject = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'user', orphanRemoval: true, cascade: ["REMOVE"])]
    private Collection $cartItems;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'teacher', orphanRemoval: true)]
    private Collection $courses;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\ManyToMany(targetEntity: Course::class)]
    private Collection $purchasedCourses;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(min: 15, max: 180)]
    private ?string $description = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\Length(min: 11, max: 11)]
    private ?string $cpf = null;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->purchasedCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setUser($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getUser() === $this) {
                $cartItem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTeacher() === $this) {
                $course->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getPurchasedCourses(): Collection
    {
        return $this->purchasedCourses;
    }

    public function addPurchasedCourses(array $purchasedCourses): static
    {
        foreach ($purchasedCourses as $course) {
            if (!$this->purchasedCourses->contains($course)) {
                $this->purchasedCourses->add($course);
            }
        }

        return $this;
    }

    public function removePurchasedCourse(Course $purchasedCourse): static
    {
        $this->purchasedCourses->removeElement($purchasedCourse);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): static
    {
        $this->cpf = preg_replace('/\D/', '', $cpf);

        return $this;
    }
}
