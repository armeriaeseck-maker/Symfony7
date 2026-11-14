<?php

namespace App\EventListener;

use App\Event\UserDeleteSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEventListener(event: 'userDelete.success', method: 'sendDeleteUserEmail')]
class UserDeleteListener
{
       public function __construct(
              private MailerInterface $mailer
       ) {}

       public function sendDeleteUserEmail(UserDeleteSuccessEvent $event): void
       {
              $email = (new Email())
                     ->from('armeriaeseck@gmail.com')
                     ->to($event->getEmail())
                     ->subject('Suppression de votre compte')
                     ->html('
                <h2>Bonjour</h2>
                <p>Votre compte a été supprimé avec succès.</p>
            ');

              $this->mailer->send($email);
       }
}
