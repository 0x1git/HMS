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

// Get detailed table structure for the room table
$sql = "DESCRIBE room";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

echo "<h2>Room Table Structure:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f2f2f2;'>";
echo "<th style='padding: 8px; text-align: left;'>Field</th>";
echo "<th style='padding: 8px; text-align: left;'>Type</th>";
echo "<th style='padding: 8px; text-align: left;'>Null</th>";
echo "<th style='padding: 8px; text-align: left;'>Key</th>";
echo "<th style='padding: 8px; text-align: left;'>Default</th>";
echo "<th style='padding: 8px; text-align: left;'>Extra</th>";
echo "</tr>";

$requiredFields = [];

while ($row = $result->fetch_assoc()) {
    $isNull = $row['Null'];
    $hasDefault = $row['Default'] !== NULL;
    $fieldName = $row['Field'];
    
    echo "<tr>";
    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $fieldName . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $row['Type'] . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $isNull . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $row['Key'] . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . ($hasDefault ? $row['Default'] : "NULL") . "</td>";
    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $row['Extra'] . "</td>";
    echo "</tr>";
    
    // Track required fields (those that can't be NULL and don't have a default value)
    if ($isNull === 'NO' && !$hasDefault && $row['Extra'] !== 'auto_increment') {
        $requiredFields[] = $fieldName;
    }
}

echo "</table>";

// Display the required fields
if (count($requiredFields) > 0) {
    echo "<h3>Required Fields (No NULL, No Default):</h3>";
    echo "<ul>";
    foreach ($requiredFields as $field) {
        echo "<li style='color: red;'>" . $field . "</li>";
    }
    echo "</ul>";
}

// Show a sample of the existing data
$sql = "SELECT * FROM room LIMIT 5";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

echo "<h2>Sample Data from Room Table:</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    
    // Get field names for table header
    $fields = $result->fetch_fields();
    echo "<tr style='background-color: #f2f2f2;'>";
    foreach ($fields as $field) {
        echo "<th style='padding: 8px; text-align: left;'>" . $field->name . "</th>";
    }
    echo "</tr>";
    
    // Output data rows
    $result->data_seek(0); // Reset result pointer
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . ($value === NULL ? "NULL" : $value) . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No data found in the room table.</p>";
}

// Display recommended INSERT statement structure
echo "<h3>Recommended INSERT Statement Format:</h3>";
echo "<pre style='background-color: #f8f8f8; padding: 10px; border-radius: 5px;'>";
echo "INSERT INTO room(";
$sql = "DESCRIBE room";
$result = $conn->query($sql);
$fields = [];
while ($row = $result->fetch_assoc()) {
    if ($row['Extra'] !== 'auto_increment') {
        $fields[] = $row['Field'];
    }
}
echo implode(", ", $fields);
echo ") VALUES (";
$placeholders = array_fill(0, count($fields), "'value'");
echo implode(", ", $placeholders);
echo ");</pre>";

$conn->close();
?>
