
<?php
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid publisher ID.");
}

$stmt = $conn->prepare(
    "DELETE FROM publishers WHERE id = ?"
);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: index.php");
    exit;
}

$stmt->close();

http_response_code(500);
echo "Failed to delete publisher.";
?>
