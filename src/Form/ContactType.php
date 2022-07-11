<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Entre votre Nom',
                'attr' => [
                    'placeholder' => 'Elisee'
                ]
            ])

            ->add('sujet', TextType::class, [
                'label' => 'Entre votre sujet',
                'attr' => [
                    'placeholder' => 'sujet'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Entre votre Email',
                'attr' => [
                    'placeholder' => 'votreEmail@gmail.com'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Entre votre message',
                'attr' => [
                    'placeholder' => 'message'
                ]
            ])
            ->add('Envoyez', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
