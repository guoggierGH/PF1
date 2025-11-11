<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Recommendation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecommendationType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();

        $builder
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => function(Movie $movie) {
                    return $movie->getTitulo() . ' (' . $movie->getAnio() . ') - ' . $movie->getGenero();
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
            ->add('toUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getNombreCompleto() . ' (' . $user->getEmail() . ')';
                },
                'label' => 'Recomendar a',
                'placeholder' => 'Selecciona un usuario',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor selecciona un usuario',
                    ]),
                ],
                'query_builder' => function($repository) use ($currentUser) {
                    return $repository->createQueryBuilder('u')
                        ->where('u.id != :currentUserId')
                        ->setParameter('currentUserId', $currentUser ? $currentUser->getId() : 0)
                        ->orderBy('u.nombre', 'ASC');
                },
            ])
            ->add('comentario', TextareaType::class, [
                'label' => 'Mensaje',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Escribe un mensaje personalizado (opcional)...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recommendation::class,
        ]);
    }
}