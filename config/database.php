<?php
// Database config
return function (): mysqli {
    $host     = "localhost";
    $username = "root";
    $password = "";
    $dbname   = "retrorides_db";

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_errno) {
        error_log("DB Connection failed: " . $conn->connect_error);
        die("Sorry, something went wrong. Please try again later.");
    }

    if (!$conn->set_charset("utf8mb4")) {
        error_log("Error loading charset utf8mb4: " . $conn->error);
    }

    return $conn;
};
