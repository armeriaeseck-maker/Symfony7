<?php

namespace App\EventSubscriber;

use App\Event\UserAddSuccessEvent;
use App\Event\UserDeleteSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserSubscriber implements EventSubscriberInterface
{
       public function __construct(private MailerInterface $mailer) {}

       public static function getSubscribedEvents(): array
       {
              return [
                     'userAdd.success' => 'onUserAddSuccess',
                     'userDelete.success' => 'onUserDeleteSuccess',
              ];
       }

       public function onUserAddSuccess(UserAddSuccessEvent $event): void
       {
              $email = (new Email())
                     ->from('armeriaeseck@gmail.com')
                     ->to($event->getEmail())
                     ->subject('Bienvenue !')
                     ->html('<h2>Bonjour,</h2><p>Votre compte a été créé avec succès.</p>');

              try {
                     $this->mailer->send($email);
              } catch (\Exception $e) {
                     // Gérer l'erreur si besoin
              }
       }
       public function onUserDeleteSuccess(UserDeleteSuccessEvent $event): void
       {
              $recipientEmail = $event->getEmail();

              // Vérification que l'e-mail est valide avant l'envoi
              if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                     return; // Ignore l'envoi si l'e-mail est malformé (ex: "bint")
              }

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
