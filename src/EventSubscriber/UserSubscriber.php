<?php

namespace App\EventSubscriber;

use App\Event\UserAddSuccessEvent;
use App\Event\UserDeleteSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserSubscriber implements EventSubscriberInterface
{
       public function __construct(
              private MailerInterface $mailer
       ) {}

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
                     ->to('armeriaeseck@gmail.com') // adresse de réception
                     ->subject('Nouvel utilisateur créé')
                     ->html('
                <h2>Nouvel utilisateur</h2>
                <p>Un nouvel utilisateur a été créé avec succès.</p>
                <p>Email : ' . $event->getEmail() . '</p>
            ');

              $this->mailer->send($email);
       }

       public function onUserDeleteSuccess(UserDeleteSuccessEvent $event): void
       {
              $email = (new Email())
                     ->from('armeriaeseck@gmail.com')
                     ->to('armeriaeseck@gmail.com')
                     ->subject('Utilisateur supprimé')
                     ->html('
                <h2>Suppression utilisateur</h2>
                <p>Le compte ' . $event->getEmail() . ' a été supprimé.</p>
            ');

              $this->mailer->send($email);
       }
}
