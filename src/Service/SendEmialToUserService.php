<?php

namespace App\Service;

use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Entity\User;

class SendEmialToUserService
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public function sendMailToUser($user,$em)
    {
        if (!is_object($user)) {            
            $user = $em->getRepository(User::class)->findOneBY(['id' => $user]);
        }
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('piotrzakrzewski@piotrzakrzewski89.pl', 'Piotr Zakrzewski'))
                ->to($user->getEmail())
                ->subject('Weryfikacja Emaila oraz aktywacja konta Blog5')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}
