<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmailVerificationController extends AbstractController
{
    #[Route('/verify-email', name: 'verify_email')]
    public function verifyEmail(Request $request, UserRepository $userRepository): Response
    {
        $token = $request->query->get('token');
    
        if (!$token) {
            throw $this->createNotFoundException('No token provided');
        }
    
        $user = $userRepository->findOneBy(['verificationToken' => $token]);
    
        if (!$user) {
            throw $this->createNotFoundException('Invalid token');
        }
    
        $user->setEmailValidated(true);
        $user->setVerificationToken(null);
        $userRepository->save($user, true);
    
        return new Response('Email verified successfully!');
    }
}
