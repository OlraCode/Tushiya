<?php

namespace App\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class VerifiedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private RouterInterface $router,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        /** @var User */
        $user = $this->security->getUser();

        if (!$user) {
            return;
        }

        if (!$user->isVerified()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($currentRoute !== 'app_user_verify') {
                $url = $this->router->generate('app_user_verify');
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
