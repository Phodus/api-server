<?php

namespace ApiServer;

class ResponseProxy {

    public static function sendReponse($data) {
        if(isset($data['error']) && $data['message']) {
            throw new \ApiServer\ExceptionApi(json_encode($data), 400);
        }
        return array('data' => $data);
    }
}
