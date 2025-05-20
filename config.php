<?php

$server = "localhost";
$username = "root";
$password = "228899";
$database = "goldenpalacehotel";

$conn = mysqli_connect($server,$username,$password,$database);

if(!$conn){
    die("<script>alert('connection Failed.')</script>");
}
// else{
//     echo "<script>alert('connection successfully.')</script>";
// }
?>