<?php

namespace ApiServer;

class CustomView extends \Slim\View
{
    public function render($mustBeAuthorized, $data = null)
    {
        //todo: à utiliser avant d'exeuter les méthodes afin de rendre $data
        if($mustBeAuthorized === true) {
            //doUseAuthorization();
        }

        if(isset($this->data['error'])) {
            if(isset($this->data['error']['message']) && substr($this->data['error']['message'],0,1) == '{') {
                echo json_encode(array('error' => json_decode($this->data['error']['message'], true)));
            }
            // If an \Exception appears during execution
            elseif(isset($this->data['error']['message'])) {
                echo json_encode(array('error' => $this->data['error']['message']));
            }
            else {
                echo json_encode(array('error' => 'failed'));
            }
        }
        elseif(isset($this->data['data'])) {
            if (!is_object($this->data['data'])) {
                echo json_encode($this->data['data']);
            }
        }
        elseif(!empty($this->data)) {
            echo json_encode($this->data);
        }
    }
}
