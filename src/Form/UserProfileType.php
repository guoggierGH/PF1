<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa tu nombre',
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'El nombre no puede tener más de {{ limit }} caracteres',
                    ]),
                ],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa tu apellido',
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'El apellido no puede tener más de {{ limit }} caracteres',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa tu correo electrónico',
                    ]),
                    new Email([
                        'message' => 'Por favor ingresa un correo electrónico válido',
                    ]),
                ],
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa tu fecha de nacimiento',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}