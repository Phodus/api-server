<?php

namespace MyApi\Models;

use ApiServer\ConfigManager as Config;

class Test
{

    public function test($id = null, $email = null)
    {
        if($id !== null && $email !== null) {
            return array('response' => 'hello '.$email.' with id: '.$id);
        }
        else {
            return array('response' => 'hello world');
        }
    }
}
