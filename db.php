<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "society_db";

// Connect to MySQL server (without specifying DB first to handle case where DB doesn't exist)
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Create database if not exists
$db_create = mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$database`");
if (!$db_create) {
    die("Database Creation Failed: " . mysqli_error($conn));
}

// Select the database
mysqli_select_db($conn, $database);

// Verify if the database schema is up-to-date
// 1. Check if 'members' table has the new 'reg_no' column (old schema had 'regno')
$members_table_ok = false;
$col_check = mysqli_query($conn, "SHOW COLUMNS FROM `members` LIKE 'reg_no'");
if ($col_check && mysqli_num_rows($col_check) > 0) {
    $members_table_ok = true;
}

// 2. Check if the 'payments' table exists
$payments_table_exists = false;
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'payments'");
if ($table_check && mysqli_num_rows($table_check) > 0) {
    $payments_table_exists = true;
}

// If either check fails, re-import the entire up-to-date schema
if (!$members_table_ok || !$payments_table_exists) {
    $sql_file = __DIR__ . '/society_db.sql';
    if (file_exists($sql_file)) {
        $sql_content = file_get_contents($sql_file);
        
        // Execute the multi-query to drop old tables, build new ones, and insert sample data
        if (mysqli_multi_query($conn, $sql_content)) {
            // Flush all results to clear connection state
            do {
                if ($res = mysqli_store_result($conn)) {
                    mysqli_free_result($res);
                }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            
            // Reconnect to reset the mysqli connection state after multi-query operations
            $conn = mysqli_connect($host, $user, $password, $database);
        } else {
            die("Auto-import of database failed: " . mysqli_error($conn));
        }
    } else {
        die("Database tables not found, and society_db.sql is missing!");
    }
}
?>