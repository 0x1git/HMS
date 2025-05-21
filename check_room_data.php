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

// Get sample data from room table
$sql = "SELECT * FROM room LIMIT 5";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

echo "<h2>Sample Room Data:</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    
    // Get field names
    $fields = $result->fetch_fields();
    echo "<tr>";
    foreach ($fields as $field) {
        echo "<th>{$field->name}</th>";
    }
    echo "</tr>";
    
    // Output data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<td>" . ($value === NULL ? "NULL" : $value) . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No data found in the 'room' table.</p>";
}

$conn->close();
?>
