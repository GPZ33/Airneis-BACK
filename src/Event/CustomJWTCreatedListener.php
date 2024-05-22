<?php

namespace App\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomJWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getData();

        // Récupérer l'utilisateur à partir de l'événement
        $user = $event->getUser();

        // Ajouter l'ID de l'utilisateur au payload
        if ($user instanceof UserInterface) {
            $payload['id'] = $user->getId();
        }

        $event->setData($payload);
    }
}