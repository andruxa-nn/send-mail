<?php
error_reporting(E_ALL ^ E_NOTICE);

class Config {
    public $projectName = 'База E-mail адресов';
    public $siteUrl = 'http://127.0.0.1/sendMail';
    public $db_host = '127.0.0.1';
    public $db_user = 'root';
    public $db_pass = '';
    public $db_name = 'emails';
    public $dir1 = 'folder_input';
    public $dir2 = 'folder_output';
    public $Data = array();
    public $dbh = '';
}

?>