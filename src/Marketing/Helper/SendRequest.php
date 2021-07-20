<?php
namespace Marketing\Helper;
use Exception;

class SendRequest{
    public $base_url = "https://tools.saturibu.com/";
    public $api_key;
    public $app_id;

    public function __construct($app_id, $api_key)
    {
        $this->app_id = $app_id;
        $this->api_key = $api_key;
    }

    public function sendRequest($postParam, $queryParam, $uri, $method){
        try {
            if ($postParam && !is_array($postParam)) throw new Exception('request_body_must_be_array');
            if ($queryParam && !is_array($queryParam)) throw new Exception('query_param_must_be_array');
            if (!$uri) throw new Exception('uri_is_required');
            if (!$method) throw new Exception('method_is_required');
    
            if($method != "POST"  && $method != "GET" && $method != "PUT" && $method != "PATCH" && $method != "DELETE")
            throw new Exception('unknown_method');
        
         
            
            $hash = $this->generateHashKey($postParam);
            
            $postParam['hash'] = $hash;
            $postParam['app_id'] =  $this->app_id;
            
            if ($queryParam) $uri .= '?'.http_build_query($queryParam);
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,  $this->base_url .$uri);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($method == "POST") {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postParam));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = curl_exec($ch);
            if ($data === false) {
                $errorMessage = curl_error($ch);
                curl_close($ch);
                throw new Exception($errorMessage);
            }
            
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            $response = json_decode($data);
            $response->error = false;
            if ($response->status != 200  ) {
                throw new Exception($response->message);
            }
        } catch (\Exception $e) {
            $response = new \stdClass();
            $response->message = $e->getMessage();
            $response->error = true;
        }

        return (array)$response;
    }

    protected function generateHashKey($param) {
        $hash = "";
        if ($param) {
            ksort($param);

            $stringToEncrypt = "";
            foreach ($param as $key => $value) {
                if ($value != "") $stringToEncrypt .= $value.".";
            }
            $stringToEncrypt = substr($stringToEncrypt, 0, -1);
            $hash = hash_hmac('sha256', $stringToEncrypt, $this->api_key);
        }

        return $hash;
    }
}