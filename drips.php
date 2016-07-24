<?php

use Drips\App;
use Drips\Config\Config;
use Drips\Database\DB;
use Drips\Database\Connection;

if (class_exists('Drips\App')) {
    App::on('create', function (App $app) {
        $type = Config::get('database_type', 'mysql');
        $host = Config::get('database_host', 'localhost');
        $database = Config::get('database_name', 'drips');
        $username = Config::get('database_username', 'root');
        $password = Config::get('database_password', 'root');
        $port = Config::get('database_port', 3306);
        $charset = Config::get('database_charset', 'utf-8');

        $config = array(
            'database_type' => $type
        );

        if ($type == 'sqlite') {
            $config['database_file'] = $host;
        } else {
            $config['server'] = $host;
            $config['database_name'] = $database;
            $config['username'] = $username;
            $config['password'] = $password;
            $config['port'] = $port;
            $config['charset'] = $charset;
        }

        DB::setConnection('default', new Connection($config, true));
    });
}
