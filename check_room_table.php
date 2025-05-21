<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "228899";
$database = "goldenpalacehotel";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get table structure
$sql = "DESCRIBE room";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

echo "<h2>Room Table Structure:</h2>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . ($row['Default'] === NULL ? "NULL" : $row['Default']) . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Also check for any INSERT/UPDATE triggers on this table
$sql = "SHOW TRIGGERS WHERE `table` = 'room'";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>Triggers on Room Table:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Trigger</th><th>Event</th><th>Statement</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Trigger'] . "</td>";
        echo "<td>" . $row['Event'] . "</td>";
        echo "<td>" . $row['Statement'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No triggers found for the 'room' table.</p>";
}

$conn->close();
?>
