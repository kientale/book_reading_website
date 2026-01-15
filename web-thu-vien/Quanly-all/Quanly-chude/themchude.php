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

// Lấy dữ liệu JSON từ request
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (isset($data["idChuDe"], $data["tenChuDe"], $data["moTa"])) {
    $idChuDe = (int)$data["idChuDe"];
    $tenChuDe = $conn->real_escape_string($data["tenChuDe"]);
    $moTa = $conn->real_escape_string($data["moTa"]);

    // Kiểm tra chủ đề đã tồn tại chưa
    $sqlCheck = "SELECT id_chu_de FROM chu_de WHERE id_chu_de = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $idChuDe);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo json_encode(["error" => "ID chủ đề đã tồn tại!"]);
        exit;
    }

    // Chèn dữ liệu vào database
    $sql = "INSERT INTO chu_de (id_chu_de, ten_chu_de, mo_ta) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idChuDe, $tenChuDe, $moTa);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Thêm chủ đề thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi thêm chủ đề: " . $conn->error]);
    }

    // Đóng statement
    $stmt->close();
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

// Đóng kết nối
$conn->close();
?>
