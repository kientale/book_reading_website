<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "thu_vien_online");
$conn->set_charset("utf8");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die(json_encode(["success" => false, "error" => "ID sách không hợp lệ!"]));
}

$idSach = intval($_GET['id']);
$sql = "SELECT noi_dung_sach FROM sach WHERE id_sach = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idSach);
$stmt->execute();
$stmt->bind_result($pdfContent);
$stmt->fetch();
$stmt->close();
$conn->close();

if (!$pdfContent) {
    die(json_encode(["success" => false, "error" => "Không tìm thấy nội dung sách!"]));
}

// Trả về PDF dạng base64 để nhúng vào modal
echo json_encode(["success" => true, "pdf" => base64_encode($pdfContent)]);
?>
