<?php
error_reporting(E_ALL ^ E_NOTICE);

class Config {
    const projectName = 'База E-mail адресов';
    const siteUrl = 'http://127.0.0.1/send-mail';
    const db_host = '127.0.0.1';
    const db_user = 'root';
    const db_pass = '';
    const db_name = 'emails';
    const dir1 = 'folder_input';
    const dir2 = 'folder_output';
}

if (!is_dir(Config::dir1) || !is_dir(Config::dir2)) {
    @mkdir(Config::dir1);
    @mkdir(Config::dir2);
}