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
if (!isset($data["id_tintuc"])) {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$id_tintuc = $conn->real_escape_string($data["id_tintuc"]);

// Truy vấn tìm tin tức theo ID
$sql = "SELECT id_tin_tuc, tieu_de, noi_dung, hinh_anh, ngay_dang, mo_ta 
        FROM tin_tuc WHERE id_tin_tuc = '$id_tintuc'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $news = $result->fetch_assoc();
    echo json_encode(["success" => true, "news" => $news]);
} else {
    echo json_encode(["error" => "Không tìm thấy tin tức!"]);
}

$conn->close();
?>
