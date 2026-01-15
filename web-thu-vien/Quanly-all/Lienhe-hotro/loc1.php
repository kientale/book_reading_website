<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}
$conn->set_charset("utf8");

// Lấy dữ liệu từ AJAX
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data["keyword"]) && isset($data["filterType"])) {
    $keyword = $conn->real_escape_string($data["keyword"]);
    $filterType = $conn->real_escape_string($data["filterType"]);

    // Thiết lập cột lọc mặc định
    $column = "hoten";
    if ($filterType === "email") {
        $column = "email";
    } elseif ($filterType === "phone") {
        $column = "sdt";
    } elseif ($filterType === "address") {
        $column = "diachi";
    }

    // Truy vấn dữ liệu từ bảng `lien_he` theo từ khóa và loại lọc
    $sql = "SELECT * FROM lien_he WHERE $column LIKE '%$keyword%'";
    $result = $conn->query($sql);

    $users = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    echo json_encode($users);
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>
