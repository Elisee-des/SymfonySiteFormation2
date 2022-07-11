<?php

namespace App\Form;

use App\Entity\Formation;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', CKEditorType::class, [
                'label'=> 'Description'
            ])
            ->add('photoFile', FileType::class, [
                "mapped" => false,
                "label" => "Choisir une image",
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png"
                        ]
                    ])
                ]
            ])
            ->add('nombrePlace', NumberType::class)
            ->add('categorie')
            ->add('dateDebutFormation')
            ->add('dateFinFormation')
            ->add('is_actif', ChoiceType::class, [
                'label'=>'Afficher ou Cacher la formation',
                'choices'=>[
                    'afficher'=>'Afficher',
                    'cacher'=>'Cacher'
                ],
                
                
            ])
            ->add('Modifier', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
