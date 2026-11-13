<?php

namespace App\EventListener;

use App\Event\UserDeleteSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEventListener(event: 'userDelete.success', method: 'sendDeleteUserEmail')]
class UserDeleteListener
{
       public function __construct(private MailerInterface $mailer) {}

       public function sendDeleteUserEmail(UserDeleteSuccessEvent $event): void
       {
              $recipientEmail = $event->getEmail();

              $email = (new Email())
                     ->from('armeriaeseck@gmail.com')
                     ->to($recipientEmail)
                     ->subject('Suppression de votre compte')
                     ->html('<h2>Bonjour,</h2><p>Nous vous confirmons que votre compte a été supprimé avec succès.</p>');

              try {
                     $this->mailer->send($email);
              } catch (\Exception $e) {
                     // Gérer l'erreur si besoin
              }
       }
}
