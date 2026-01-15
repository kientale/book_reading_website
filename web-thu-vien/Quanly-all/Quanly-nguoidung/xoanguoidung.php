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

// Kiểm tra dữ liệu gửi từ form
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["maNguoiDung"])) {
    $maNguoiDung = $conn->real_escape_string($data["maNguoiDung"]);

    // Kiểm tra xem mã người dùng có tồn tại không
    $checkSql = "SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = '$maNguoiDung'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows == 0) {
        echo json_encode(["error" => "Không tìm thấy người dùng với mã '$maNguoiDung'!"]);
        $conn->close();
        exit;
    }

    // Nếu tồn tại, thực hiện xóa
    $sql = "DELETE FROM nguoi_dung WHERE ma_nguoi_dung = '$maNguoiDung'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Xóa người dùng thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi xóa người dùng: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>
