# Database

[![Build Status](https://travis-ci.org/Prowect/Database.svg)](https://travis-ci.org/Prowect/Database)
[![Code Climate](https://codeclimate.com/github/Prowect/Database/badges/gpa.svg)](https://codeclimate.com/github/Prowect/Database)
[![Test Coverage](https://codeclimate.com/github/Prowect/Database/badges/coverage.svg)](https://codeclimate.com/github/Prowect/Database/coverage)
[![Latest Release](https://img.shields.io/packagist/v/drips/Database.svg)](https://packagist.org/packages/drips/database)

## Beschreibung

Datenbank-Komponente basierend auf dem [Medoo-Framework](http://medoo.in). Baut die Datenbankverbindung nur auf, wenn es auch wirklich notwendig ist.

## Konfiguration

 - `database_type` - legt den Datenbank-Typ fest (z.B.: *mysql*, *sqlite*, ...)
 - `database_host` - legt den Datenbank-Host fest (bei SQLite: Dateipfad)
 - `database_name` - legt den Datenbank-Namen fest (bei SQLite nicht erforderlich)
 - `database_username` - legt den Datenbank-Benutzer fest (bei SQLite nicht erforderlich)
 - `database_password` - legt das Datenbank-Passwort fest (bei SQLite nicht erforderlich)
 - `database_port` - legt den Datenbank-Port fest (bei SQLite nicht erforderlich)
 - `database_charset` - legt das Datenbank-Charset fest (Standard: *utf-8*)
 
> In Drips wird die Datenbank-Verbindung automatisch aufgebaut, wenn die Informationen in der Konfiguration festgelegt sind.
 
## Verwendung

```php
<?php 

use Drips\Database\DB;

$db = DB::getConnection();

$result = $db->select('users', '*');
```

### Mehrere Datenbankverbindungen

```php
<?php 

use Drips\Database\DB;
use Drips\Database\Connection;

$connection2 = new Connection([
    // Datenbank-Verbindungsinformationen (siehe Medoo-Dokumentation)
]);

DB::setConnection('database2', $connection2);


$db1 = DB::getConnection();
$result = $db->select('users', '*');

$db2 = DB::getConnection('database2');
$result2 = $db2->select('news', '*');

```

Weitere Informationen findest du auf [http://medoo.in/doc](http://medoo.in/doc)
