<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MessageGenerator;
use App\Service\PHPMailService;


final class HomeController extends AbstractController
{
    protected $pass;
    public function __construct(string $gmailpass)
    {
        $this->pass = $gmailpass;
    }

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
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'armeriaeseck@gmail.com';
        $mail->Password   = 'frvdspbvfyzcxbmf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setFrom('armeriaeseck@gmail.com', 'Ibrahima');
        $mail->addAddress('armeriaeseck@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Test PHPMailer';
        $mail->Body    = '<h2>Email envoyé depuis PHPMailer</h2>';
        $mail->send();
        return $this->render('home/email.html.twig');
    }

    //message generator service
    #[Route('/service', name: 'app_service')]
    public function service(MessageGenerator $msg): Response
    {
        return $this->render('home/service.html.twig', [
            'controller_name' => $msg->getHappyMessage(),
        ]);
    }

    //php mail service
    #[Route('/phpmail_service', name: 'app_phpmail_service')]
    public function phpmailservice(PHPMailService $mail): Response
    {
        //envoi du mail
        $mail->setFrom('armeriaeseck@gmail.com', 'Ibrahima');
        $mail->addAddress('armeriaeseck@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Test avec PHPMailService';
        $mail->Body = '<h2>Email envoyé depuis le service PHPMailer</h2>';
        $mail->send();
        return $this->render('home/email.html.twig', [
            'controller_name' => "Le service mail a réussi l'envoi"
        ]);
    }

    //tiers service
    #[Route('/tiers_service', name: 'app_tiers_service')]
    public function tiersService(): Response
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'armeriaeseck@gmail.com';
            $mail->Password = $this->pass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('armeriaeseck@gmail.com', 'Ibrahima');
            $mail->addAddress('armeriaeseck@gmail.com');
            $mail->isHTML(true);
            $mail->Subject = 'Tiers Service 2';
            $mail->Body = '<h2>Email envoyé depuis Tiers Service</h2>';
            $mail->send();
            return $this->render('home/email.html.twig', [
                'controller_name' => 'Email envoyé avec succès'
            ]);
        } catch (\Exception $e) {
            return new Response(
                'Erreur PHPMailer : ' . $mail->ErrorInfo
            );
        }
    }
}
