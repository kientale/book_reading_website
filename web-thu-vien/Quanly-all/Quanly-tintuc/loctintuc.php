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
if (!isset($data["keyword"]) || !isset($data["filterType"])) {
    echo json_encode(["success" => false, "error" => "Dữ liệu không hợp lệ!"]);
    exit;
}

$keyword = trim($data["keyword"]);
$filterType = trim($data["filterType"]);

// Xác định cột cần lọc
$allowedFilters = ["tieu_de", "nam_dang"];
if (!in_array($filterType, $allowedFilters)) {
    echo json_encode(["success" => false, "error" => "Bộ lọc không hợp lệ!"]);
    exit;
}

$column = $filterType;

// Xử lý truy vấn
if ($column === "nam_dang") {
    // Nếu lọc theo năm đăng (số)
    if (!is_numeric($keyword)) {
        echo json_encode(["success" => false, "error" => "Giá trị không hợp lệ cho năm đăng!"]);
        exit;
    }
    $sql = "SELECT * FROM tin_tuc WHERE YEAR(ngay_dang) = ?";
} else {
    // Nếu lọc theo tiêu đề (chuỗi)
    $sql = "SELECT * FROM tin_tuc WHERE LOWER(tieu_de) LIKE LOWER(?)";
    $keyword = "%$keyword%"; // Dùng LIKE để tìm kiếm một phần
}

// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "error" => "Lỗi truy vấn!"]);
    exit;
}

// Gán giá trị vào truy vấn
if ($column === "nam_dang") {
    $stmt->bind_param("i", $keyword);
} else {
    $stmt->bind_param("s", $keyword);
}

$stmt->execute();
$result = $stmt->get_result();

// Xử lý kết quả
$newsList = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newsList[] = $row;
    }
}

// Trả về kết quả JSON
echo json_encode(["success" => true, "news" => $newsList]);

$stmt->close();
$conn->close();
?>
