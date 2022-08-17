<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220817090503 extends AbstractMigration
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
        $this->addSql('CREATE TABLE lecturer_subject (lecturer_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_90086B4DBA2D8762 (lecturer_id), INDEX IDX_90086B4D23EDC87 (subject_id), PRIMARY KEY(lecturer_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mark (id INT AUTO_INCREMENT NOT NULL, stu_id_id INT DEFAULT NULL, subj_id_id INT DEFAULT NULL, mark VARCHAR(255) NOT NULL, INDEX IDX_6674F271FDD35830 (stu_id_id), INDEX IDX_6674F271BD76B772 (subj_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, class_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, dob DATE NOT NULL, sex VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_B723AF339993BF61 (class_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lecturer_classes ADD CONSTRAINT FK_692B2BF2BA2D8762 FOREIGN KEY (lecturer_id) REFERENCES lecturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecturer_classes ADD CONSTRAINT FK_692B2BF29E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecturer_subject ADD CONSTRAINT FK_90086B4DBA2D8762 FOREIGN KEY (lecturer_id) REFERENCES lecturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecturer_subject ADD CONSTRAINT FK_90086B4D23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271FDD35830 FOREIGN KEY (stu_id_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271BD76B772 FOREIGN KEY (subj_id_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF339993BF61 FOREIGN KEY (class_id_id) REFERENCES classes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecturer_classes DROP FOREIGN KEY FK_692B2BF2BA2D8762');
        $this->addSql('ALTER TABLE lecturer_classes DROP FOREIGN KEY FK_692B2BF29E225B24');
        $this->addSql('ALTER TABLE lecturer_subject DROP FOREIGN KEY FK_90086B4DBA2D8762');
        $this->addSql('ALTER TABLE lecturer_subject DROP FOREIGN KEY FK_90086B4D23EDC87');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271FDD35830');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271BD76B772');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF339993BF61');
        $this->addSql('DROP TABLE classes');
        $this->addSql('DROP TABLE lecturer');
        $this->addSql('DROP TABLE lecturer_classes');
        $this->addSql('DROP TABLE lecturer_subject');
        $this->addSql('DROP TABLE mark');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE subject');
    }
}
