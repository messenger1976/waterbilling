<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['ms_company_code'] = 'ROXAS';
$config['ms_company_name'] = 'Roxas Water District';
$config['ms_ticket_prefix'] = 'ROX';
$ms_host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
$config['ms_hub_url'] = preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $ms_host)
	? 'http://localhost/wd-support-hub/'
	: 'https://support-hub.bohollander.com/';
$config['ms_api_token'] = 'roxas-ms-4e8b1c7a9d2f5603a1b9';
$config['ms_poll_seconds'] = 8;
$config['ms_max_upload_kb'] = 4096;

// Message Board (announcements from the hub; same hub URL and token as Message Support).
$config['ms_board_enabled'] = TRUE;
$config['ms_board_refresh_minutes'] = 5;
