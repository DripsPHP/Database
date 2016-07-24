<?php

namespace tests;

use Drips\Database\Connection;
use PHPUnit_Framework_TestCase;

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    public function testConnection()
    {
        date_default_timezone_set('Europe/Vienna');

        $connection = new Connection(array(
            'database_type' => 'sqlite',
            'database_file' => __DIR__ . "/db.sqlite"
        ));
        $this->assertFalse($connection->isConnected());
        $this->assertTrue(is_array($connection->select("user", "*")));
        $this->assertTrue($connection->isConnected());
        $this->assertEquals($connection->does_not_exist, null);
    }
}
