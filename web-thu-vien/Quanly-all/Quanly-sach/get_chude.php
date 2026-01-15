<?php
header('Content-Type: application/json');

// Kết nối database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Truy vấn lấy danh sách chủ đề
$sql = "SELECT id_chu_de, ten_chu_de FROM chu_de";
$result = $conn->query($sql);

$chuDeList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chuDeList[] = $row;
    }
}

echo json_encode($chuDeList, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
