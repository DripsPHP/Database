<?php
/**
 * Created by PhpStorm.
 * User: raffael
 * Date: 24.07.16
 * Time: 11:40
 */

namespace Drips\Database;

/**
 * Class DB
 *
 * Klasse zur Speicherung mehrerer Datenbankverbindungen, sodass diese von einer zentralen Stelle eingeholt werden können.
 *
 * @package Drips\Database
 */
abstract class DB
{
    /**
     * Beinhaltet die einzelnen Datenbank-Verbindungen (Connection-Objekte).
     *
     * @var array
     */
    protected static $connections = array();

    /**
     * Registriert eine neue Datenbank-Verbindung unter gegebenen Namen.
     *
     * @param $name
     * @param Connection $connection
     */
    public static function setConnection($name, Connection $connection)
    {
        static::$connections[$name] = $connection;
    }

    /**
     * Prüft, ob eine Verbingung unter gegebenem Namen besteht.
     *
     * @param $name
     *
     * @return bool
     */
    public static function hasConnection($name)
    {
        return isset(static::$connections[$name]);
    }

    /**
     * Liefert zurück, ob bereits Verbindungen registriert wurden oder nicht.
     *
     * @return bool
     */
    public static function hasConnections()
    {
        return count(static::$connections) > 0;
    }

    /**
     * Liefert alle registrierten Verbindungen als Array zurück.
     *
     * @return array
     */
    public static function getConnections()
    {
        return static::$connections;
    }

    /**
     * Liefert eine bestimmte Verbindungen oder die Erste (die registriert wurde) zurück, sofern diese existiert.
     *
     * @param null $name
     *
     * @return mixed|null
     */
    public static function getConnection($name = null)
    {
        if (static::hasConnections()) {
            if ($name === null) {
                return static::$connections[key(static::$connections)];
            } elseif(static::hasConnection($name)){
                return static::$connections[$name];
            }
        }
        return null;
    }
}