<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Course;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    public function __construct(
        private CartItemRepository $repository,
        private EntityManagerInterface $entityManager,
    ){
    }

    public function getCourses(User $user): array
    {
        $courseList = $this->repository->findBy(['user' => $user]);
        return $courseList;
    }

    public function addCourse(Course $course, User $user): void
    
    {
        if ($this->hasCourse($course, $user)) {
            throw new \DomainException("Esse curso já foi adicionado ao carrinho");
            
        }
        $cartItem = new CartItem;
        $cartItem->setCourse($course);
        $cartItem->setUser($user);

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();
    }

    public function removeCourse(Course $course, User $user): void
    {
        /** @var CartItem */
        $item = $this->repository->findOneBy(['course' => $course, 'user' => $user]);
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }

    public function hasCourse(Course $course, User $user): bool
    {
        $items = $this->repository->findBy(['user' => $user]);
        $courseList = array_map(fn ($item) => $item->getCourse(), $items);

        return in_array($course, $courseList);
    }

    public function totalPrice(User $user): string
    {
        $courseList = $this->getCourses($user);
        $price = array_reduce($courseList, fn ($value, CartItem $item) => bcadd($value, $item->getCourse()->getPrice(), 2), 0);

        return $price;
    }
}
