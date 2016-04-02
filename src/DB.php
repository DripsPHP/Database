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

    public function __construct(array $options)
    {
        $this->options = $options;
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
