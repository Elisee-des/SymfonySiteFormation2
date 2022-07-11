<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostuleFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('telephone', NumberType::class)
            ->add('niveauEtude', TextType::class)
            ->add('cv', FileType::class, [
                'label' => 'CV',
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png"
                        ]
                    ]),
                    // "invalid_message"=>"Votre fichier ne dois pas depasser 2M et dois etre JPEG ou PNG"
                ]
            ])
            ->add('diplome', FileType::class, [
                'label' => 'Diplomes',
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png"
                        ]
                    ]),
                    // "invalid_message"=>"Votre fichier ne dois pas depasser 2M et dois etre JPEG ou PNG"
                ]
            ])
            ->add('lettre_motivation', FileType::class, [
                'label' => 'Lettre de motivation',
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png"
                        ]
                    ]),
                    // "invalid_message"=>"Votre fichier ne dois pas depasser 2M et dois etre JPEG ou PNG"
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => "Choisir une recente photo d'identité",
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png"
                        ]
                    ]),
                    // "invalid_message"=>"Votre fichier ne dois pas depasser 2M et dois etre JPEG ou PNG"
                ]
            ])
            ->add('Postuler', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
