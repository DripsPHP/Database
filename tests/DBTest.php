<?php
/**
 * Created by PhpStorm.
 * User: raffael
 * Date: 24.07.16
 * Time: 15:29
 */

namespace tests;

use Drips\Database\Connection;
use Drips\Database\DB;
use PHPUnit_Framework_Testcase;

class DBTest extends PHPUnit_Framework_Testcase
{
    public function testDB()
    {
        $connection = new Connection(array(
            'database_type' => 'sqlite',
            'database_file' => __DIR__ . "/db.sqlite"
        ));

        $this->assertFalse(DB::hasConnections());
        $this->assertFalse(DB::hasConnection('test'));
        DB::setConnection('test', $connection);
        $this->assertEquals(DB::getConnection(), $connection);
        $this->assertTrue(DB::hasConnections());
        $this->assertTrue(DB::hasConnection('test'));
    }
}