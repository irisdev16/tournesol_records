<?php

namespace App\Form;

use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class,[
                'label' => 'Prénom',
                'required' => false,
                ])
            ->add('name',TextType::class,[
                'label' => 'Nom',
                'required' => false
            ])
            ->add('email',TextType::class,[
                'label' => 'Email',
                'required' => false
            ])
            ->add('address',TextType::class,[
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('alias', TextType::class,[
                'label' => 'Alias',
                'required' => false
            ])
            ->add('description', TextAreaType::class,[
                'label' => 'Description',
                'required' => false
    ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
