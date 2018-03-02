SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS projectDB;
USE projectDB;
DROP TABLE IF EXISTS enrowed;
DROP TABLE IF EXISTS campaigns;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS electives;


CREATE TABLE users (
    `id` int(255) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `status`  enum('approved','pending') NOT NULL DEFAULT 'pending',
    `role`  enum('administrator','student') NOT NULL DEFAULT 'student',
    `code` varchar(255) UNIQUE,
    `stamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Q1
INSERT INTO `users` (username, password, email, status, role) VALUES ('admin','$2y$10$rPg45UAarMJgFtRQwsPaROm/Nafh0LxtXaq9z1avYLL9.AmWWkyJS','gogo@uni-sofia.bg','approved','administrator'); 
-- W2
INSERT INTO `users` (username, password, email, status, role) VALUES ('student','$2y$10$T9oat4wPJm4Zo8s7oObWsuQH5mJ7d.UCNEmQz0BT8ZZX8EtHHzYWG','danieldelchev95@abv.bg','approved','student'); 


CREATE TABLE campaigns (
    `id` int(255) NOT NULL AUTO_INCREMENT,
    `adminID` int(255) NOT NULL,
    `start` DATE NOT NULL,
    `finish` DATE NOT NULL,
    `stamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,    
    PRIMARY KEY (`id`),
    FOREIGN KEY (adminID) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2008-11-11','2009-11-11'); 
INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2011-11-11','2012-11-11'); 
INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2009-11-11','2010-11-11'); 
INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2015-11-11','2016-11-11'); 
INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2013-11-11','2014-11-11'); 
INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2018-01-01','2018-01-31'); 
INSERT INTO `campaigns` (adminID, start, finish) VALUES (1,'2018-09-30','2018-10-28'); 


CREATE TABLE electives (
    `id` int(255) NOT NULL AUTO_INCREMENT,
    `electiveName` varchar(255) NOT NULL UNIQUE,    
    `lecturerName` varchar(255) NOT NULL,    
    `type` enum('ЯКН','ОКН','Математика') NOT NULL,    
    `credits` int(255) NOT NULL,
    `maxSlots` int(255) NOT NULL,
    `currentSlotsTaken` int(255) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Алгебра I','Каспарян','Математика',6,28,15); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Алгебра II','Каспарян','Математика',7,40,40); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Увод в теория на графите','Ненов','Математика',7,20,13); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Анализ на Фурие','Н. Рибарска','Математика',9,40,40); 


INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Д.А.А.','Марков','ОКН',7,70,70); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Н.Л.И.И.И.','Вакарелов','ОКН',7,30,13); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Моблини приложения','Георгиев','ОКН',7,70,69); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Операционни системи','Филипова','ОКН',7,30,12); 

INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('C++ advanced','Д.Трендафилов','ЯКН',6,60,50); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Python','Киро питоня и Куната','ЯКН',5,50,14); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Java','Сападжийте','ЯКН',6,60,60); 
INSERT INTO `electives` (electiveName, lecturerName, type,credits,maxSlots,currentSlotsTaken) VALUES ('Функционално програмиране','Т.Трифонов','ЯКН',5,50,50); 

CREATE TABLE enrowed (
    `id` int(255) NOT NULL AUTO_INCREMENT,
    `electiveID` int(255) NOT NULL,
    `userID` int(255) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (electiveID) REFERENCES electives(id),
    FOREIGN KEY (userID) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `enrowed` (electiveID, userID) VALUES ('1','2'); 
INSERT INTO `enrowed` (electiveID, userID) VALUES ('2','2'); 
INSERT INTO `enrowed` (electiveID, userID) VALUES ('3','2'); 
INSERT INTO `enrowed` (electiveID, userID) VALUES ('4','2'); 
INSERT INTO `enrowed` (electiveID, userID) VALUES ('5','2'); 
INSERT INTO `enrowed` (electiveID, userID) VALUES ('6','2'); 