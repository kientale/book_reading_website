<?php
session_start();
require 'db_connect.php'; // Đảm bảo đã kết nối với CSDL

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_sach = isset($_POST['id_sach']) ? intval($_POST['id_sach']) : 0;
    $binh_luan = isset($_POST['binh_luan']) ? trim($_POST['binh_luan']) : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Khách';

    if ($id_sach <= 0 || empty($binh_luan)) {
        echo json_encode(["success" => false, "error" => "Dữ liệu không hợp lệ"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO binh_luan (id_sach, binh_luan, ten_nguoi_dung) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_sach, $binh_luan, $username);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Lỗi khi lưu dữ liệu"]);
    }

    $stmt->close();
}

$conn->close();
?>
