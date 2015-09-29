<?php

namespace MyApi\Controllers;

use ApiServer\ControllerManager as ControllerManager;
use MyApi\Models as Models;
use ApiServer\ResponseProxy as ResponseProxy;

class Test extends ControllerManager
{
    public static function test_get()
    {
        return ResponseProxy::sendReponse(Models\Test::test());
    }

    public static function test_post($id, $email)
    {
        return ResponseProxy::sendReponse(Models\Test::test());
    }
}
