<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "constraints" => [
                    new NotBlank(),
                    new Length(null, 5)
                ],
                "first_options" => [
                    "label" => "Saisir un mot de passe",
                ],
                "second_options" => [
                    "label" => "Repeter le mot de passe",
                ],
                "invalid_message" => "Veuaille entrez un mot de passe valide"
            ])
            ->add('email')
            ->add('telephone')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Utilisateur" => "ROLE_USER",
                    "Administrateur" => "ROLE_ADMIN",
                    "Desactiviter" => "ISDESACTIVED"
                ],
                "expanded" => true,
                "multiple" => true,
                "label" => "Definir le role"
            ])
            ->add('photoFile', FileType::class, [
                "mapped" => false,
                "attr" => [
                    "label" => "Ajouter un image"
                ],
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
            ->add('ville')
            ->add('Creer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
