<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portifolio2025";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, description, image FROM projects ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        if (!empty($row['image'])) {
            echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:100px; height:auto;'></td>";
        } else {
            echo "<td>No Image</td>";
        }
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>Nenhum projeto adicionado ainda.</td></tr>";
}

$conn->close();
