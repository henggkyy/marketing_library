<?php
namespace Marketing;

use Exception;
use Marketing\Helper;
use Marketing\Helper\SendRequest;

class Whatsapp{

    public $app_id;
    public $api_key;
    public $base_url;

    public function __construct($base_url, $app_id, $api_key){
        $this->app_id = $app_id;
        $this->api_key = $api_key;
        $this->base_url = $base_url;
    }


    public function getDeviceStatus(){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        $data = array();
        $endpoint = 'wa/device/status';
        $method = 'POST';
        $device_status = $request->sendRequest($data, null, $endpoint, $method);
        if($device_status['error'] === true){
            throw new Exception($device_status['message']);
        }
        return $device_status;
    }
}
