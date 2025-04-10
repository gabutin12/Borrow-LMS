<?php
session_start();
require_once '../db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: ../index.php');
    exit();
}
