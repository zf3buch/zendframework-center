SET FOREIGN_KEY_CHECKS=0;

START TRANSACTION;

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

INSERT INTO `user` (`id`, `registered`, `updated`, `status`, `role`, `email`, `password`) VALUES
(1, '2016-07-03 11:21:32', '2016-07-03 11:22:05', 'approved', 'company', 'company@zendframework.center', '$2y$10$LgI03AFI.lu5lB1t4Rfpy.ipppiAE79Xz1Dk6pi1RVIAW2./5at2.'),
(2, '2016-07-03 11:21:45', '2016-07-03 11:22:12', 'approved', 'admin', 'admin@zendframework.center', '$2y$10$MNLzqwHkE4HNbjWIiF7i3e8aKvrBsPm8CRUF3aFbT9f1nAMeOmPDO');

SET FOREIGN_KEY_CHECKS=1;

COMMIT;
