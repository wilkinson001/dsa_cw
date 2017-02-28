/*###########################################################
#############################################################

						DSA SQL script

#############################################################
################		User Setup			   ##############
###########################################################*/



-- On UWE mysql server, you need to connect using: mysql <username> --local-infile=1 -h mysql5 -u<username> -p

CREATE SCHEMA IF NOT EXISTS `dsa` DEFAULT CHARACTER SET utf8 ;
USE `dsa` ;
-- -----------------------------------------------------
-- Table `dsa`.`location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`location` ;

CREATE TABLE IF NOT EXISTS `dsa`.`location` (
  `location_id` INT NOT NULL,
  `woeid` INT NULL,
  `name` VARCHAR(45) NOT NULL,
  `coordinates` VARCHAR(200) NULL,
  PRIMARY KEY (`location_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsa`.`region`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`region` ;

CREATE TABLE IF NOT EXISTS `dsa`.`region` (
  `region_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(200) NOT NULL,
  `country` VARCHAR(45) NOT NULL,
  `climate` VARCHAR(45) NOT NULL,
  `population` INT NOT NULL,
  `planted_area` VARCHAR(45) NOT NULL,
  `rainfall` INT NOT NULL,
  `volume_produced` INT NOT NULL,
  `export_value` INT NOT NULL,
  `domestic_sales` INT NOT NULL,
  `location_id` INT NOT NULL,
  PRIMARY KEY (`region_id`),
  CONSTRAINT `fk_Region_Location1`
    FOREIGN KEY (`location_id`)
    REFERENCES `dsa`.`location` (`location_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsa`.`vinyard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`vinyard` ;

CREATE TABLE IF NOT EXISTS `dsa`.`vinyard` (
  `vinyard` INT NOT NULL,
  `region_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `location_id` INT NOT NULL,
  PRIMARY KEY (`vinyard`),
  CONSTRAINT `fk_vinyard_region1`
    FOREIGN KEY (`region_id`)
    REFERENCES `dsa`.`region` (`region_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vinyard_location1`
    FOREIGN KEY (`location_id`)
    REFERENCES `dsa`.`location` (`location_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsa`.`wine`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`wine` ;

CREATE TABLE IF NOT EXISTS `dsa`.`wine` (
  `wine_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `descritption` VARCHAR(45) NOT NULL,
  `alcohol` INT NOT NULL,
  `dryness_sweetness` VARCHAR(45) NOT NULL,
  `producer` VARCHAR(45) NOT NULL,
  `bottle_size` VARCHAR(45) NOT NULL,
  `vintage` CHAR(4) NOT NULL,
  `vinyard` INT NOT NULL,
  PRIMARY KEY (`wine_id`),
  CONSTRAINT `fk_wine_vinyard1`
    FOREIGN KEY (`vinyard`)
    REFERENCES `dsa`.`vinyard` (`vinyard`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsa`.`type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`type` ;

CREATE TABLE IF NOT EXISTS `dsa`.`type` (
  `red` INT NOT NULL,
  `white` INT NOT NULL,
  `rose` INT NOT NULL,
  `champagne` INT NOT NULL,
  `sparkling` INT NOT NULL,
  `wine_id` INT NOT NULL,
  PRIMARY KEY (`wine_id`),
  CONSTRAINT `fk_type_wine1`
    FOREIGN KEY (`wine_id`)
    REFERENCES `dsa`.`wine` (`wine_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsa`.`languages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`languages` ;

CREATE TABLE IF NOT EXISTS `dsa`.`languages` (
  `language` VARCHAR(45) NOT NULL,
  `region_id` INT NOT NULL,
  PRIMARY KEY (`language`, `region_id`),
  CONSTRAINT `fk_languages_region1`
    FOREIGN KEY (`region_id`)
    REFERENCES `dsa`.`region` (`region_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsa`.`grapes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dsa`.`grapes` ;

CREATE TABLE IF NOT EXISTS `dsa`.`grapes` (
  `grape` VARCHAR(45) NOT NULL,
  `wine_id` INT NOT NULL,
  PRIMARY KEY (`grape`, `wine_id`),
  CONSTRAINT `fk_grapes_wine1`
    FOREIGN KEY (`wine_id`)
    REFERENCES `dsa`.`wine` (`wine_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



LOAD DATA LOCAL INFILE 'wines.tab' into table wine lines terminated by '\r\n';
LOAD DATA LOCAL INFILE 'grapes.tab' into table grapes lines terminated by '\r\n';
LOAD DATA LOCAL INFILE 'languages.tab' into table languages lines terminated by '\r\n';
LOAD DATA LOCAL INFILE 'location.tab' into table location lines terminated by '\r\n';
LOAD DATA LOCAL INFILE 'regions.tab' into table regions lines terminated by '\r\n';
LOAD DATA LOCAL INFILE 'type.tab' into table type lines terminated by '\r\n';
LOAD DATA LOCAL INFILE 'vinyards.tab' into table vinyard lines terminated by '\r\n';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

update wine set name = replace(name,"'","");

select
	*
from wines;
select
	*
from grapes;
select
	*
from languages;
select
	*
from location;
select
	*
from regions;
select
	*
from type;
select
	*
from vinyards;
