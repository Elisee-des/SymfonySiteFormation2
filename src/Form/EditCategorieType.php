<?php

namespace App\Form;

use App\Entity\Categorie;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description', CKEditorType::class, [
                'label'=> 'Description'
            ])
            ->add('petitedescription')
            ->add('images', FileType::class, [
                "mapped"=>false,
                "constraints"=>[
                    new File([
                        "maxSize"=>"2M",
                        "mimeTypes"=>[
                            "image/jpeg",
                            "image/png"
                        ]
                    ])
                ]
            ])
            ->add('Modifier', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
