<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251110145837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(150) NOT NULL, descripcion LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_recommendation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, group_id INT NOT NULL, movie_id INT NOT NULL, comentario LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_DBBFEDA0A76ED395 (user_id), INDEX IDX_DBBFEDA0FE54D947 (group_id), INDEX IDX_DBBFEDA08F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, sinopsis LONGTEXT NOT NULL, genero VARCHAR(100) NOT NULL, anio INT NOT NULL, director VARCHAR(100) DEFAULT NULL, poster VARCHAR(255) DEFAULT NULL, duracion INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_group (movie_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_13825E598F93B6FC (movie_id), INDEX IDX_13825E59FE54D947 (group_id), PRIMARY KEY(movie_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendation (id INT AUTO_INCREMENT NOT NULL, from_user_id INT NOT NULL, to_user_id INT NOT NULL, movie_id INT NOT NULL, comentario LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, visto TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_433224D22130303A (from_user_id), INDEX IDX_433224D229F6EE60 (to_user_id), INDEX IDX_433224D28F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, movie_id INT NOT NULL, puntuacion INT NOT NULL, comentario LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C68F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, apellido VARCHAR(100) NOT NULL, fecha_nacimiento DATE NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_membership (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_5132B337A76ED395 (user_id), INDEX IDX_5132B337FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_viewed (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_E4F8471A76ED395 (user_id), INDEX IDX_E4F84718F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_recommendation ADD CONSTRAINT FK_DBBFEDA0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE group_recommendation ADD CONSTRAINT FK_DBBFEDA0FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE group_recommendation ADD CONSTRAINT FK_DBBFEDA08F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_group ADD CONSTRAINT FK_13825E598F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_group ADD CONSTRAINT FK_13825E59FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D22130303A FOREIGN KEY (from_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D229F6EE60 FOREIGN KEY (to_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D28F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE group_membership ADD CONSTRAINT FK_5132B337A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_membership ADD CONSTRAINT FK_5132B337FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_viewed ADD CONSTRAINT FK_E4F8471A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_viewed ADD CONSTRAINT FK_E4F84718F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_recommendation DROP FOREIGN KEY FK_DBBFEDA0A76ED395');
        $this->addSql('ALTER TABLE group_recommendation DROP FOREIGN KEY FK_DBBFEDA0FE54D947');
        $this->addSql('ALTER TABLE group_recommendation DROP FOREIGN KEY FK_DBBFEDA08F93B6FC');
        $this->addSql('ALTER TABLE movie_group DROP FOREIGN KEY FK_13825E598F93B6FC');
        $this->addSql('ALTER TABLE movie_group DROP FOREIGN KEY FK_13825E59FE54D947');
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D22130303A');
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D229F6EE60');
        $this->addSql('ALTER TABLE recommendation DROP FOREIGN KEY FK_433224D28F93B6FC');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C68F93B6FC');
        $this->addSql('ALTER TABLE group_membership DROP FOREIGN KEY FK_5132B337A76ED395');
        $this->addSql('ALTER TABLE group_membership DROP FOREIGN KEY FK_5132B337FE54D947');
        $this->addSql('ALTER TABLE movie_viewed DROP FOREIGN KEY FK_E4F8471A76ED395');
        $this->addSql('ALTER TABLE movie_viewed DROP FOREIGN KEY FK_E4F84718F93B6FC');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_recommendation');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_group');
        $this->addSql('DROP TABLE recommendation');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE group_membership');
        $this->addSql('DROP TABLE movie_viewed');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
