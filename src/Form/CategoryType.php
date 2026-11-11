<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('Description')
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Image Catégorie',
                'attr' => [
                    'placeholder' => 'Placeholder Image Catégorie'
                ],
                'constraints' => [
                    new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                    'image/jpeg',
                     'image/png',
                    ],
    ])
]
            ])
            ->add('Date_add', DateTimeType::class, [
                'data' => new \DateTime(),
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}