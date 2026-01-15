<?php
header('Content-Type: application/json');

// Kết nối Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Lấy ID từ request
if (isset($_GET["idChuDe"])) {
    $idChuDe = (int)$_GET["idChuDe"];

    // Kiểm tra ID chủ đề trong database
    $sql = "SELECT id_chu_de FROM chu_de WHERE id_chu_de = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idChuDe);
    $stmt->execute();
    $result = $stmt->get_result();

    // Trả về kết quả
    if ($result->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }

    // Đóng statement
    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu ID chủ đề!"]);
}

// Đóng kết nối
$conn->close();
?>
