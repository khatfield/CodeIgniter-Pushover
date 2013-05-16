<?php
class Pushover_log extends CI_Model
{
    private $table = 'pushover_logs';
    
    public function logResponse($result, $raw_result, $http_status, $message)
    {
        $data = array(
            'date_sent'     => date('Y-m-d H:i:s'),
            'curl_error'    => $result === false ? 1 : 0,
            'http_status'   => $http_status,
            'response_data' => $raw_result,
            'message_data'  => json_encode($message)
        );
        
        $this->db->insert($this->table, $data);
    }
}