<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditPhotoUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photoProfil', FileType::class, [
            'mapped'=>false,
            'label'=>'Choisir une nouvelle photo de profil',
            "constraints" => [
                new File([
                    "maxSize" => "3M",
                    "mimeTypes" => [
                        "image/jpeg",
                        "image/png"
                    ]
                ])
            ]
        ])
            ->add('Modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
