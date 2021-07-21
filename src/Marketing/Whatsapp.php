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

    public function synchronizeSession(){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        $data = array();
        $endpoint = 'wa/device/session/sync';
        $method = 'POST';

        $sync_session = $request->sendRequest($data, null, $endpoint, $method);
        if($sync_session['error'] === true){
            throw new Exception($sync_session['message']);
        }
        return $sync_session;
    }

    public function rebootSession(){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);

        $data = array();
        $endpoint = 'wa/device/session/reboot';
        $method = 'POST';

        $reboot_session = $request->sendRequest($data, null, $endpoint, $method);
        if($reboot_session['error'] === true){
            throw new Exception($reboot_session['message']);
        }
        return $reboot_session;
    }

    public function resetSession(){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        $data = array(
            "wait" => true
        );
        $endpoint = 'wa/device/session/reset';
        $method = 'POST';

        $reset_session = $request->sendRequest($data, null, $endpoint, $method);
        if($reset_session['error'] === true){
            throw new Exception($reset_session['message']);
        }
        return $reset_session;
    }

    public function getQrImage(){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);

        $data = array(
            "force" => true,
        );
        $endpoint = 'wa/qr/get';
        $method = 'POST';

        $qr_image = $request->sendRequest($data, null, $endpoint, $method);
        if($qr_image['error'] === true){
            throw new Exception($qr_image['message']);
        }
        return $qr_image;
    }

    public function getSessionHealth(){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);

        $data = array();
        $endpoint = 'wa/device/session/health';
        $method = 'POST';

        $session_health = $request->sendRequest($data, null, $endpoint, $method);
        if($session_health['error'] === true){
            throw new Exception($session_health['message']);
        }
        return $session_health;
    }
}
