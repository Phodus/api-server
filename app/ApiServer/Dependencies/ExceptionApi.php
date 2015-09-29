<?php

namespace ApiServer;

class ExceptionApi extends \Exception
{
    private $http_code;

    public function __construct($message, $http_code = 200, $code = 0, Exception $previous = null) {
        $this->http_code = $http_code;
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getHttpCode() {
        return $this->http_code;
    }
}
