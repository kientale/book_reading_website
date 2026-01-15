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

// Đọc dữ liệu JSON từ yêu cầu POST
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!isset($data["id_chu_de"])) {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$id_chu_de = $data["id_chu_de"];

// Sử dụng Prepared Statement để tránh SQL Injection
$sql = "SELECT id_chu_de, ten_chu_de, mo_ta FROM chu_de WHERE id_chu_de = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_chu_de);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $chuDe = $result->fetch_assoc();
    echo json_encode(["success" => true, "chuDe" => $chuDe]);
} else {
    echo json_encode(["success" => false, "error" => "Không tìm thấy chủ đề!"]);
}

$stmt->close();
$conn->close();
?>
