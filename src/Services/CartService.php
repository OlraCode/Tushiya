<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Course;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartService
{
    public function __construct(
        private CartItemRepository $repository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ){
    }

    public function getCourses(): array
    {
        $user = $this->security->getUser();
        $courseList = $this->repository->findBy(['user' => $user]);
        return $courseList;
    }

    public function addCourse(Course $course): void
    
    {
        /** @var User */
        $user = $this->security->getUser();

        if ($this->hasCourse($course, $user)) {
            throw new \DomainException("Esse curso já foi adicionado ao carrinho");
            
        }
        if ($user->getCourses()->contains($course)) {
            throw new \DomainException("Você não pode adicionar seu própio curso ao carrinho");
        }
        $cartItem = new CartItem;
        $cartItem->setCourse($course);
        $cartItem->setUser($user);

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();
    }

    public function removeCourse(Course $course): void
    {
        $user = $this->security->getUser();

        /** @var CartItem */
        $item = $this->repository->findOneBy(['course' => $course, 'user' => $user]);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }

    public function hasCourse(Course $course): bool
    {
        $user = $this->security->getUser();

        $items = $this->repository->findBy(['user' => $user]);
        $courseList = array_map(fn ($item) => $item->getCourse(), $items);

        return in_array($course, $courseList);
    }

    public function totalPrice(): string
    {
        $user = $this->security->getUser();
        
        $courseList = $this->getCourses($user);
        $price = array_reduce($courseList, fn ($value, CartItem $item) => bcadd($value, $item->getCourse()->getPrice(), 2), 0);

        return $price;
    }
}
