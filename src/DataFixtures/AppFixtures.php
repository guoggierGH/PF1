<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Group;
use App\Entity\Review;
use App\Entity\Recommendation;
use App\Entity\GroupRecommendation;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ==================== USUARIOS ====================
        
        // Usuario Administrador
        $admin = new User();
        $admin->setEmail('admin@movierecommendations.com');
        $admin->setNombre('Admin');
        $admin->setApellido('Sistema');
        $admin->setFechaNacimiento(new \DateTime('1990-01-01'));
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Usuarios normales
        $usuarios = [
            [
                'email' => 'juan.perez@email.com',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'fecha' => '1995-03-15',
                'password' => 'password123'
            ],
            [
                'email' => 'maria.garcia@email.com',
                'nombre' => 'María',
                'apellido' => 'García',
                'fecha' => '1992-07-22',
                'password' => 'password123'
            ],
            [
                'email' => 'carlos.lopez@email.com',
                'nombre' => 'Carlos',
                'apellido' => 'López',
                'fecha' => '1988-11-10',
                'password' => 'password123'
            ],
            [
                'email' => 'ana.martinez@email.com',
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'fecha' => '1997-05-30',
                'password' => 'password123'
            ],
            [
                'email' => 'luis.rodriguez@email.com',
                'nombre' => 'Luis',
                'apellido' => 'Rodríguez',
                'fecha' => '1993-09-18',
                'password' => 'password123'
            ],
        ];

        $userObjects = [];
        foreach ($usuarios as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setNombre($userData['nombre']);
            $user->setApellido($userData['apellido']);
            $user->setFechaNacimiento(new \DateTime($userData['fecha']));
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            
            $manager->persist($user);
            $userObjects[] = $user;
        }

        // ==================== GRUPOS ====================
        
        $grupos = [
            [
                'nombre' => 'Amantes del Cine Clásico',
                'descripcion' => 'Para los que aprecian las grandes obras maestras del cine de los años 40 a los 80. Discutimos sobre técnica, narrativa y la historia detrás de las películas.',
            ],
            [
                'nombre' => 'Ciencia Ficción Extrema',
                'descripcion' => 'Dedicado a explorar los límites de la ciencia ficción. Desde space operas hasta distopías tecnológicas.',
            ],
            [
                'nombre' => 'Cine de Terror',
                'descripcion' => 'Para los valientes que disfrutan del suspenso, el horror psicológico y las películas que te mantienen despierto por la noche.',
            ],
            [
                'nombre' => 'Películas Independientes',
                'descripcion' => 'Descubriendo joyas ocultas del cine independiente y directores emergentes.',
            ],
            [
                'nombre' => 'Animación para Adultos',
                'descripcion' => 'No solo para niños. Exploramos la animación con narrativas profundas y visuales impresionantes.',
            ],
        ];

        $groupObjects = [];
        foreach ($grupos as $index => $grupoData) {
            $group = new Group();
            $group->setNombre($grupoData['nombre']);
            $group->setDescripcion($grupoData['descripcion']);
            
            // Agregar miembros aleatorios a cada grupo
            $numMiembros = rand(2, 4);
            $miembrosSeleccionados = array_rand($userObjects, $numMiembros);
            
            if (!is_array($miembrosSeleccionados)) {
                $miembrosSeleccionados = [$miembrosSeleccionados];
            }
            
            foreach ($miembrosSeleccionados as $miembroIndex) {
                $group->addMember($userObjects[$miembroIndex]);
            }
            
            $manager->persist($group);
            $groupObjects[] = $group;
        }

        // ==================== PELÍCULAS VISTAS ====================
        
        // Marcar algunas películas como vistas por usuarios
        foreach ($userObjects as $user) {
            $numVistas = rand(3, 8);
            $peliculasUsadas = [];
            
            for ($i = 0; $i < $numVistas; $i++) {
                $movieIndex = rand(0, 24);
                
                // Evitar duplicados
                if (in_array($movieIndex, $peliculasUsadas)) {
                    continue;
                }
                
                $peliculasUsadas[] = $movieIndex;
                $movieRef = 'movie_' . $movieIndex;
                
                try {
                    $movie = $this->getReference($movieRef, Movie::class);
                    if (!$user->hasViewedMovie($movie)) {
                        $user->addViewedMovie($movie);
                    }
                } catch (\Exception $e) {
                    // Si no existe la referencia, continuar
                    continue;
                }
            }
        }

        // ==================== RESEÑAS ====================
        
        $comentarios = [
            'Una obra maestra absoluta. Cada escena está perfectamente construida.',
            'Me mantuvo al borde del asiento de principio a fin.',
            'Las actuaciones son increíbles, especialmente el protagonista.',
            'Visualmente impresionante, pero la historia se queda corta.',
            'No me gustó tanto como esperaba, demasiado lenta.',
            'Perfecta para ver con amigos, muy entretenida.',
            'Una experiencia cinematográfica única.',
            'La banda sonora es épica y complementa perfectamente la película.',
            'No la entendí completamente, necesito verla de nuevo.',
            'Sobrevalorada, esperaba mucho más.',
            'Me encantó cada minuto, ya la he visto 3 veces.',
            'Buena pero no excelente, tiene sus momentos.',
        ];

        foreach ($userObjects as $user) {
            // Cada usuario hace entre 2 y 5 reseñas
            $numReviews = rand(2, 5);
            $peliculasReseñadas = [];
            
            for ($i = 0; $i < $numReviews; $i++) {
                $movieKey = rand(0, 24);
                
                // Evitar reseñar la misma película dos veces
                if (in_array($movieKey, $peliculasReseñadas)) {
                    continue;
                }
                
                $peliculasReseñadas[] = $movieKey;
                $movieRef = 'movie_' . $movieKey;
                
                try {
                    $movie = $this->getReference($movieRef, Movie::class);
                    
                    $review = new Review();
                    $review->setUser($user);
                    $review->setMovie($movie);
                    $review->setPuntuacion(rand(3, 5)); // Puntuaciones mayormente positivas
                    $review->setComentario($comentarios[array_rand($comentarios)]);
                    
                    $manager->persist($review);
                } catch (\Exception $e) {
                    // Si no existe la referencia, continuar
                    continue;
                }
            }
        }

        // ==================== RECOMENDACIONES 1-A-1 ====================
        
        // Crear algunas recomendaciones entre usuarios
        $recommendationsCreated = 0;
        $maxAttempts = 20;
        $attempts = 0;
        
        while ($recommendationsCreated < 10 && $attempts < $maxAttempts) {
            $attempts++;
            
            $fromUser = $userObjects[array_rand($userObjects)];
            $toUser = $userObjects[array_rand($userObjects)];
            
            // No recomendarse a sí mismo
            if ($fromUser === $toUser) {
                continue;
            }
            
            $movieRef = 'movie_' . rand(0, 24);
            
            try {
                $movie = $this->getReference($movieRef, Movie::class);
                
                $recommendation = new Recommendation();
                $recommendation->setFromUser($fromUser);
                $recommendation->setToUser($toUser);
                $recommendation->setMovie($movie);
                $recommendation->setComentario('¡Tienes que ver esta película! Te va a encantar.');
                $recommendation->setVisto(rand(0, 1) === 1); // 50% leídas
                
                $manager->persist($recommendation);
                $recommendationsCreated++;
            } catch (\Exception $e) {
                // Si no existe la referencia, continuar
                continue;
            }
        }

        // ==================== RECOMENDACIONES DE GRUPOS ====================
        
        // Cada grupo tiene algunas recomendaciones
        foreach ($groupObjects as $group) {
            $numRecommendations = rand(2, 5);
            $recommendationsCreated = 0;
            $attempts = 0;
            $maxAttempts = 10;
            
            while ($recommendationsCreated < $numRecommendations && $attempts < $maxAttempts) {
                $attempts++;
                
                // Seleccionar un miembro aleatorio del grupo
                $members = $group->getMembers()->toArray();
                if (empty($members)) {
                    break;
                }
                
                $user = $members[array_rand($members)];
                $movieRef = 'movie_' . rand(0, 24);
                
                try {
                    $movie = $this->getReference($movieRef, Movie::class);
                    
                    $groupRecommendation = new GroupRecommendation();
                    $groupRecommendation->setUser($user);
                    $groupRecommendation->setGroup($group);
                    $groupRecommendation->setMovie($movie);
                    
                    $comentariosGrupo = [
                        '¡Esta película es perfecta para nuestro grupo!',
                        'Les va a fascinar esta obra maestra.',
                        'Muy acorde a nuestros gustos, altamente recomendada.',
                        'No se la pierdan, es espectacular.',
                        'Una joya que descubrí este fin de semana.',
                    ];
                    
                    $groupRecommendation->setComentario($comentariosGrupo[array_rand($comentariosGrupo)]);
                    
                    $manager->persist($groupRecommendation);
                    $recommendationsCreated++;
                } catch (\Exception $e) {
                    // Si no existe la referencia, continuar
                    continue;
                }
            }
        }

        $manager->flush();

        echo "\n✅ Fixtures cargados exitosamente!\n\n";
        echo "👤 Usuarios creados:\n";
        echo "   - Admin: admin@movierecommendations.com / admin123\n";
        foreach ($usuarios as $u) {
            echo "   - {$u['nombre']} {$u['apellido']}: {$u['email']} / password123\n";
        }
        echo "\n🎬 {$this->countMovies()} películas creadas\n";
        echo "👥 " . count($groupObjects) . " grupos creados\n";
        echo "⭐ Reseñas y recomendaciones generadas\n\n";
    }

    public function getDependencies(): array
    {
        return [
            MovieFixtures::class,
        ];
    }

    private function countMovies(): int
    {
        return 25; // Número de películas en MovieFixtures
    }
}