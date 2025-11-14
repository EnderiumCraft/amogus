<?php

// DVWA-like vulnerable SQL Injection example

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // SQL Injection vulnerability:
    // concatenation of raw user input directly inside SQL query
    $query = "SELECT first_name, last_name FROM users WHERE user_id = '$id'";

    // This code is intentionally insecure for training purposes
    echo "<h2>Vulnerable SQL Query</h2>";
    echo "<pre>$query</pre>";
}
?>
