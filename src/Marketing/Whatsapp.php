<?php
namespace Marketing;

use Exception;
use Marketing\Helper;
use Marketing\Helper\SendRequest;
use Marketing\Helper\InputChecker;

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

    public function sendOtp($phone_number, $otp, $country=false){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        $data = array();
        $data['phone'] = $phone_number;
        $data['country'] = $country;
        $data['message'] = $otp;

        $endpoint = 'wa/verification/send';
        $method = 'POST';

        $send_messages = $request->sendRequest($data, null, $endpoint, $method);
        if($send_messages['error'] === true){
            throw new Exception($send_messages['message']);
        }
        return $send_messages;
    }

    public function verifyOtp($phone_number, $otp, $country=false){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        $data = array();
        $data['phone'] = $phone_number;
        $data['country'] = $country;
        $data['message'] = $otp;

        $endpoint = 'wa/verification/verify';
        $method = 'POST';

        $verify_message = $request->sendRequest($data, null, $endpoint, $method);
        if($verify_message['error'] === true){
            throw new Exception($verify_message['message']);
        }
        return $verify_message;
    }

    public function sendMessageByGroup($user_group, $message, $image, $sending_type, $sending_date=false){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        if($message == ""){
            throw new Exception("message_required");
        }
        if(!$user_group){
            throw new Exception("user_group_required");
        }

        if($sending_type === 2 && !$sending_date){
            throw new Exception("sending_date_required");
        }
        $data = array();
        $data['user_group'] = $user_group;
        $data['type'] = 1;
        if($image){
            $data['type'] = 2;
            $data['image'] = $image;
        }
        $data['message'] = $message;
        $data['sending_type'] = $sending_type;
        if($sending_type === 2){
            $data['sending_date'] = $sending_date;
        }

        $endpoint = 'wa/blast/group';
        $method = 'POST';

        $send_messages = $request->sendRequest($data, null, $endpoint, $method);
        if($send_messages['error'] === true){
            throw new Exception($send_messages['message']);
        }
        return $send_messages;
    }

    public function sendMessage($array_phone_number, $message, $image, $sending_type, $sending_date=false){
        $request = new SendRequest($this->base_url, $this->app_id, $this->api_key);
        if($message == ""){
            throw new Exception("message_required");
        }
        if(count($array_phone_number) <= 0){
            throw new Exception("phone_number_list_required");
        }

        if($sending_type === 2 && !$sending_date){
            throw new Exception("sending_date_required");
        }

        foreach($array_phone_number as $key=>$phone){
            if(!$phone->number){
                throw new Exception("phone_number_required");
            }
        }

        

        $data = array();
        $data['whatsapps'] = $array_phone_number;
        $data['type'] = 1;
        if($image){
            $data['type'] = 2;
            $data['image'] = $image;
        }
        $data['message'] = $message;
        $data['sending_type'] = $sending_type;
        if($sending_type === 2){
            $data['sending_date'] = $sending_date;
        }

        $endpoint = 'wa/blast/phone';
        $method = 'POST';

        $send_messages = $request->sendRequest($data, null, $endpoint, $method);
        if($send_messages['error'] === true){
            throw new Exception($send_messages['message']);
        }
        return $send_messages;
    }
}
