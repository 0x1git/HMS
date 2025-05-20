<?php
include 'config.php';

$sql = "DESCRIBE room";
$result = mysqli_query($conn, $sql);

echo "Room Table Structure:\n";
while($row = mysqli_fetch_assoc($result)) {
    echo "Field: " . $row['Field'] . 
         " | Type: " . $row['Type'] . 
         " | Null: " . $row['Null'] . 
         " | Key: " . $row['Key'] . 
         " | Default: " . $row['Default'] . 
         " | Extra: " . $row['Extra'] . "\n";
}
?>
