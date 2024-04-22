<?php
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_db_name";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT AVG(survivalRate) as Average FROM Picks";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Average Survival Rate: " . $row["Average"]. "<br>";
}
$conn->close();
