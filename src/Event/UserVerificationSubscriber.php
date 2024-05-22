<?php

namespace App\Event;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class UserVerificationSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendVerificationEmail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendVerificationEmail(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $verificationLink = sprintf(
            'http://127.0.0.1:8000/verify-email?token=%s',
            $user->getVerificationToken()
        );

        $email = (new Email())
            ->from('no-reply@airneis.com')
            ->to($user->getEmail())
            ->subject('Airneis : Email Verification')
            ->text('Please verify your email by clicking on the following link: ' . $verificationLink);

        $this->mailer->send($email);
    }
}

