-- Clean LMS schema without explicit KEY/INDEX lines (server defaults used)
-- Mentor used instead of AcademicOfficer. Import after backup.

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `online_lms`;
CREATE DATABASE `online_lms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `online_lms`;

-- Reference tables
CREATE TABLE `city` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `user_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `gender` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Courses
CREATE TABLE `course` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `course` (`id`,`name`) VALUES
  (1, 'JavaScript'),
  (2, 'Java'),
  (3, 'Python');
ALTER TABLE `course` AUTO_INCREMENT = 4;

-- Groups (backticked)
CREATE TABLE `group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `group` (`id`,`name`) VALUES
  (1,'JS1'),
  (2,'JS2'),
  (3,'JAVA1'),
  (4,'JAVA2');
ALTER TABLE `group` AUTO_INCREMENT = 5;

-- Group <-> Course mapping
CREATE TABLE `group_has_course` (
  `group_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  PRIMARY KEY (`group_id`,`course_id`),
  CONSTRAINT `fk_ghc_group` FOREIGN KEY (`group_id`) REFERENCES `group`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ghc_course` FOREIGN KEY (`course_id`) REFERENCES `course`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `group_has_course` (`group_id`,`course_id`) VALUES
  (1,1),(2,1),
  (3,2),(4,2),
  (1,3);

-- Users
CREATE TABLE `user` (
  `username` VARCHAR(100) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `address_1` VARCHAR(255) DEFAULT NULL,
  `address_2` VARCHAR(255) DEFAULT NULL,
  `city_id` INT DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `user_type_id` INT NOT NULL,
  `status_id` INT NOT NULL,
  `gender_id` INT NOT NULL,
  PRIMARY KEY (`username`),
  CONSTRAINT `fk_user_city` FOREIGN KEY (`city_id`) REFERENCES `city`(`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_user_type` FOREIGN KEY (`user_type_id`) REFERENCES `user_type`(`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_user_status` FOREIGN KEY (`status_id`) REFERENCES `status`(`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_user_gender` FOREIGN KEY (`gender_id`) REFERENCES `gender`(`id`) ON UPDATE CASCADE
);

-- Seed reference data
INSERT INTO `city` (`id`,`name`) VALUES (1,'Bishkek'),(2,'Osh'),(3,'Batken');
INSERT INTO `user_type` (`id`,`name`) VALUES (1,'Admin'),(2,'Teacher'),(3,'Student'),(4,'Mentor');
INSERT INTO `status` (`id`,`name`) VALUES (1,'Verified'),(2,'Pending'),(3,'Disabled');
INSERT INTO `gender` (`id`,`name`) VALUES (1,'Male'),(2,'Female');

-- Seed users (include Mentor)
INSERT INTO `user` (`username`,`first_name`,`last_name`,`mobile`,`email`,`address_1`,`address_2`,`city_id`,`password`,`user_type_id`,`status_id`,`gender_id`) VALUES
  ('admin','Aibek','Bekmamatov','0771234567','admin@lms.kg','Ala-Too','Bishkek',1,'admin',1,1,1),
  ('teacher1','Aida','Jusupova','0777111222','aida@lms.kg','Lenin','Bishkek',1,'pwdhash',2,1,2),
  ('student1','Ermek','Saidov','0777222333','ermek@lms.kg','Naryn','Osh',2,'pwdhash',3,1,1),
  ('student2','Aijan','Tursunova','0777333444','aijan@lms.kg','Archa','Batken',3,'pwdhash',3,1,2),
  ('mentor1','Nurbek','Karaev','0777444555','mentor1@lms.kg','Ala-Too','Bishkek',1,'pwdhash',4,1,1);

-- User <-> Group enrollment
CREATE TABLE `user_has_group` (
  `user_username` VARCHAR(100) NOT NULL,
  `group_id` INT NOT NULL,
  `expire_date` DATE DEFAULT NULL,
  PRIMARY KEY (`user_username`,`group_id`),
  CONSTRAINT `fk_uhg_user` FOREIGN KEY (`user_username`) REFERENCES `user`(`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uhg_group` FOREIGN KEY (`group_id`) REFERENCES `group`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `user_has_group` (`user_username`,`group_id`,`expire_date`) VALUES
  ('student1',1,'2024-12-31'),
  ('student2',2,'2024-12-31'),
  ('teacher1',1,'2025-12-31'),
  ('mentor1',1,'2025-12-31');

-- Completion statuses
CREATE TABLE `complete_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `complete_status` (`id`,`name`) VALUES (1,'Not Started'),(2,'In Progress'),(3,'Completed');

-- User progress (ref composite group_has_course)
CREATE TABLE `user_has_group_has_course` (
  `user_username` VARCHAR(100) NOT NULL,
  `group_has_course_group_id` INT NOT NULL,
  `group_has_course_course_id` INT NOT NULL,
  `complete_status_id` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`user_username`,`group_has_course_group_id`,`group_has_course_course_id`),
  CONSTRAINT `fk_uhghc_user` FOREIGN KEY (`user_username`) REFERENCES `user`(`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uhghc_groupcourse` FOREIGN KEY (`group_has_course_group_id`,`group_has_course_course_id`) REFERENCES `group_has_course`(`group_id`,`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uhghc_status` FOREIGN KEY (`complete_status_id`) REFERENCES `complete_status`(`id`) ON UPDATE CASCADE
);

INSERT INTO `user_has_group_has_course` (`user_username`,`group_has_course_group_id`,`group_has_course_course_id`,`complete_status_id`) VALUES
  ('student1',1,1,2),
  ('student2',2,1,2),
  ('student1',1,3,1);

-- Assignment table
CREATE TABLE `assignment` (
  `id` VARCHAR(45) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `assignment_location` VARCHAR(255) DEFAULT NULL,
  `group_has_course_group_id` INT NOT NULL,
  `group_has_course_course_id` INT NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `user_username` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_assignment_groupcourse` FOREIGN KEY (`group_has_course_group_id`,`group_has_course_course_id`) REFERENCES `group_has_course`(`group_id`,`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_assignment_user` FOREIGN KEY (`user_username`) REFERENCES `user`(`username`) ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT INTO `assignment` (`id`,`name`,`assignment_location`,`group_has_course_group_id`,`group_has_course_course_id`,`start_date`,`end_date`,`user_username`) VALUES
  ('2025-1-1-AS-1','JS Homework 2025','Assignments/2025-1-1-AS-1.pdf',1,1,'2025-12-01','2025-12-15','teacher1'),
  ('2025-2-1-AS-1','Java Homework 2025','Assignments/2025-2-1-AS-1.pdf',3,2,'2025-12-01','2025-12-15','teacher1');

-- Notes
CREATE TABLE `notes` (
  `id` VARCHAR(45) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `note_location` VARCHAR(255) DEFAULT NULL,
  `group_has_course_group_id` INT NOT NULL,
  `group_has_course_course_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_notes_groupcourse` FOREIGN KEY (`group_has_course_group_id`,`group_has_course_course_id`) REFERENCES `group_has_course`(`group_id`,`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `notes` (`id`,`name`,`note_location`,`group_has_course_group_id`,`group_has_course_course_id`) VALUES
  ('2024-1-NOTE-1','JS Lesson 1','Notes/2024-1-NOTE-1.pdf',1,1),
  ('2024-2-NOTE-1','Java Lesson 1','Notes/2024-2-NOTE-1.pdf',3,2);

-- Payment
CREATE TABLE `payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_has_group_user_username` VARCHAR(100) NOT NULL,
  `user_has_group_group_id` INT NOT NULL,
  `date_time` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_payment_uhg` FOREIGN KEY (`user_has_group_user_username`,`user_has_group_group_id`) REFERENCES `user_has_group`(`user_username`,`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `payment` (`user_has_group_user_username`,`user_has_group_group_id`,`date_time`) VALUES
  ('student1',1,'2024-08-20 10:00:00'),
  ('student2',2,'2024-08-21 11:44:21');

-- Mark status
CREATE TABLE `mark_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
);
INSERT INTO `mark_status` (`id`,`name`) VALUES (1,'Not Submitted'),(2,'Submitted'),(3,'Graded');

-- Generated codes
CREATE TABLE `generated_code` (
  `code` VARCHAR(45) NOT NULL,
  `user_username` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`code`),
  CONSTRAINT `fk_generated_user` FOREIGN KEY (`user_username`) REFERENCES `user`(`username`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `generated_code` (`code`,`user_username`) VALUES ('J3IMSY','student2');

-- add Released status (id=4) if missing
INSERT INTO `mark_status` (`id`,`name`) VALUES (4,'Released') 
  ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- submitted assignments table
DROP TABLE IF EXISTS `user_has_release_assignment`;
CREATE TABLE `user_has_release_assignment` (
  `id` INT NOT NULL ,
  `user_username` VARCHAR(100) NOT NULL,
  `assignment_id` VARCHAR(45) NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL,
  `submitted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `marks` INT DEFAULT NULL,
  `mark_status_id` INT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_assignment` (`user_username`,`assignment_id`),
  CONSTRAINT `fk_uha_user` FOREIGN KEY (`user_username`) REFERENCES `user`(`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uha_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `assignment`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_uha_mark_status` FOREIGN KEY (`mark_status_id`) REFERENCES `mark_status`(`id`) ON UPDATE CASCADE ON DELETE SET NULL
);

INSERT INTO `user_has_release_assignment` (`user_username`,`assignment_id`,`file_path`,`submitted_at`,`marks`,`mark_status_id`) VALUES
  ('student1','2025-1-1-AS-1','submissions/student1_2025-1-1-AS-1.pdf','2025-12-05 12:00:00',85,4),
  ('student2','2025-2-1-AS-1','submissions/student2_2025-2-1-AS-1.pdf','2025-12-06 13:00:00',72,3)
ON DUPLICATE KEY UPDATE
  `file_path`=VALUES(`file_path`),
  `submitted_at`=VALUES(`submitted_at`),
  `marks`=VALUES(`marks`),
  `mark_status_id`=VALUES(`mark_status_id`);
SET FOREIGN_KEY_CHECKS=1;
