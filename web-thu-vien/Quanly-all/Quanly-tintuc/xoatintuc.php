<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8");

// Đọc dữ liệu JSON từ JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!isset($data["maTinTuc"])) {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$maTinTuc = $conn->real_escape_string($data["maTinTuc"]);

// Kiểm tra xem tin tức có tồn tại không
$checkSql = "SELECT * FROM tin_tuc WHERE id_tin_tuc = '$maTinTuc'";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows == 0) {
    echo json_encode(["error" => "Không tìm thấy tin tức với ID '$maTinTuc'!"]);
    exit;
}

// Nếu tồn tại, thực hiện xóa
$sql = "DELETE FROM tin_tuc WHERE id_tin_tuc = '$maTinTuc'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Xóa tin tức thành công!"]);
} else {
    echo json_encode(["error" => "Lỗi khi xóa tin tức: " . $conn->error]);
}

$conn->close();
