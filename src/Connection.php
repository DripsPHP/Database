<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 02.04.2016 - 13:37.
 * Copyright Prowect.
 */

namespace Drips\Database;

use Drips\Logger\Handler;
use Drips\Logger\Logger;
use Drips\Utils\IDataProvider;
use Medoo;
use Monolog\Handler\StreamHandler;
use Propel\Runtime\Propel;

/**
 * Class DB.
 *
 * Datenbank-Komponente basierend auf dem Medoo-Framework.
 * Baut die Datenbankverbindung nur auf, wenn es auch wirklich notwendig ist.
 */
class Connection extends Medoo implements IDataProvider
{
    /**
     * Beinhaltet sämtliche Verbindungsinformationen wie z.B.: Datenbank-Host, -Benutzer, usw.
     *
     * @var array
     */
    private $options;

    /**
     * Wurde bereits eine Verbindung zur Datenbank hergestellt?
     *
     * @var bool
     */
    private $connected = false;

    /**
     * Beinhaltet ein Logger-Objekt zum Loggen der Datenbankaktivitäten.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Logfile, das beschrieben werden soll.
     *
     * @var string
     */
    private $logfile = 'database.log';

    /**
     * Legt fest ob die bestehende Datenbankverbindung von Propel (ORM) verwendet werden soll, wenn vorhanden oder nicht.
     *
     * @var bool
     */
    private $usePropel = false;

    /**
     * Erzeugt eine neues Objekt - stellt allerdings noch keine Verbindung zur Datenbank her!
     *
     * @param array $options
     * @param bool $usePropel
     */
    public function __construct(array $options, $usePropel = false)
    {
        $this->options = $options;
        $this->logger = new Logger('database');
        if (defined('DRIPS_LOGS')) {
            $this->logfile = DRIPS_LOGS . '/' . $this->logfile;
        }
        if (defined('DRIPS_DEBUG')) {
            if (DRIPS_DEBUG) {
                $this->logger->pushHandler(new Handler);
            } else {
                $this->logger->pushHandler(new StreamHandler($this->logfile, Logger::WARNING));
            }
        }
        $this->usePropel = $usePropel;
    }

    /**
     * Führt eine Datenbank-Abfrage durch und loggt diese.
     *
     * @param $query
     *
     * @return bool|\PDOStatement
     */
    public function query($query)
    {
        $result = parent::query($query);
        $this->logger->addInfo($query);
        if ($result === false) {
            $errors = $this->error();
            $this->logger->addCritical($errors[2]);
        }
        return $result;
    }

    /**
     * Führt eine Datenbank-Abfrage durch und loggt diese.
     *
     * @param $query
     *
     * @return bool|\PDOStatement
     */
    public function exec($query)
    {
        $result = parent::exec($query);
        $this->logger->addInfo($query);
        if ($result === false) {
            $errors = $this->error();
            $this->logger->addCritical($errors[2]);
        }
        return $result;
    }

    /**
     * Erzeugt eine PDO-Instanz, wenn noch nicht vorhanden => baut die Verbindung zur Datenbank auf.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($name == "pdo") {
            // Bestehende Propel-Verbindung benutzen, wenn vorhanden, andernfalls neue Verbindung aufbauen
            if (class_exists('\Propel\Runtime\Propel') && $this->usePropel) {
                $this->pdo = Propel::getServiceContainer()->getConnection('default')->getWrappedConnection();
                if (@$this->options['database_type'] == 'mysql') {
                    $this->pdo->exec('SET SQL_MODE=ANSI_QUOTES');
                } elseif (@$this->options['database_type'] == 'mssql') {
                    $this->pdo->exec('SET QUOTED_IDENTIFIER ON');
                }
            } else {
                parent::__construct($this->options);
            }
            if (isset($this->pdo)) {
                $this->connected = true;
            }
            return $this->pdo;
        }
        return null;
    }

    /**
     * Liefert zurück, ob bereits eine Datenbankverbindung besteht oder nicht.
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->connected;
    }
}
