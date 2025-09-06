<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class CartController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private CartItemRepository $repository)
    {
    }

    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(): Response
    {

        $cartItems = $this->repository->findBy(['user' => $this->getUser()]);

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }

    #[Route('/cart/{id}', name: 'app_cart_new', methods: ['POST'])]
    public function new(Course $course, Request $request): Response
    {
        $cartItem = new CartItem;
        $cartItem->setCourse($course);
        $cartItem->setUser($this->getUser());

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();

        return new RedirectResponse($request->headers->get('referer'));
    }
}
