<?php
session_start();
session_regenerate_id(true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Kết nối CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Kết nối database thất bại: " . $conn->connect_error]));
}

// Kiểm tra session đăng nhập
if (!isset($_SESSION["maNguoiDung"])) {
    echo json_encode(["success" => false, "error" => "Bạn chưa đăng nhập!"]);
    exit();
}

$maNguoiDung = $_SESSION["maNguoiDung"];

// Lấy dữ liệu JSON từ request
$dataRaw = file_get_contents("php://input");
$data = json_decode($dataRaw, true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "Dữ liệu JSON không hợp lệ!", "raw" => $dataRaw]);
    exit();
}

// Lấy dữ liệu từ request
$hoTen = trim($data["hoTen"]);
$tuoi = intval($data["tuoi"]);
$email = trim($data["email"]);
$soDienThoai = trim($data["soDienThoai"]);
$diaChi = trim($data["diaChi"]);
$matKhau = trim($data["matKhau"] ?? "");

// Kiểm tra thông tin hợp lệ
if (empty($hoTen) || empty($email) || empty($soDienThoai) || empty($diaChi)) {
    echo json_encode(["success" => false, "error" => "Thông tin không được để trống!"]);
    exit();
}

// Kiểm tra người dùng có tồn tại không
$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = ?");
$stmt->bind_param("i", $maNguoiDung);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "Người dùng không tồn tại!"]);
    exit();
}

// Cập nhật thông tin
// Cập nhật thông tin
if (!empty($matKhau)) {
    if (strlen($matKhau) < 6) {
        echo json_encode(["success" => false, "error" => "Mật khẩu phải có ít nhất 6 ký tự!"]);
        exit();
    }
    // Không mã hóa mật khẩu
    $query = "UPDATE nguoi_dung SET ho_ten=?, mat_khau=?, tuoi=?, email=?, so_dien_thoai=?, dia_chi=? WHERE ma_nguoi_dung=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisssi", $hoTen, $matKhau, $tuoi, $email, $soDienThoai, $diaChi, $maNguoiDung);
} else {
    $query = "UPDATE nguoi_dung SET ho_ten=?, tuoi=?, email=?, so_dien_thoai=?, dia_chi=? WHERE ma_nguoi_dung=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisssi", $hoTen, $tuoi, $email, $soDienThoai, $diaChi, $maNguoiDung);
}


if ($stmt->execute()) {
    // ✅ CẬP NHẬT SESSION
    $_SESSION["hoTen"] = $hoTen;
    $_SESSION["tuoi"] = $tuoi;
    $_SESSION["email"] = $email;
    $_SESSION["soDienThoai"] = $soDienThoai;
    $_SESSION["diaChi"] = $diaChi;

    echo json_encode(["success" => true, "message" => "Cập nhật thông tin thành công!", "updatedSession" => $_SESSION]);
} else {
    echo json_encode(["success" => false, "error" => "Lỗi cập nhật thông tin!"]);
}

$stmt->close();
$conn->close();
?>