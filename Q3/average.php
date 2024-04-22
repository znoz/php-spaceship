<?php
/**
 * 평균 구하기
 *
 * @return float 평균 값
 */
function GetAverage() {
    $servername = "서버주소";
    $username = "접속계정";
    $password = "비밀번호";
    $dbname = "DB명";
    
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

printf("평균 성공률: %f", $average);
