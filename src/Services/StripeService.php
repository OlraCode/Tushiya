<?php

namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeService
{
    public function __construct(string $stripeSecretKey, private UrlGeneratorInterface $url) {
        Stripe::setApiKey($stripeSecretKey);
    }

    public function createCheckoutSession(array $courses): Session
    {
        return Session::create([
            'line_items' => $courses,
            'mode' => 'payment',
            'success_url' => $this->url->generate('app_payment_success', referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->url->generate('app_payment_cancel', referenceType: UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}
