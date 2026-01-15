<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Tổng số sách
$sql_total_books = "SELECT COUNT(*) AS total_books FROM sach";
$result_total = $conn->query($sql_total_books);
$total_books = $result_total ? $result_total->fetch_assoc()["total_books"] : 0;

// Biểu đồ số sách xuất bản theo năm
$sql_books_by_year = "SELECT nam_xuat_ban, COUNT(*) AS count FROM sach GROUP BY nam_xuat_ban ORDER BY nam_xuat_ban ASC";
$result_books_by_year = $conn->query($sql_books_by_year);
$books_by_year = [];
while ($row = $result_books_by_year->fetch_assoc()) {
    $books_by_year[$row["nam_xuat_ban"]] = $row["count"];
}

// Số lượt xem trung bình
$sql_avg_views = "SELECT AVG(so_luot_xem) AS avg_views FROM sach";
$result_avg_views = $conn->query($sql_avg_views);
$avg_views = $result_avg_views ? round($result_avg_views->fetch_assoc()["avg_views"], 2) : 0;

// Biểu đồ số sách theo chủ đề
$sql_books_by_category = "SELECT id_chu_de, COUNT(*) AS count FROM sach GROUP BY id_chu_de";
$result_books_by_category = $conn->query($sql_books_by_category);
$books_by_category = [];
while ($row = $result_books_by_category->fetch_assoc()) {
    $books_by_category[$row["id_chu_de"]] = $row["count"];
}

// Sách có lượt xem cao nhất
$sql_most_viewed_book = "SELECT ten_sach, so_luot_xem FROM sach ORDER BY so_luot_xem DESC LIMIT 1";
$result_most_viewed = $conn->query($sql_most_viewed_book);
$most_viewed_book = $result_most_viewed ? $result_most_viewed->fetch_assoc() : null;

// Sách có lượt xem thấp nhất
$sql_least_viewed_book = "SELECT ten_sach, so_luot_xem FROM sach ORDER BY so_luot_xem ASC LIMIT 1";
$result_least_viewed = $conn->query($sql_least_viewed_book);
$least_viewed_book = $result_least_viewed ? $result_least_viewed->fetch_assoc() : null;

// Sách mới ra nhất (năm xuất bản lớn nhất)
$sql_newest_book = "SELECT ten_sach, nam_xuat_ban FROM sach ORDER BY nam_xuat_ban DESC LIMIT 1";
$result_newest_book = $conn->query($sql_newest_book);
$newest_book = $result_newest_book ? $result_newest_book->fetch_assoc() : null;

// Sách cũ nhất (năm xuất bản nhỏ nhất)
$sql_oldest_book = "SELECT ten_sach, nam_xuat_ban FROM sach ORDER BY nam_xuat_ban ASC LIMIT 1";
$result_oldest_book = $conn->query($sql_oldest_book);
$oldest_book = $result_oldest_book ? $result_oldest_book->fetch_assoc() : null;

// Trả về dữ liệu JSON
echo json_encode([
    "success" => true,
    "total_books" => $total_books,
    "books_by_year" => $books_by_year,
    "avg_views" => $avg_views,
    "books_by_category" => $books_by_category,
    "most_viewed_book" => $most_viewed_book,
    "least_viewed_book" => $least_viewed_book,
    "newest_book" => $newest_book,
    "oldest_book" => $oldest_book
]);

$conn->close();
?>