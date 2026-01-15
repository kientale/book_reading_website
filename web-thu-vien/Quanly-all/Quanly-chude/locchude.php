<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

// Kết nối Database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Kết nối thất bại: " . $conn->connect_error]));
}
$conn->set_charset("utf8");

// Đọc dữ liệu JSON từ frontend
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!isset($data["keyword"])) {
    echo json_encode(["success" => false, "error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$keyword = trim($data["keyword"]);

// Truy vấn tìm kiếm chủ đề theo tên
$sql = "SELECT id_chu_de, ten_chu_de, mo_ta FROM chu_de WHERE LOWER(ten_chu_de) LIKE LOWER(?)";
$keyword = "%$keyword%"; // Dùng LIKE để tìm kiếm một phần

// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "error" => "Lỗi truy vấn!"]);
    exit;
}

// Gán giá trị vào truy vấn
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

// Xử lý kết quả
$chuDeList = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chuDeList[] = $row;
    }
}

// Trả về kết quả JSON
echo json_encode(["success" => true, "chuDeList" => $chuDeList]);

$stmt->close();
$conn->close();
?>
