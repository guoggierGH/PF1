<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('puntuacion', ChoiceType::class, [
                'label' => 'Puntuación',
                'choices' => [
                    '⭐ 1 - Muy mala' => 1,
                    '⭐⭐ 2 - Mala' => 2,
                    '⭐⭐⭐ 3 - Regular' => 3,
                    '⭐⭐⭐⭐ 4 - Buena' => 4,
                    '⭐⭐⭐⭐⭐ 5 - Excelente' => 5,
                ],
                'expanded' => false,
                'placeholder' => 'Selecciona una puntuación',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor selecciona una puntuación',
                    ]),
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'La puntuación debe estar entre {{ min }} y {{ max }}',
                    ]),
                ],
            ])
            ->add('comentario', TextareaType::class, [
                'label' => 'Comentario',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Escribe tu opinión sobre la película (opcional)...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}