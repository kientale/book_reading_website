<?php
// Bật debug PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// Nhận dữ liệu JSON từ frontend
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Kiểm tra lỗi JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi khi decode JSON!",
        "json_error" => json_last_error_msg(),
        "raw_json" => $json
    ]);
    exit;
}

// Kiểm tra dữ liệu đầu vào
if (!$data || !isset($data['users']) || !is_array($data['users'])) {
    echo json_encode(["status" => "error", "message" => "Không có dữ liệu hợp lệ!"]);
    exit;
}

// Chuẩn bị truy vấn SQL
$checkStmt = $conn->prepare("SELECT COUNT(*) FROM nguoi_dung WHERE ma_nguoi_dung = ?");
$insertStmt = $conn->prepare("INSERT INTO nguoi_dung (ma_nguoi_dung, tai_khoan, mat_khau, ho_ten, tuoi, email, so_dien_thoai, dia_chi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$checkStmt || !$insertStmt) {
    echo json_encode(["status" => "error", "message" => "Lỗi chuẩn bị truy vấn SQL: " . $conn->error]);
    exit;
}

// Biến lưu kết quả
$successCount = 0;
$errors = [];

foreach ($data['users'] as $row) {
    $ma_nguoi_dung = $row['ma_nguoi_dung']; 

    // Kiểm tra trùng mã người dùng
    $checkStmt->bind_param("s", $ma_nguoi_dung);
    $checkStmt->execute();
    $checkStmt->store_result();  // Đặt store_result() trước fetch()
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->free_result();

    if ($count > 0) {
        $errors[] = "Mã người dùng '$ma_nguoi_dung' đã tồn tại!";
        continue;  // Tiếp tục xử lý user tiếp theo
    }

    // Ghi log dữ liệu chuẩn bị thêm vào database
    file_put_contents("debug_log.txt", "Thêm user: " . print_r($row, true) . "\n", FILE_APPEND);

    // Thêm dữ liệu vào database
    $insertStmt->bind_param(
        "ssssisss",
        $row['ma_nguoi_dung'],
        $row['tai_khoan'],
        $row['mat_khau'],
        $row['ho_ten'],
        $row['tuoi'],
        $row['email'],
        $row['so_dien_thoai'],
        $row['dia_chi']
    );

    if (!$insertStmt->execute()) {
        $errors[] = "Lỗi khi thêm mã người dùng '$ma_nguoi_dung': " . $insertStmt->error;
        file_put_contents("debug_log.txt", "Lỗi SQL: " . $insertStmt->error . "\n", FILE_APPEND);
    } else {
        $successCount++;
    }
}

// Đóng kết nối
$checkStmt->close();
$insertStmt->close();
$conn->close();

// Trả về kết quả
$response = ["status" => "success", "message" => "$successCount user(s) đã được thêm."];
if (!empty($errors)) {
    $response["status"] = "error";
    $response["errors"] = $errors;
}
echo json_encode($response);
