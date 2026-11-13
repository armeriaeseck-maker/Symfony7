<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Client' => 'ROLE_USER',
                    'Employe' => 'ROLE_EMPLOYEE',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'help' => 'Sélectionnez un rôle',
                'multiple' => false,
                'expanded' => false,
                'mapped' => false, // Géré manuellement dans le controller
            ])
            ->add('nom')
            ->add('prenom')
        ;

        // Écoute d'événement pour adapter dynamiquement les champs de mot de passe
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $user = $event->getData();
            $form = $event->getForm();

            // Vérifier si on est en mode création (pas d'ID)
            if (!$user || null === $user->getId()) {
                // EN CRÉATION : Un seul champ mot de passe visible
                $form->add('password', PasswordType::class, [
                    'label' => 'Password'
                ]);
            } else {
                // EN ÉDITION : Un champ caché pour le mot de passe existant et un champ pour un éventuel nouveau
                $form->add('password', HiddenType::class);
                $form->add('password2', PasswordType::class, [
                    'mapped' => false,
                    'required' => false,
                    //'label' => 'Nouveau mot de passe (laisser vide si inchangé)'
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
