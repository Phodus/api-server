<?php

namespace MyApi\Controllers;

use ApiServer\ControllerManager as ControllerManager;
use MyApi\Models as Models;
use ApiServer\ResponseProxy as ResponseProxy;

class Test extends ControllerManager
{
    public static function test_get()
    {
        $obj = new Models\Test();
        return $obj->test();
    }

    public static function test_post($id, $email)
    {
        $obj = new Models\Test();
        return $obj->test($id, $email);
    }
}
