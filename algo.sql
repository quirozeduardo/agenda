CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(256) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `last_name` VARCHAR(256) NULL,
  `email` VARCHAR(256) NOT NULL,
  `password` VARCHAR(512) NOT NULL,
  `remember_token` VARCHAR(256) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email`),
  UNIQUE INDEX `username_UNIQUE` (`username`));


-- -----------------------------------------------------
-- Table `status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `status` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(512) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `impact`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `impact` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(512) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `priority`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `priority` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(512) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(512) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `task`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(1024) NULL,
  `status_id` INT UNSIGNED NOT NULL,
  `assigned_user_id` INT UNSIGNED NULL,
  `assigned_by_user_id` INT UNSIGNED NULL,
  `impact_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `priority_id` INT UNSIGNED NOT NULL,
  `comments` VARCHAR(1024) NULL,
  `time_to_solve` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_task_status_id_status_idx` (`status_id`),
  INDEX `fk_task_asigned_user_id_users_idx` (`assigned_user_id`),
  INDEX `fk_task_impact_id_impact_idx` (`impact_id`),
  INDEX `fk_task_priority_id_priority_idx` (`priority_id`),
  INDEX `fk_task_category_id_category_idx` (`category_id`),
  INDEX `fk_task_assigned_by_user_id_users_idx` (`assigned_by_user_id`),
  CONSTRAINT `fk_task_status_id_status`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_asigned_user_id_users`
    FOREIGN KEY (`assigned_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_impact_id_impact`
    FOREIGN KEY (`impact_id`)
    REFERENCES `impact` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_priority_id_priority`
    FOREIGN KEY (`priority_id`)
    REFERENCES `priority` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_category_id_category`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_assigned_by_user_id_users`
    FOREIGN KEY (`assigned_by_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `task_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `description` VARCHAR(1024) NULL,
  `status_id` INT UNSIGNED NOT NULL,
  `user_asigned_id` INT UNSIGNED NULL,
  `assigned_by_user_id` INT UNSIGNED NULL,
  `impact_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `priority_id` INT UNSIGNED NOT NULL,
  `comments` VARCHAR(1024) NULL,
  `time_to_solve` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_task_log_status_id_status_idx` (`status_id`),
  INDEX `fk_task_log_asigned_user_id_users_idx` (`user_asigned_id`),
  INDEX `fk_task_log_impact_id_impact_idx` (`impact_id`),
  INDEX `fk_task_log_priority_id_priority_idx` (`priority_id`),
  INDEX `fk_task_log_category_id_category_idx` (`category_id`),
  INDEX `fk_task_log_assigned_by_user_id_users_idx` (`assigned_by_user_id`),
  CONSTRAINT `fk_task_log_status_id_status`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_asigned_user_id_users`
    FOREIGN KEY (`user_asigned_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_impact_id_impact`
    FOREIGN KEY (`impact_id`)
    REFERENCES `impact` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_priority_id_priority`
    FOREIGN KEY (`priority_id`)
    REFERENCES `priority` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_category_id_category`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_assigned_by_user_id_users`
    FOREIGN KEY (`assigned_by_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_task_id_task`
    FOREIGN KEY (`id`)
    REFERENCES `task` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);

CREATE TABLE IF NOT EXISTS `system_configuration` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(256) NOT NULL,
    `value` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`));