SET FOREIGN_KEY_CHECKS=0;

START TRANSACTION;

DROP TABLE IF EXISTS `advert`;

CREATE TABLE IF NOT EXISTS `advert` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` enum('new','approved','blocked') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `type` enum('job','project') COLLATE utf8mb4_unicode_ci DEFAULT 'job',
  `company` smallint(5) unsigned DEFAULT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `location` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company` (`company`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=20 ;

DROP TABLE IF EXISTS `company`;

CREATE TABLE IF NOT EXISTS `company` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `registered` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` enum('new','approved','blocked') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=10 ;

ALTER TABLE `advert`
  ADD CONSTRAINT `advert_ibfk_1` FOREIGN KEY (`company`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `registered` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `status` enum('new','approved','blocked') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `role` enum('company','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

SET FOREIGN_KEY_CHECKS=1;

COMMIT;
