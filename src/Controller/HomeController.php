<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'Controller de page contact',
            'coordonnees1' => [
                "Nom" => "Seck",
                "Penom" => "Ibrahima",
                // "Email" => "ibrahima@email.com",
                "Téléphone" => "06 00 00 00 00",
                "Ville" => "Toulouse",
            ],
            'coordonnees2' => [
                "Nom" => "Khali",
                "Prenom" => "Ibou",
                // "Email" => "ibrahima@email.com",
                "Téléphone" => "06 10 52 63 00",
                "Ville" => "Paris",
            ]

        ]);
    }

    #[Route('/mail', name: 'app_mail')]
    public function mail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('armeriaeseck@gmail.com')
            ->to('armeriaeseck@gmail.com')
            ->subject('Test email Symfony')
            ->text('Ceci est un email de test.')
            ->html('<h2>Test email Symfony</h2><p>Email envoyé avec succès.</p>');

        $mailer->send($email);

        return $this->render('home/email.html.twig');
    }
    //php mailer
    #[Route('/phpmail', name: 'app_phpmail')]
    public function phpmail(): Response
    {
        // configuration du mailer
        $mail = (new PHPMailer(true));
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'armeriaeseck@gmail.com';                     //SMTP username
        $mail->Password   = 'frvdspbvfyzcxbmf';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //envoi mail
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('armeriaeseck@gmail.com', 'Joe User');
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Test avec phpmail';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';    //Add a recipient
        $mail->send();



        return $this->render('home/email.html.twig');
    }
}
