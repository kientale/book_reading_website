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
$allowedFilters = ["ten_sach", "tac_gia", "nam_xuat_ban", "id_chu_de"];
if (!in_array($filterType, $allowedFilters)) {
    echo json_encode(["success" => false, "error" => "Bộ lọc không hợp lệ!"]);
    exit;
}

$column = $filterType;

// Xử lý truy vấn
if ($column === "nam_xuat_ban" || $column === "id_chu_de") {
    if (!is_numeric($keyword)) {
        echo json_encode(["success" => false, "error" => "Giá trị không hợp lệ cho bộ lọc này!"]);
        exit;
    }
    $sql = "SELECT id_sach, ten_sach, tac_gia, nam_xuat_ban, so_luong, so_luot_xem, id_chu_de, mo_ta_sach, hinh_anh FROM sach WHERE $column = ?";
} else {
    $sql = "SELECT id_sach, ten_sach, tac_gia, nam_xuat_ban, so_luong, so_luot_xem, id_chu_de, mo_ta_sach, hinh_anh FROM sach WHERE LOWER($column) LIKE LOWER(?)";
    $keyword = "%$keyword%";
}

// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "error" => "Lỗi truy vấn!"]);
    exit;
}

// Gán giá trị vào truy vấn
if ($column === "nam_xuat_ban" || $column === "id_chu_de") {
    $stmt->bind_param("i", $keyword);
} else {
    $stmt->bind_param("s", $keyword);
}

$stmt->execute();
$result = $stmt->get_result();

// Xử lý kết quả
$books = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra ảnh có tồn tại không
        if (!empty($row["hinh_anh"])) {
            $row["hinh_anh"] = "data:image/png;base64," . base64_encode($row["hinh_anh"]);
        } else {
            $row["hinh_anh"] = null; // Nếu không có ảnh, đặt là null
        }
        $books[] = $row;
    }
}

// Trả về kết quả JSON
echo json_encode(["success" => true, "books" => $books]);

$stmt->close();
$conn->close();
?>
