CREATE SCHEMA `leave`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci;

CREATE TABLE `leave`.`users` (
  `id`   INT         NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `leave`.`leaves` (
  `id`              INT                                      NOT NULL AUTO_INCREMENT,
  `user_id`         INT                                      NULL,
  `type`            ENUM ('WFH', 'SICK', 'VACATION')         NULL     DEFAULT 'VACATION',
  `start_timestamp` INT                                      NULL     DEFAULT 0,
  `end_timestamp`   INT                                      NULL     DEFAULT 0,
  `status`          ENUM ('PENDING', 'APPROVED', 'REJECTED') NULL     DEFAULT 'PENDING',
  PRIMARY KEY (`id`)
);

ALTER TABLE `leave`.`leaves`
  ADD CONSTRAINT `id`
FOREIGN KEY (`user_id`)
REFERENCES `leave`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
,
  ADD INDEX `id_idx` (`user_id` ASC);
