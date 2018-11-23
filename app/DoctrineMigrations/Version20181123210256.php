<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181123210256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
                CREATE TABLE IF NOT EXISTS `message` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `tender_id` int(11) DEFAULT NULL,
  `sender_team_id` int(11) DEFAULT NULL,
  `receiver_team_id` int(11) DEFAULT NULL,
  `tread_id` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307F9245DE54` (`tender_id`),
  KEY `IDX_B6BD307FA49A1E65` (`sender_team_id`),
  KEY `IDX_B6BD307F5455E90C` (`receiver_team_id`),
  CONSTRAINT `FK_B6BD307F5455E90C` FOREIGN KEY (`receiver_team_id`) REFERENCES `team` (`id`),
  CONSTRAINT `FK_B6BD307F9245DE54` FOREIGN KEY (`tender_id`) REFERENCES `tender` (`id`),
  CONSTRAINT `FK_B6BD307FA49A1E65` FOREIGN KEY (`sender_team_id`) REFERENCES `team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        ");

        $this->addSql("
CREATE TABLE IF NOT EXISTS `request` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `tender_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3B978F9F9245DE54` (`tender_id`),
  KEY `IDX_3B978F9F296CD8AE` (`team_id`),
  CONSTRAINT `FK_3B978F9F296CD8AE` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  CONSTRAINT `FK_3B978F9F9245DE54` FOREIGN KEY (`tender_id`) REFERENCES `tender` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;      
        ");

        $this->addSql("
CREATE TABLE IF NOT EXISTS `team` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext COLLATE utf8_unicode_ci NOT NULL,
  `manager` longtext COLLATE utf8_unicode_ci NOT NULL,
  `city` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C4E0A61FA76ED395` (`user_id`),
  CONSTRAINT `FK_C4E0A61FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;        
        ");

        $this->addSql("
CREATE TABLE IF NOT EXISTS `tender` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `organizer_id` int(11) DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `geo_point` longtext COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `max_team` int(11) NOT NULL,
  `max_player` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42057A77876C4DDA` (`organizer_id`),
  CONSTRAINT `FK_42057A77876C4DDA` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;        
        ");

        $this->addSql("
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1483A5E9A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
