<?php

namespace App\Form;

use App\Entity\GroupRecommendation;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GroupRecommendationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => function(Movie $movie) {
                    return $movie->getTitulo() . ' (' . $movie->getAnio() . ')';
                },
                'label' => 'Película',
                'placeholder' => 'Selecciona una película',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor selecciona una película',
                    ]),
                ],
                'query_builder' => function($repository) {
                    return $repository->createQueryBuilder('m')
                        ->orderBy('m.titulo', 'ASC');
                },
            ])
            ->add('comentario', TextareaType::class, [
                'label' => 'Comentario',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'placeholder' => '¿Por qué recomiendas esta película al grupo? (opcional)',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupRecommendation::class,
        ]);
    }
}