<?php

// Database configuration
$databaseHost = 'localhost'; // or your database host
$databaseName = 'serenity'; // your database name
$databaseUsername = 'root'; // your database username
$databasePassword = ''; // your database password

// Create connection
$koneksi = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}
