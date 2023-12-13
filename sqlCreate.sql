-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`BootTypen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BootTypen` (
  `TypeId` INT NOT NULL,
  `Bezeichnung` VARCHAR(45) NULL,
  PRIMARY KEY (`TypeId`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Boote`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Boote` (
  `BootId` INT NOT NULL AUTO_INCREMENT,
  `TypeId` INT NOT NULL,
  `Kaufdatum` DATE NULL,
  `Farbe` VARCHAR(45) NULL,
  `Seriennummer` VARCHAR(45) NULL,
  `Hersteller` VARCHAR(45) NULL,
  PRIMARY KEY (`BootId`),
  INDEX `fk_Boote_BootTypen1_idx` (`TypeId` ASC),
  CONSTRAINT `fk_Boote_BootTypen1`
    FOREIGN KEY (`TypeId`)
    REFERENCES `mydb`.`BootTypen` (`TypeId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Ort`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Ort` (
  `OrtId` INT NOT NULL AUTO_INCREMENT,
  `PLZ` INT NULL,
  `OrtName` VARCHAR(45) NULL,
  PRIMARY KEY (`OrtId`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Kunden`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Kunden` (
  `KundenId` INT NOT NULL  AUTO_INCREMENT,
  `Vorname` VARCHAR(45) NULL,
  `Nachname` VARCHAR(45) NULL,
  `Straße` VARCHAR(45) NULL,
  `Hausnummer` VARCHAR(45) NULL,
  `OrtId` INT NOT NULL,
  PRIMARY KEY (`KundenId`),
  INDEX `fk_Kunden_Ort1_idx` (`OrtId` ASC),
  CONSTRAINT `fk_Kunden_Ort1`
    FOREIGN KEY (`OrtId`)
    REFERENCES `mydb`.`Ort` (`OrtId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Bestellungen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Bestellungen` (
  `BestellungsId` INT NOT NULL AUTO_INCREMENT,
  `KundenId` INT NOT NULL,
  `Anzahl` INT NULL,
  `Anfangsdatum` DATE NULL,
  `Enddatum` DATE NULL,
  PRIMARY KEY (`BestellungsId`),
  INDEX `fk_Bestellungen_Kunden1_idx` (`KundenId` ASC),
  CONSTRAINT `fk_Bestellungen_Kunden1`
    FOREIGN KEY (`KundenId`)
    REFERENCES `mydb`.`Kunden` (`KundenId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Verleihung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Verleihung` (
  `BootId` INT NOT NULL,
  `BestellungsId` INT NOT NULL,
  `Status` ENUM('Verfügbar', 'Verliehen') NULL,
  INDEX `fk_Verleihung_Boote1_idx` (`BootId` ASC),
  INDEX `fk_Verleihung_Bestellungen1_idx` (`BestellungsId` ASC),
  CONSTRAINT `fk_Verleihung_Boote1`
    FOREIGN KEY (`BootId`)
    REFERENCES `mydb`.`Boote` (`BootId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Verleihung_Bestellungen1`
    FOREIGN KEY (`BestellungsId`)
    REFERENCES `mydb`.`Bestellungen` (`BestellungsId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
