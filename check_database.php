<?php
include 'config.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check roombook table structure
echo "<h2>Roombook Table Structure</h2>";
$result = mysqli_query($conn, "DESCRIBE roombook");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['Field']."</td>";
        echo "<td>".$row['Type']."</td>";
        echo "<td>".$row['Null']."</td>";
        echo "<td>".$row['Key']."</td>";
        echo "<td>".$row['Default']."</td>";
        echo "<td>".$row['Extra']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error getting table structure: " . mysqli_error($conn);
}

// Check recent entries in roombook table
echo "<h2>Recent Roombook Entries (last 5)</h2>";
$result = mysqli_query($conn, "SELECT * FROM roombook ORDER BY id DESC LIMIT 5");
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        // Print table headers
        $fields = mysqli_fetch_fields($result);
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<th>".$field->name."</th>";
        }
        echo "</tr>";
        
        // Print data rows
        mysqli_data_seek($result, 0);
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach($row as $key => $value) {
                echo "<td>".$value."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No recent roombook entries found.";
    }
} else {
    echo "Error getting recent entries: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
