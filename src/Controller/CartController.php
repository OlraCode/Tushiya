<?php

namespace App\Controller;

use App\Entity\Course;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class CartController extends AbstractController
{

    public function __construct(
        private CartService $cart,
    ) {
    }

    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(): Response
    {
        $cartItems = $this->cart->getCourses($this->getUser());
        $totalPrice = $this->cart->totalPrice($this->getUser());

        return $this->render('cart/index.html.twig', compact('cartItems', 'totalPrice'));
    }

    #[Route('/cart/{id}', name: 'app_cart_new', methods: ['POST'])]
    public function new(Course $course, Request $request): Response
    {
        $this->cart->addCourse($course, $this->getUser());

        return new RedirectResponse($request->headers->get('referer'));
    }

    #[Route('cart/{id}/delete', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Course $course, Request $request): Response
    {
        $this->cart->removeCourse($course, $this->getUser());

        return new RedirectResponse($request->headers->get('referer'));
    }
}
