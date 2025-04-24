<?php

$conn = new mysqli("sql102.infinityfree.com", "if0_38824670", "lsLYSO8m7l","if0_38824670_careconnect", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>