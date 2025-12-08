 <?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class send_sms_notification extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mobilenotifications_model', 'sms_model');
    }
    
    public function index() {
        // Use traditional ITEXMO endpoint (broadcast endpoint returns 405 Method Not Allowed)
        // The broadcast API endpoint doesn't work, so we use the traditional endpoint
        $endpoint = 'https://www.itexmo.com/php_api/api.php';
        
        // Get SMS settings from database
        $settings = $this->sms_model->get_sms_settings();
        
        if(!$settings || empty($settings['api_code']) || empty($settings['api_password'])){
            echo json_encode(array(
                'success' => false,
                'message' => 'ITEXMO API settings not configured. Please configure API Code and Password in Settings.'
            ));
            exit;
        }
        
        // Example data - modify these values as needed or get from POST/GET parameters
        $mobile = $this->input->post('mobile') ?: $this->input->get('mobile') ?: '639151874107';
        $message = $this->input->post('message') ?: $this->input->get('message') ?: 'Test message';
        
        // Clean mobile number (remove spaces, dashes, etc.)
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        
        // Remove leading zeros if present
        $mobile = ltrim($mobile, '0');
        
        // For Philippines numbers, ensure it starts with country code if not present
        if(strlen($mobile) == 10 && substr($mobile, 0, 1) == '9'){
            $mobile = '63' . $mobile;
        }
        elseif(strlen($mobile) == 11 && substr($mobile, 0, 2) == '09'){
            $mobile = '63' . substr($mobile, 1);
        }
        
        $itexmo = array(
            '1' => $mobile,
            '2' => $message,
            '3' => $settings['api_code'],
            'passwd' => $settings['api_password']
        );
        
        if(!empty($settings['sender_id'])){
            $itexmo['6'] = $settings['sender_id'];
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1); // Set POST method (this was missing!)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itexmo)); // Send POST data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response instead of outputting
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Allow self-signed certs
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'); // Some APIs require User-Agent
        
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Check for cURL errors
        if($response === false || !empty($curl_error)){
            echo json_encode(array(
                'success' => false,
                'message' => 'cURL Error: ' . ($curl_error ? $curl_error : 'Request failed'),
                'http_code' => $http_code,
                'code' => 'CURL_ERROR'
            ));
            exit;
        }
        
        // Check for server errors (4xx, 5xx)
        if($http_code >= 400){
            $error_message = 'ITEXMO API Error (HTTP ' . $http_code . ')';
            
            // Check if response is HTML (server error page)
            if(stripos($response, '<!DOCTYPE html>') !== false || stripos($response, '<html') !== false){
                $error_message .= '. Server returned an error page.';
                // Try to extract error message from HTML
                if(preg_match('/<title[^>]*>(.*?)<\/title>/is', $response, $matches)){
                    $error_message .= ' Error: ' . strip_tags($matches[1]);
                }
            } else {
                $error_message .= '. Response: ' . trim($response);
            }
            
            echo json_encode(array(
                'success' => false,
                'message' => $error_message,
                'http_code' => $http_code,
                'response' => trim($response),
                'code' => 'HTTP_ERROR',
                'mobile' => $mobile,
                'debug' => array(
                    'api_code' => substr($settings['api_code'], 0, 3) . '***',
                    'endpoint' => $endpoint,
                    'post_data_keys' => array_keys($itexmo)
                )
            ));
            exit;
        }
        
        // Check if response is empty
        if(empty(trim($response))){
            echo json_encode(array(
                'success' => false,
                'message' => 'Empty response from ITEXMO API. HTTP Code: ' . $http_code,
                'http_code' => $http_code,
                'code' => 'EMPTY_RESPONSE'
            ));
            exit;
        }
        
        // ITEXMO returns numeric codes: 0 = success, other numbers = error codes
        $response_trimmed = trim($response);
        $itexmo_codes = array(
            '0' => 'Message sent successfully',
            '1' => 'Invalid number',
            '2' => 'Number prefix not supported',
            '3' => 'Invalid API Code',
            '4' => 'Maximum message per day reached',
            '5' => 'Maximum allowed characters for message reached',
            '6' => 'System Offline',
            '7' => 'Expired API Code',
            '8' => 'iTexMo Error',
            '9' => 'Invalid function parameters',
            '10' => 'Recipient\'s number is blocked',
            '11' => 'Recipient\'s number is invalid',
            '12' => 'Invalid sender ID',
            '13' => 'Sender ID not allowed'
        );
        
        $status_message = isset($itexmo_codes[$response_trimmed]) ? $itexmo_codes[$response_trimmed] : 'Unknown response code: ' . $response_trimmed;
        $is_success = ($response_trimmed === '0');
        
        echo json_encode(array(
            'success' => $is_success,
            'http_code' => $http_code,
            'itexmo_code' => $response_trimmed,
            'message' => $status_message,
            'response' => $response_trimmed,
            'mobile' => $mobile,
            'message_sent' => $message
        ));
        exit;
        //$this->load->view('send_sms_notification');
    }
}


?>