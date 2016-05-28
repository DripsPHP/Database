<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 02.04.2016 - 13:37.
 * Copyright Prowect.
 */

namespace Drips\Database;

use Medoo;
use Drips\Utils\IDataProvider;
use Drips\Logger\Logger;
use Drips\Logger\Handler;
use Monolog\Handler\StreamHandler;

/**
 * Class DB.
 *
 * Datenbank-Komponente basierend auf dem Medoo-Framework.
 * Baut die Datenbankverbindung nur auf, wenn es auch wirklich notwendig ist
 */
class DB extends Medoo implements IDataProvider
{
    private $options;
    private $connected = false;
    private $logger;
    private $logfile = 'database.log';

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->logger = new Logger('database');
        if(defined('DRIPS_LOGS')){
            $this->logfile = DRIPS_LOGS.'/'.$this->logfile;
        }
        $this->logger->pushHandler(new StreamHandler($this->logfile));
        $this->logger->pushHandler(new Handler);
    }

    public function query($query)
    {
        $result = parent::query($query);
        $this->logger->addDebug($query);
        if($result === false){
            $errors = $this->error();
            $this->logger->addCritical($errors[2]);
        }
        return $result;
    }

    public function exec($query)
    {
        $result = parent::exec($query);
        $this->logger->addDebug($query);
        if($result === false){
            $errors = $this->error();
            $this->logger->addCritical($errors[2]);
        }
        return $result;
    }

    public function __get($name)
    {
        if($name == "pdo"){
            parent::__construct($this->options);
            if(isset($this->pdo)){
                $this->connected = true;
            }
            return $this->pdo;
        }
    }


    public function isConnected()
    {
        return $this->connected;
    }
}
