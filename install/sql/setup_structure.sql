-- MySQL Script generated by MySQL Workbench
-- Thu Feb  8 12:42:26 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `melis_site_translation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `melis_site_translation` (
  `mst_id` INT NOT NULL AUTO_INCREMENT,
  `mst_key` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`mst_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `melis_site_translation_text`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `melis_site_translation_text` (
  `mstt_id` INT NOT NULL AUTO_INCREMENT,
  `mstt_mst_id` INT NOT NULL,
  `mstt_lang_id` INT NOT NULL,
  `mstt_text` TEXT NOT NULL,
  PRIMARY KEY (`mstt_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
