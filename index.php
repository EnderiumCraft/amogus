<?php
session_start();
require_once __DIR__ . '/db_config.php';

if (empty($_SESSION['user_token'])) {
    $_SESSION['user_token'] = bin2hex(random_bytes(16));
}
$user_token_session = $_SESSION['user_token'];

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

if (!isset($_GET['Login'])) {
    echo '<h2>Secure Object Info System</h2>';
    echo '<p>Use GET parameters: username, password, user_token, Login=Login</p>';
    echo '<p>Your user_token: <code>' . h($user_token_session) . '</code></p>';
    echo '<p>Example:</p>';
    echo '<pre>?username=admin&password=password&user_token=' . h($user_token_session) . '&Login=Login</pre>';
    exit;
}

$username   = $_GET['username']   ?? '';
$password   = $_GET['password']   ?? '';
$user_token = $_GET['user_token'] ?? '';

if ($user_token !== $user_token_session) {
    http_response_code(400);
    echo '<pre>Invalid user_token</pre>';
    exit;
}

if (mb_strlen($username) < 3 || mb_strlen($username) > 50) {
    http_response_code(400);
    echo '<pre>Invalid username length</pre>';
    exit;
}

if (mb_strlen($password) < 3 || mb_strlen($password) > 100) {
    http_response_code(400);
    echo '<pre>Invalid password length</pre>';
    exit;
}

// Secure SQL query
$sql = 'SELECT user_id, user, first_name, last_name, email, password
        FROM users
        WHERE user = ?';

$stmt = $db->prepare($sql);

if (!$stmt) {
    error_log('Prepare failed: ' . $db->error);
    http_response_code(500);
    echo '<pre>Internal server error</pre>';
    exit;
}

$stmt->bind_param('s', $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo '<pre>Invalid username or password</pre>';
    exit;
}

// Password check (simplified for lab)
if ($password !== 'password') {
    echo '<pre>Invalid username or password</pre>';
    exit;
}

echo '<h2>Object Information (Secure)</h2>';
echo '<pre>';
echo 'User ID:     ' . h($user['user_id']) . PHP_EOL;
echo 'Username:    ' . h($user['user']) . PHP_EOL;
echo 'First name:  ' . h($user['first_name']) . PHP_EOL;
echo 'Last name:   ' . h($user['last_name']) . PHP_EOL;
echo 'Email:       ' . h($user['email']) . PHP_EOL;
echo '</pre>';

echo '<p>This endpoint uses prepared statements → SQL Injection is impossible.</p>';

$db->close();
