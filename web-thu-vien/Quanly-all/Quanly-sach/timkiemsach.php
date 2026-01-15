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
if (!isset($data["id_sach"])) {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$id_sach = $conn->real_escape_string($data["id_sach"]);

// Truy vấn tìm sách theo ID
$sql = "SELECT id_sach, ten_sach, tac_gia, nam_xuat_ban, so_luong, so_luot_xem, id_chu_de, hinh_anh, mo_ta_sach 
        FROM sach WHERE id_sach = '$id_sach'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();

    // Chuyển đổi ảnh BLOB thành Base64
    if (!empty($book['hinh_anh'])) {
        $book['hinh_anh'] = "data:image/jpeg;base64," . base64_encode($book['hinh_anh']);
    } else {
        $book['hinh_anh'] = null; // Không có ảnh
    }

    echo json_encode(["success" => true, "book" => $book]);
} else {
    echo json_encode(["error" => "Không tìm thấy sách!"]);
}

$conn->close();
?>
