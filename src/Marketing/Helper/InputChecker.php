<?php
namespace Marketing\Helper;
use Exception;

class InputChecker{
    public function sanitizePhoneNumber($phone_number, $prefix_phone){
        if(substr($phone_number,0,3) == '+'.$prefix_phone){
            $result = $phone_number;
        }
        else if (substr($phone_number,0,2) == $prefix_phone){
            $result = '+'.$phone_number;
        }
        else if(substr($phone_number,0,1) == '0'){
            $result = '+'.$prefix_phone.substr($phone_number,1);
        }
        else{
            $result = '+'.$prefix_phone.$phone_number;
        }
    
        return $result;
    }
}
