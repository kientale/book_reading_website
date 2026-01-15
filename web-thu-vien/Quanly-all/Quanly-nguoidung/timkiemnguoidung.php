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

// Kiểm tra dữ liệu gửi từ frontend
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["maNguoiDung"])) {
    $maNguoiDung = $conn->real_escape_string($data["maNguoiDung"]);

    $sql = "SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = '$maNguoiDung'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        error_log(json_encode($user)); // Ghi log dữ liệu
        echo json_encode(["success" => true, "user" => $user]);
    } else {
        echo json_encode(["success" => false, "error" => "Không tìm thấy người dùng!"]);
    }    
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>
