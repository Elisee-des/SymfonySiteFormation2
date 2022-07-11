<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ExportationVilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', FileType::class, [
                "constraints" => [
                    new File([
                        "maxSize" => "2M",
                        "mimeTypes" => [
                            "application/vnd.oasis.opendocument.spreadsheet",
                            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        ]
                    ])
                ]
            ])
            ->add('Exporter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
