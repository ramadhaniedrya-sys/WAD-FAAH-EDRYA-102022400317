
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "digital_library_c";
$port = 3307;

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

echo "Koneksi database berhasil!";
?>
