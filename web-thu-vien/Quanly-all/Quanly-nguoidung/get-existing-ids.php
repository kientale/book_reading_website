<?php

// Thiết lập thông tin kết nối Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

// Kết nối CSDL
$conn = new mysqli($host, $user, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Kết nối CSDL thất bại: " . $conn->connect_error]);
    exit();
}

// Thiết lập header JSON
header('Content-Type: application/json');

// Truy vấn danh sách ID người dùng
$sql = "SELECT ma_nguoi_dung FROM nguoi_dung";
$result = $conn->query($sql);

// Kiểm tra kết quả
if ($result) {
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['ma_nguoi_dung'];
    }
    echo json_encode(["success" => true, "ids" => $ids]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Lỗi truy vấn: " . $conn->error]);
}

// Đóng kết nối
$conn->close();
?>
