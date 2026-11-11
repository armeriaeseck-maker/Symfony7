<?php

namespace App\Controller;

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
        return $this->render('home/contact.html.twig',[
        'controller_name'=> 'Controller de page contact',
        'coordonnees1' => [
            "Nom" => "Seck",
            "Penom" => "Ibrahima",
            // "Email" => "ibrahima@email.com",
            "Téléphone" => "06 00 00 00 00",
            "Ville" => "Toulouse",
        ],
        'coordonnees2' => [
            "Nom" => "Khali",
            "Penom" => "Ibou",
            // "Email" => "ibrahima@email.com",
            "Téléphone" => "06 10 52 63 00",
            "Ville" => "Paris",
        ]

        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank...');
    }
}