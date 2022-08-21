<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220821184118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecturer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dob DATE NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecturer_classes (lecturer_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_692B2BF2BA2D8762 (lecturer_id), INDEX IDX_692B2BF29E225B24 (classes_id), PRIMARY KEY(lecturer_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, class_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, dob DATE NOT NULL, sex VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_B723AF339993BF61 (class_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lecturer_classes ADD CONSTRAINT FK_692B2BF2BA2D8762 FOREIGN KEY (lecturer_id) REFERENCES lecturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecturer_classes ADD CONSTRAINT FK_692B2BF29E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339993BF61 FOREIGN KEY (class_id_id) REFERENCES classes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecturer_classes DROP FOREIGN KEY FK_692B2BF2BA2D8762');
        $this->addSql('ALTER TABLE lecturer_classes DROP FOREIGN KEY FK_692B2BF29E225B24');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF339993BF61');
        $this->addSql('DROP TABLE classes');
        $this->addSql('DROP TABLE lecturer');
        $this->addSql('DROP TABLE lecturer_classes');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE user');
    }
}
