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

// Nhận dữ liệu JSON từ yêu cầu POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["idChuDe"], $data["tenChuDe"])) {
    $idChuDe = (int)$data["idChuDe"];
    $tenChuDe = $conn->real_escape_string($data["tenChuDe"]);

    // Kiểm tra xem chủ đề có tồn tại không
    $checkChuDe = $conn->query("SELECT * FROM chu_de WHERE id_chu_de = '$idChuDe'");
    if ($checkChuDe->num_rows == 0) {
        echo json_encode(["error" => "Chủ đề không tồn tại!"]);
        exit();
    }

    // Cập nhật thông tin chủ đề
    $sql = "UPDATE chu_de SET ten_chu_de='$tenChuDe' WHERE id_chu_de='$idChuDe'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Cập nhật chủ đề thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi cập nhật chủ đề: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>
