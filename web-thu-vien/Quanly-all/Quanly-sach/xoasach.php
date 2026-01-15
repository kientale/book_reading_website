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

// Kiểm tra dữ liệu
if (!isset($data["maSach"])) {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$maSach = $conn->real_escape_string($data["maSach"]);

// Kiểm tra xem mã sách có tồn tại không
$checkSql = "SELECT * FROM sach WHERE id_sach = '$maSach'";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows == 0) {
    echo json_encode(["error" => "Không tìm thấy sách với mã '$maSach'!"]);
    exit;
}

// Nếu tồn tại, thực hiện xóa
$sql = "DELETE FROM sach WHERE id_sach = '$maSach'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Xóa sách thành công!"]);
} else {
    echo json_encode(["error" => "Lỗi khi xóa sách: " . $conn->error]);
}

$conn->close();
