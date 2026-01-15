<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Kiểm tra request có phải JSON không
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    die(json_encode(["error" => "Dữ liệu gửi lên không phải JSON hợp lệ!"]));
}

// Kiểm tra dữ liệu đầu vào
if (!isset($data["idTinTuc"], $data["tieuDe"], $data["noiDung"], $data["hinhAnh"], $data["ngayDang"], $data["moTaTin"])) {
    die(json_encode(["error" => "Thiếu dữ liệu!"]));
}

$idTinTuc = (int)$data["idTinTuc"];
$tieuDe = $conn->real_escape_string($data["tieuDe"]);
$noiDung = $conn->real_escape_string($data["noiDung"]);
$hinhAnh = $conn->real_escape_string($data["hinhAnh"]);
$ngayDang = $conn->real_escape_string($data["ngayDang"]);
$moTaTin = $conn->real_escape_string($data["moTaTin"]);

// Kiểm tra tin tức đã tồn tại chưa
$sqlCheck = "SELECT id_tin_tuc FROM tin_tuc WHERE id_tin_tuc = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $idTinTuc);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    die(json_encode(["error" => "ID tin tức đã tồn tại!"]));
}

// Chèn dữ liệu vào database
$sql = "INSERT INTO tin_tuc (id_tin_tuc, tieu_de, noi_dung, hinh_anh, ngay_dang, mo_ta) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $idTinTuc, $tieuDe, $noiDung, $hinhAnh, $ngayDang, $moTaTin);

if ($stmt->execute()) {
    echo json_encode(["success" => "Thêm tin tức thành công!"]);
} else {
    echo json_encode(["error" => "Lỗi khi thêm tin tức: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>
