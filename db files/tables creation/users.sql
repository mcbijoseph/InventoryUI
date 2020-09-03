CREATE TABLE `inventory`.`users` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `isActive` BOOLEAN NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;


ALTER TABLE `inventory`.`users` CHANGE COLUMN `isActive` `InActive` TINYINT(1) NULL DEFAULT NULL;
