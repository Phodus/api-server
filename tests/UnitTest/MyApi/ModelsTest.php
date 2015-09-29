<?php

/**
 * Class ModelsTest
 *
 * Process for test:
 *
 * # cd /var/www/api-server
 * # composer install
 * # vendor/phpunit/phpunit/phpunit --bootstrap tests/UnitTest/bootstrap.php tests/UnitTest/MyApi/ModelsTest.php
 *
 */

namespace MyApi;

class ModelsTest extends \PHPUnit_Framework_TestCase
{
    public function testMethod_withoutParameters()
    {
        $obj = new Models\Test();
        $result = $obj->test();
        $this->assertEquals($result, array('response' => 'hello world'));
    }

    public function testMethod_withParameters()
    {
        $obj = new Models\Test();
        $result = $obj->test('123ab', 'test@domain.com');

        $this->assertEquals($result, array('response' => 'hello test@domain.com with id: 123ab'));
    }
}
