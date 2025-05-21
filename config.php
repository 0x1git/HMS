<?php

$server = "localhost";
$username = "root";
$password = "228899"; // Empty password for local development security
$database = "goldenpalacehotel"; // Updated to match your current database

$conn = mysqli_connect($server, $username, $password, $database);

if(!$conn){
    die("<script>alert('Connection Failed.')</script>");
}
// else{
//     echo "<script>alert('Connection successful.')</script>";
// }
?>
