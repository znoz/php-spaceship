<?php
function GetAverage() {
    $servername = "your_server_name";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_db_name";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    
    $sql = "SELECT AVG(survivalRate) as Average FROM Picks";
    $result = $conn->query($sql);

    $average = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $average = $row["Average"];
    }
    $conn->close();

    return $average;
}

// 샘플 실행
$average = GetAverage();

printf("평균 성공률: %d", $average);
