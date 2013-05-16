<?php

class Pushover
{
    private $token         = null;
    private $curl_result   = null;
    private $curl_response = null;
    
    private $logging   = false;
    private $log_model = '';
    
    protected $ci;

    public $base_url = 'https://api.pushover.net/1/';
    
    //Message data
    public $user;
    public $message;
    public $title;
    public $options = array();

    public function __construct($config = array())
    {
        $this->token = $config['pushover_token'];

        if($config['enable_logging'] === true){
            $this->logging   = true;
            $this->log_model = $config['logging_model']; 
            $this->ci = get_instance();
            $this->ci->load->model($this->log_model);
        }
    }
    
    public function sendMessage($user = null, $message = null, $title = null, $options = array())
    {
        if(!empty($user)){
            $this->setUser($user);
        }
        
        if(!empty($message)){
            $this->setMessage($message);
        }
        if(!empty($title)){
            $this->setTitle($title);
        }
        
        if(!empty($options)){
            $this->options = array_merge($this->options, $options);
        }
        
        if(!empty($this->user) && !empty($this->message)){
            $data = array(
                'user'    => $this->user,
                'message' => $this->message
            );

            if(!empty($this->title)){
                $data['title'] = $this->title;
            }

            $data = array_merge($data, $this->options);

            return $this->doRequest('messages', $data);
        } else {
            $this->curl_result = 'Must have, at minimum, user and message to send a message';
            return false;
        }
    }
    
    public function clear()
    {
        $this->user    = '';
        $this->message = '';
        $this->title   = '';
        $this->options = array();
    }
    
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function setOption($name, $value)
    {
        if(empty($value)){
            unset($this->options[$name]);
        } else {
            $this->options[$name] = $value;
        }
        
        return $this;
    }
    
    public function getCurlResult()
    {
        return $this->curl_result;
    }
    
    public function getCurlResponse()
    {
        return $this->curl_response;
    }
    
    public function getSounds()
    {
        return $this->doRequest('sounds', array(), 'get');
    }
    
    public function getError()
    {
        return $this->curl_result;
    }
    
    private function doRequest($action, $data = array(), $method = 'post', $format = 'json')
    {
        $headers = null;

        $action  = $action . '.' . $format;
        $url     = $this->base_url . $action;

        $params = array(
            'token' => $this->token
        );

        $params = array_merge($params, $data);

        if(strcasecmp($method, 'get') == 0){
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        if(strcasecmp($method, 'post') == 0){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        $this->curl_result   = curl_exec($ch);
        $this->curl_response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if(curl_errno($ch)){
            $this->curl_result = curl_error($ch);
            $return = false;
        } else {
            $return = json_decode($this->curl_result);
            $this->clear();
        }
        if($this->logging){
            $this->ci->{$this->log_model}->logResponse($return, $this->curl_result, $this->curl_response, $params);
        }

        curl_close($ch);

        return $return;
    }
}
