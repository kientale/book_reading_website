<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$database = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$chuDe = isset($_GET['chu_de']) ? intval($_GET['chu_de']) : 0;
$sort = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Điều kiện lọc theo chủ đề
$conditions = [];
$params = [];
$types = "";

if ($chuDe > 0) {
    $conditions[] = "sach.id_chu_de = ?";
    $params[] = $chuDe;
    $types .= "i";
}

// Đếm tổng số sách theo chủ đề
$sqlCount = "SELECT COUNT(*) AS total FROM sach";
if (!empty($conditions)) {
    $sqlCount .= " WHERE " . implode(" AND ", $conditions);
}

$stmtCount = $conn->prepare($sqlCount);
if (!empty($params)) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalBooks = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($totalBooks / $limit);

// Lấy danh sách sách theo chủ đề & tên chủ đề
$sql = "SELECT sach.id_sach, sach.ten_sach, sach.tac_gia, sach.nam_xuat_ban, sach.so_luong, sach.so_luot_xem, sach.hinh_anh, chu_de.ten_chu_de 
        FROM sach 
        JOIN chu_de ON sach.id_chu_de = chu_de.id_chu_de";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Kiểm tra sắp xếp hợp lệ
$sortOptions = [
    "views" => "so_luot_xem DESC",
    "year" => "nam_xuat_ban DESC",
    "quantity" => "so_luong DESC"
];
$orderBy = isset($sortOptions[$sort]) ? " ORDER BY " . $sortOptions[$sort] : "";

// Thêm LIMIT
$sql .= $orderBy . " LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
$tenChuDe = "";

while ($row = $result->fetch_assoc()) {
    if (empty($tenChuDe)) {
        $tenChuDe = $row['ten_chu_de'];
    }

    // Chuyển đổi dữ liệu ảnh LONGBLOB sang base64
    $imageData = base64_encode($row['hinh_anh']);
    $row['hinh_anh'] = "data:image/jpeg;base64," . $imageData;

    $books[] = $row;
}

// Trả kết quả JSON
echo json_encode([
    "books" => $books,
    "ten_chu_de" => $tenChuDe,
    "totalPages" => $totalPages
]);

$conn->close();
?>