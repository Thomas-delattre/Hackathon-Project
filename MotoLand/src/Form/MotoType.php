<?php

namespace App\Form;

use App\Entity\Moto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le titre ne peut pas faire moins de deux caractères',
                        'max' => 100,
                    ])
                ]
            ])
            ->add('description', TextareaType::class,[
                'label'=> 'Description'
            ])
            ->add('media', FileType::class, [
                'label' => 'image',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mettre une photo'
                    ])
                ]
            ])
            ->add('Ajouter', SubmitType::class,[
                'label' => 'Ajouter une moto'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Moto::class,
        ]);
    }
}
