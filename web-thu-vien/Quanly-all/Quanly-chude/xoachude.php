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
if (!isset($data["idChuDe"])) {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$idChuDe = (int) $data["idChuDe"];

// Kiểm tra xem chủ đề có tồn tại không
$checkSql = "SELECT * FROM chu_de WHERE id_chu_de = '$idChuDe'";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows == 0) {
    echo json_encode(["error" => "Không tìm thấy chủ đề với ID '$idChuDe'!"]);
    exit;
}

// Kiểm tra xem có sách nào thuộc chủ đề này không
$checkSachSql = "SELECT * FROM sach WHERE id_chu_de = '$idChuDe'";
$checkSachResult = $conn->query($checkSachSql);

if ($checkSachResult->num_rows > 0) {
    echo json_encode(["error" => "Không thể xóa vì vẫn còn sách thuộc chủ đề này!"]);
    exit;
}

// Nếu không có sách nào, thực hiện xóa chủ đề
$sql = "DELETE FROM chu_de WHERE id_chu_de = '$idChuDe'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Xóa chủ đề thành công!"]);
} else {
    echo json_encode(["error" => "Lỗi khi xóa chủ đề: " . $conn->error]);
}

$conn->close();
?>
