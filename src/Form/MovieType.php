<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa el título de la película',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'El título no puede tener más de {{ limit }} caracteres',
                    ]),
                ],
            ])
            ->add('sinopsis', TextareaType::class, [
                'label' => 'Sinopsis',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa la sinopsis',
                    ]),
                ],
            ])
            ->add('genero', ChoiceType::class, [
                'label' => 'Género',
                'choices' => [
                    'Acción' => 'Acción',
                    'Aventura' => 'Aventura',
                    'Animación' => 'Animación',
                    'Biografía' => 'Biografía',
                    'Ciencia Ficción' => 'Ciencia Ficción',
                    'Comedia' => 'Comedia',
                    'Crimen' => 'Crimen',
                    'Documental' => 'Documental',
                    'Drama' => 'Drama',
                    'Familiar' => 'Familiar',
                    'Fantasía' => 'Fantasía',
                    'Film Noir' => 'Film Noir',
                    'Historia' => 'Historia',
                    'Horror' => 'Horror',
                    'Musical' => 'Musical',
                    'Misterio' => 'Misterio',
                    'Romance' => 'Romance',
                    'Suspenso' => 'Suspenso',
                    'Guerra' => 'Guerra',
                    'Western' => 'Western',
                ],
                'placeholder' => 'Selecciona un género',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor selecciona un género',
                    ]),
                ],
            ])
            ->add('anio', IntegerType::class, [
                'label' => 'Año',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa el año',
                    ]),
                    new Range([
                        'min' => 1900,
                        'max' => 2100,
                        'notInRangeMessage' => 'El año debe estar entre {{ min }} y {{ max }}',
                    ]),
                ],
            ])
            ->add('director', TextType::class, [
                'label' => 'Director',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'El nombre del director no puede tener más de {{ limit }} caracteres',
                    ]),
                ],
            ])
            ->add('duracion', IntegerType::class, [
                'label' => 'Duración (minutos)',
                'required' => false,
                'constraints' => [
                    new Positive([
                        'message' => 'La duración debe ser un número positivo',
                    ]),
                ],
            ])
            ->add('poster', FileType::class, [
                'label' => 'Póster de la Película',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor sube una imagen válida (JPG, PNG)',
                        'maxSizeMessage' => 'El archivo no puede pesar más de {{ limit }} {{ suffix }}',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}