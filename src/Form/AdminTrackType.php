<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Style;
use App\Entity\Track;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextAreaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('releasedAt', DateTimeType::class, [
                'label'=>'Date de release',
                'attr' => ['class' => 'form-control']
            ])

            ->add('style', EntityType::class, [
                'label' => 'Style',
                'class' => Style::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un style',
                'multiple' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('artiste', EntityType::class, [
                'label' => 'Artiste',
                'class' => Artiste::class,
                'choice_label' => 'alias',
                'placeholder' => 'Choisissez un artiste',
                'attr' => ['class' => 'form-control']
            ])

            ->add('spotifyLink', TextType::class, [
                'label'=>'Lien vers spotify',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])

            ->add('appleMusicLink', TextType::class, [
                'label'=>'Lien vers Apple Music',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('youtubeLink', TextType::class, [
                'label'=>'Lien vers youtube',
                'attr' => ['class' => 'form-control'],
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
            'data_class' => Track::class,
        ]);
    }
}
