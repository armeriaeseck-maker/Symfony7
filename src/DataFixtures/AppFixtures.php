<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        //ADMIN
        $admin = new User();
        $admin ->setNom('Admin')
             ->setPrenom('Site');
        $admin->setEmail('adminthies@hotmail.sn');
        $password = $this->encoder->hashPassword($admin, 'admin123');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        //EMPLOYEE
        $employee = new User();
        $employee ->setNom('Employee')
             ->setPrenom('Oumy');
        $employee->setEmail('oumythies@hotmail.sn');
        $password = $this->encoder->hashPassword($employee, 'employee123');
        $employee->setPassword($password);
        $employee->setRoles(['ROLE_EMPLOYEE']);
        $manager->persist($employee);
       
        //USER
        $user = new User();
        $user->setNom('Seck')
             ->setPrenom('Ibrahima');
        $user->setEmail('ibrahima.11@hotmail.es');
        $password = $this->encoder->hashPassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}