<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "waterstation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die ("Error connecton failed:" . $conn->connect_error);
}