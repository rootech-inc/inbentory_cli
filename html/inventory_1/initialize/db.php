<?php
// Database credentials
$hostname = 'localhost'; // or '127.0.0.1' for local connections
$username = 'root';
$password = 'Sunderland@411';
$database = 'venta';
// Create a PDO instance
$pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

// Set PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

