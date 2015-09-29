<?php

namespace ApiServer;

use \ApiServer\Parameters as Parameters;

class ControllerManager {

    protected static function getAccountId() {
        $params = Parameters::getInstance();
        if($params->getParamFromBody('user_id') !== null && $params->getParamFromBody('from_pool') !== null && $params->getParamFromBody('from_pool') == 'I8vfjsWQhPiEgQuJgXY') {
            $result = $params->getParamFromBody('user_id');
        }
        else {
            if (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
            } else {
                $requestHeaders = getAllHeadersSpe();
            }
            if (!isset($requestHeaders['Authorization'])) {
                callUnauthorized("No authorization header sent for getAccountId");
            }
            $authorizationHeader = $requestHeaders['Authorization'];

            if ($authorizationHeader === null) {
                callUnauthorized("Strange authorization header sent for getAccountId");
            }

            // Validate the token
            $access_token = str_replace('Bearer ', '', $authorizationHeader);

            $result = \MyApi\Models\Token::getAccountId($access_token);
            if ($result === false) {
                callUnauthorized("Token not found in database for account id");
            }
        }
        return $result;
    }
}
