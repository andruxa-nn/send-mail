<?php
class DataBase extends PDO {
    public function __construct() {
        try {
            parent::__construct("mysql:host=".Config::db_host.";"."dbname=".Config::db_name, Config::db_user, Config::db_pass);
        } catch (PDOException $exeption) {
            //$this->dbh->query("CREATE DATABASE IF NOT EXISTS `emails` DEFAULT CHARACTER SET `utf8`");
            //CREATE TABLE `mail` (`id` INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT, `item` VARCHAR(255) NOT NULL UNIQUE KEY);
            //echo $exeption->getMessage();
        }
    }
}