<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movies = [
            // Clásicos
            [
                'titulo' => 'El Padrino',
                'sinopsis' => 'La historia de la familia Corleone, una de las más poderosas familias del crimen organizado en Nueva York. Don Vito Corleone es el patriarca de la familia, un hombre respetado y temido. Cuando un rival intenta asesinarlo, su hijo menor Michael se ve obligado a tomar las riendas del negocio familiar.',
                'genero' => 'Drama',
                'anio' => 1972,
                'director' => 'Francis Ford Coppola',
                'duracion' => 175,
            ],
            [
                'titulo' => 'Pulp Fiction',
                'sinopsis' => 'La película entrelaza varias historias de criminales en Los Ángeles. Dos asesinos a sueldo, Vincent Vega y Jules Winnfield, trabajan para el mafioso Marsellus Wallace. La narrativa no lineal explora temas de redención, violencia y coincidencia.',
                'genero' => 'Crimen',
                'anio' => 1994,
                'director' => 'Quentin Tarantino',
                'duracion' => 154,
            ],
            [
                'titulo' => 'Cadena Perpetua',
                'sinopsis' => 'Andy Dufresne, un banquero exitoso, es condenado por el asesinato de su esposa y su amante. En la prisión de Shawshank, Andy entabla una amistad con Red, un convicto de larga data. A través de los años, Andy mantiene su inocencia y su esperanza de libertad.',
                'genero' => 'Drama',
                'anio' => 1994,
                'director' => 'Frank Darabont',
                'duracion' => 142,
            ],
            
            // Ciencia Ficción
            [
                'titulo' => 'Inception',
                'sinopsis' => 'Dom Cobb es un ladrón especializado en extraer secretos del subconsciente durante el sueño. Se le ofrece una oportunidad de redención: en lugar de robar una idea, debe plantarla en la mente de alguien. La tarea requiere adentrarse en múltiples niveles de sueños.',
                'genero' => 'Ciencia Ficción',
                'anio' => 2010,
                'director' => 'Christopher Nolan',
                'duracion' => 148,
            ],
            [
                'titulo' => 'Matrix',
                'sinopsis' => 'Thomas Anderson es un programador que descubre que la realidad que conoce es una simulación creada por máquinas inteligentes. Bajo el alias de Neo, se une a un grupo de rebeldes para luchar contra las máquinas y liberar a la humanidad.',
                'genero' => 'Ciencia Ficción',
                'anio' => 1999,
                'director' => 'Lana Wachowski, Lilly Wachowski',
                'duracion' => 136,
            ],
            [
                'titulo' => 'Blade Runner 2049',
                'sinopsis' => 'Treinta años después de los eventos de la primera película, un nuevo blade runner, el Oficial K, descubre un secreto que podría sumir a la sociedad en el caos. Su descubrimiento lo lleva a buscar a Rick Deckard, desaparecido hace décadas.',
                'genero' => 'Ciencia Ficción',
                'anio' => 2017,
                'director' => 'Denis Villeneuve',
                'duracion' => 164,
            ],
            
            // Acción
            [
                'titulo' => 'Mad Max: Furia en el Camino',
                'sinopsis' => 'En un mundo post-apocalíptico, Max se une a Furiosa, una comandante rebelde que huye del tirano Immortan Joe con un grupo de mujeres esclavizadas. Juntos deben sobrevivir a una persecución mortal a través del desierto.',
                'genero' => 'Acción',
                'anio' => 2015,
                'director' => 'George Miller',
                'duracion' => 120,
            ],
            [
                'titulo' => 'John Wick',
                'sinopsis' => 'John Wick es un legendario asesino a sueldo retirado que regresa al mundo del crimen cuando unos delincuentes matan a su perro, el último regalo de su esposa fallecida. Su búsqueda de venganza desencadena una ola de violencia.',
                'genero' => 'Acción',
                'anio' => 2014,
                'director' => 'Chad Stahelski',
                'duracion' => 101,
            ],
            
            // Comedia
            [
                'titulo' => 'El Gran Hotel Budapest',
                'sinopsis' => 'La historia gira en torno a Gustave H., el legendario conserje de un famoso hotel europeo, y Zero Moustafa, el botones que se convierte en su amigo de confianza. Juntos se ven envueltos en el robo de una valiosa pintura renacentista.',
                'genero' => 'Comedia',
                'anio' => 2014,
                'director' => 'Wes Anderson',
                'duracion' => 99,
            ],
            [
                'titulo' => 'Parásitos',
                'sinopsis' => 'La familia Kim, pobre y desempleada, se infiltra gradualmente en la vida de la rica familia Park. Lo que comienza como una estafa ingeniosa se convierte en una historia sobre la desigualdad social y sus consecuencias.',
                'genero' => 'Comedia',
                'anio' => 2019,
                'director' => 'Bong Joon-ho',
                'duracion' => 132,
            ],
            
            // Horror
            [
                'titulo' => '¡Huye!',
                'sinopsis' => 'Chris, un joven afroamericano, visita la casa de los padres de su novia blanca en una finca aislada. Aunque inicialmente todo parece idílico, pronto descubre que la familia esconde oscuros secretos racistas y perturbadores.',
                'genero' => 'Horror',
                'anio' => 2017,
                'director' => 'Jordan Peele',
                'duracion' => 104,
            ],
            [
                'titulo' => 'El Conjuro',
                'sinopsis' => 'Los investigadores paranormales Ed y Lorraine Warren ayudan a una familia aterrorizada por una presencia oscura en su granja. El caso se convierte en uno de los más espeluznantes de su carrera.',
                'genero' => 'Horror',
                'anio' => 2013,
                'director' => 'James Wan',
                'duracion' => 112,
            ],
            
            // Animación
            [
                'titulo' => 'Spider-Man: Un Nuevo Universo',
                'sinopsis' => 'Miles Morales se convierte en Spider-Man y descubre que no es el único: otros Spider-People de dimensiones paralelas llegan a su universo. Juntos deben detener a un villano que amenaza con destruir todas las realidades.',
                'genero' => 'Animación',
                'anio' => 2018,
                'director' => 'Bob Persichetti, Peter Ramsey, Rodney Rothman',
                'duracion' => 117,
            ],
            [
                'titulo' => 'Coco',
                'sinopsis' => 'Miguel es un niño de 12 años que sueña con ser músico, pero su familia tiene prohibida la música. El Día de Muertos, termina en la Tierra de los Muertos y debe descubrir la verdad sobre su familia para poder regresar.',
                'genero' => 'Animación',
                'anio' => 2017,
                'director' => 'Lee Unkrich',
                'duracion' => 105,
            ],
            
            // Fantasía
            [
                'titulo' => 'El Señor de los Anillos: La Comunidad del Anillo',
                'sinopsis' => 'Frodo Bolsón hereda un anillo mágico que resulta ser el Anillo Único, forjado por el Señor Oscuro Sauron. Junto a una comunidad de valientes compañeros, debe emprender un peligroso viaje para destruirlo en el Monte del Destino.',
                'genero' => 'Fantasía',
                'anio' => 2001,
                'director' => 'Peter Jackson',
                'duracion' => 178,
            ],
            [
                'titulo' => 'El Laberinto del Fauno',
                'sinopsis' => 'En la España de 1944, Ofelia descubre un misterioso laberinto donde conoce a un fauno que le revela que es una princesa y debe completar tres tareas antes de la luna llena para reclamar su inmortalidad.',
                'genero' => 'Fantasía',
                'anio' => 2006,
                'director' => 'Guillermo del Toro',
                'duracion' => 118,
            ],
            
            // Romance
            [
                'titulo' => 'Eterno Resplandor de una Mente sin Recuerdos',
                'sinopsis' => 'Joel descubre que su ex novia Clementine ha borrado todos sus recuerdos sobre él mediante un procedimiento experimental. Decide hacer lo mismo, pero durante el proceso comienza a revivir sus momentos juntos y se arrepiente de su decisión.',
                'genero' => 'Romance',
                'anio' => 2004,
                'director' => 'Michel Gondry',
                'duracion' => 108,
            ],
            [
                'titulo' => 'La La Land',
                'sinopsis' => 'Mia, una aspirante a actriz, y Sebastian, un músico de jazz, se enamoran en Los Ángeles mientras persiguen sus sueños. Su relación es puesta a prueba cuando sus carreras empiezan a despegar en direcciones diferentes.',
                'genero' => 'Romance',
                'anio' => 2016,
                'director' => 'Damien Chazelle',
                'duracion' => 128,
            ],
            
            // Suspenso
            [
                'titulo' => 'El Origen',
                'sinopsis' => 'Dom Cobb es un ladrón experto en el arte de la extracción: robar secretos del subconsciente durante el estado de sueño. Su rara habilidad lo ha convertido en un jugador codiciado en el traicionero nuevo mundo del espionaje corporativo.',
                'genero' => 'Suspenso',
                'anio' => 2010,
                'director' => 'Christopher Nolan',
                'duracion' => 148,
            ],
            [
                'titulo' => 'Joker',
                'sinopsis' => 'Arthur Fleck es un comediante fracasado en Gotham City que sufre de una enfermedad mental. A medida que es ignorado y maltratado por la sociedad, se transforma gradualmente en el icónico villano conocido como el Joker.',
                'genero' => 'Suspenso',
                'anio' => 2019,
                'director' => 'Todd Phillips',
                'duracion' => 122,
            ],
            
            // Recientes 2020+
            [
                'titulo' => 'Dune',
                'sinopsis' => 'Paul Atreides, un joven brillante, debe viajar al planeta más peligroso del universo para asegurar el futuro de su familia y su pueblo. Fuerzas malévolas se enfrentan por el control del recurso más preciado del planeta.',
                'genero' => 'Ciencia Ficción',
                'anio' => 2021,
                'director' => 'Denis Villeneuve',
                'duracion' => 155,
            ],
            [
                'titulo' => 'Todo en Todas Partes al Mismo Tiempo',
                'sinopsis' => 'Evelyn Wang es una inmigrante china dueña de una lavandería que debe conectarse con versiones de sí misma de universos paralelos para evitar que un ser poderoso destruya el multiverso.',
                'genero' => 'Ciencia Ficción',
                'anio' => 2022,
                'director' => 'Daniel Kwan, Daniel Scheinert',
                'duracion' => 139,
            ],
            [
                'titulo' => 'Oppenheimer',
                'sinopsis' => 'La historia del físico J. Robert Oppenheimer y su papel en el desarrollo de la bomba atómica durante la Segunda Guerra Mundial, así como las consecuencias personales y políticas que enfrentó.',
                'genero' => 'Historia',
                'anio' => 2023,
                'director' => 'Christopher Nolan',
                'duracion' => 180,
            ],
            [
                'titulo' => 'Barbie',
                'sinopsis' => 'Barbie vive feliz en Barbieland hasta que comienza a tener pensamientos sobre la muerte. Viaja al mundo real para descubrir qué está mal y aprende sobre la complejidad de ser humano.',
                'genero' => 'Comedia',
                'anio' => 2023,
                'director' => 'Greta Gerwig',
                'duracion' => 114,
            ],
        ];

        foreach ($movies as $key => $movieData) {
            $movie = new Movie();
            $movie->setTitulo($movieData['titulo']);
            $movie->setSinopsis($movieData['sinopsis']);
            $movie->setGenero($movieData['genero']);
            $movie->setAnio($movieData['anio']);
            $movie->setDirector($movieData['director']);
            $movie->setDuracion($movieData['duracion']);
            
            $manager->persist($movie);
            
            // Guardar referencia para usar en otros fixtures
            $this->addReference('movie_' . $key, $movie);
        }

        $manager->flush();
    }
}