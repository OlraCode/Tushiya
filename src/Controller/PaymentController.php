<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use App\Services\CartService;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method \App\Entity\User getUser()
 */

final class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment', methods: ['POST'])]
    public function create(CartService $cart, StripeService $payment): Response
    {
        $courseList = $cart->getCourses();

        $courses = array_map(fn (Course $course) => [
            'quantity' => 1,
            'price_data' => [
                'currency' => 'brl',
                'product_data' => ['name' => $course->getTitle()],
                'unit_amount' => $course->getPrice() * 100
            ],
        ], $courseList);

        $session = $payment->createCheckoutSession($courses);

        return new RedirectResponse($session->url);
    }

    #[Route('/payment/success', name: 'app_payment_success', methods: ['GET'])]
    public function success(CartService $cart, EntityManagerInterface $entityManager): Response
    {
        $this->getUser()->addPurchasedCourses($cart->getCourses());
        $cart->clear();
        $entityManager->flush();

        $this->addFlash('success', 'Pagamento realizado com sucesso');

        return $this->redirectToRoute('app_my_courses');
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        $this->addFlash('danger', 'Pagamento cancelado');

        return $this->redirectToRoute('app_cart');
    }
}
