<?php
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

$conn->set_charset("utf8");

if (isset($_GET["idTinTuc"])) {
    $idTinTuc = $conn->real_escape_string($_GET["idTinTuc"]);
    $sqlCheck = "SELECT id_tin_tuc FROM tin_tuc WHERE id_tin_tuc = '$idTinTuc'";
    $result = $conn->query($sqlCheck);

    echo json_encode(["exists" => $result->num_rows > 0]);
} else {
    echo json_encode(["error" => "Thiếu ID tin tức"]);
}

$conn->close();
?>
