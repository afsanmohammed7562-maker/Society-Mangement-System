<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

function checkRole($allowed_roles) {
    if (!isset($_SESSION['role'])) {
        header("Location: login.html");
        exit();
    }
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: " . $_SESSION['role'] . "_dashboard.php");
        exit();
    }
}

function isAdmin() { return $_SESSION['role'] === 'admin'; }
function isMember() { return $_SESSION['role'] === 'member'; }
function isSecretary() { return $_SESSION['role'] === 'secretary'; }
function isTreasurer() { return $_SESSION['role'] === 'treasurer'; }
