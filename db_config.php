<?php
// Demo DB config for SonarCloud analysis.
// Replace these values with actual ones only on your local server.

$DB_HOST = 'localhost';
$DB_USER = 'user';
$DB_PASS = 'password';
$DB_NAME = 'dvwa';

$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($db->connect_error) {
    error_log('DB connection error: ' . $db->connect_error);
    http_response_code(500);
    die('Internal server error');
}

$db->set_charset('utf8mb4');
