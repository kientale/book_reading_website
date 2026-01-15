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

    // Mặc định lọc theo họ tên
    $column = "ho_ten";
    if ($filterType === "age") {  
        $column = "tuoi";
    } elseif ($filterType === "address") {
        $column = "dia_chi";
    }

    if ($filterType === "name") {
        // Xử lý accent-sensitive + match từ “anh” thay vì “khanh”
        $keywordLower = mb_strtolower($keyword, 'UTF-8');
        $sql = "SELECT * FROM nguoi_dung
                WHERE CONCAT(' ', LOWER(ho_ten), ' ') COLLATE utf8_bin
                LIKE '% $keywordLower %'";
    } else {
        // Tuổi hoặc địa chỉ vẫn LIKE bình thường
        $sql = "SELECT * FROM nguoi_dung
                WHERE $column LIKE '%$keyword%'";
    }

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
