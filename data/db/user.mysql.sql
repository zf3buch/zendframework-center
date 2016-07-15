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
(1, '2016-07-03 11:21:32', '2016-07-03 11:22:05', 'approved', 'company', 'company@zendframework.center', '$2y$10$uo7E9Mr7bG8fWQKR2f7M0uscyqKxThLuwqkN7yT75zKt08if.oHp2'),
(2, '2016-07-03 11:21:45', '2016-07-03 11:22:12', 'approved', 'admin', 'admin@zendframework.center', '$2y$10$gTgRb/9Vlb9GAYDgtbtPquK7hCp2qRvGa80xNFhAh8Ei6x4unlHzG');

SET FOREIGN_KEY_CHECKS=1;

COMMIT;
