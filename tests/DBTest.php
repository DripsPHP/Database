<?php

namespace tests;

use PHPUnit_Framework_TestCase;
use Drips\Database\DB;

class DBTest extends PHPUnit_Framework_TestCase
{
    public function testDB()
    {
        $db = new DB(array(
            'database_type' => 'sqlite',
        	'database_file' => __DIR__."/db.sqlite"
        ));
        $this->assertFalse($db->isConnected());
        $result = $db->select("user", "*");
        $this->assertTrue($db->isConnected());
        $this->assertEquals($db->does_not_exist, null);
    }
}
