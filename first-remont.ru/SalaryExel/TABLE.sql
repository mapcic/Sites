CREATE TABLE IF NOT EXISTS `machine_salary_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` TEXT NOT NULL,
  `temp` TEXT NOT NULL,
  `uses` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
);