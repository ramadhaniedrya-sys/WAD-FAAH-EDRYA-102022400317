
<?php
require_once "connection.php";

$error = "";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    exit("Invalid publisher ID. Please return to the main page.");
}

// Get publisher data from MySQL
$stmt = $conn->prepare(
    "SELECT id, name, sales_contact, address, region
     FROM publishers
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$publisher = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$publisher) {
    exit("Publisher data not found in MySQL.");
}

// Update publisher data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $contact = trim($_POST["sales_contact"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $region = trim($_POST["region"] ?? "");

    if ($name === "" || $contact === "" ||
        $address === "" || $region === "") {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE publishers
             SET name = ?, sales_contact = ?, address = ?, region = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $name,
            $contact,
            $address,
            $region,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        }

        $error = "Failed to update publisher: " . $stmt->error;
        $stmt->close();
    }

    // Keep submitted values if validation or update fails
    $publisher["name"] = $name;
    $publisher["sales_contact"] = $contact;
    $publisher["address"] = $address;
    $publisher["region"] = $region;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Publisher</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            color: #1e293b;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 100px;
        }

        button, .cancel {
            display: inline-block;
            margin-top: 22px;
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #ffc107;
            color: #1e293b;
            font-weight: bold;
        }

        .cancel {
            background: #64748b;
            color: white;
            margin-left: 8px;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Edit Publisher / Supplier</h1>
    <p>Update publisher information.</p>

    <?php if ($error !== ""): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?= (int) $publisher['id'] ?>">

        <label for="name">Publisher Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($publisher['name']) ?>"
            required
        >

        <label for="sales_contact">Sales Contact</label>
        <input
            type="text"
            id="sales_contact"
            name="sales_contact"
            value="<?= htmlspecialchars($publisher['sales_contact']) ?>"
            required
        >

        <label for="address">Address</label>
        <textarea
            id="address"
            name="address"
            required
        ><?= htmlspecialchars($publisher['address']) ?></textarea>

        <label for="region">Region of Origin</label>
        <input
            type="text"
            id="region"
            name="region"
            value="<?= htmlspecialchars($publisher['region']) ?>"
            required
        >

        <button type="submit">Update Publisher</button>
        <a href="index.php" class="cancel">Cancel</a>
    </form>

</div>
</body>
</html>
