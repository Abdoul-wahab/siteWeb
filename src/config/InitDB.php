<?php


class InitDB	
{
    private $sql= "
    -- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: mysql.info.unicaen.fr    Database: niveau_dev
-- ------------------------------------------------------
-- Server version	5.5.47-0+deb7u1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS account;


create table account
(
	id int auto_increment,
	login VARCHAR(55) not null,
	password VARCHAR(255) not null,
	status int(1) default 0 not null,
	constraint account_pk
		primary key (id)
);

create unique index account_login_uindex
	on account (login);


INSERT INTO `account`(`id`, `login`, `password`, `status`) VALUES (NULL,'toto','\$2y$10\$vecze/V//nVxqjpk2VqMOuk46PoPs/ol.xdB4.0OTtj1Z.ee0W4a.',1);
INSERT INTO `account`(`id`, `login`, `password`) VALUES (NULL,'testeur','\$2y\$10\$Lj0O5fP9xARQvYuo5/dd7.PLAVm9mPo5zwPEohMogU3XwIGN6ZY2C');


create table books
(
	id int auto_increment,
	name varchar(255) null,
	image varchar(255) null,
	account int null,
	description VARCHAR(767) null,
	constraint books_pk
		primary key (id),
	constraint books_account_id_fk
		foreign key (account) references account (id)
);


INSERT INTO `books`(`id`, `name`, `image`, `description`, `account`) VALUES (NULL,'livre1','image.jpg','description du livre1',1);
INSERT INTO `books`(`id`, `name`, `image`, `description`, `account`) VALUES (NULL,'livre2','image.jpg','description du livre2',2);







/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-16 18:30:36
    ";

    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

}
