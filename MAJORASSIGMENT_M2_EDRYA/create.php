
<?php
require_once "connection.php";

$error = "";

$name = "";
$contact = "";
$address = "";
$region = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $contact = trim($_POST["sales_contact"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $region = trim($_POST["region"] ?? "");

    if (
        $name === "" ||
        $contact === "" ||
        $address === "" ||
        $region === ""
    ) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO publishers
            (name, sales_contact, address, region)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $name,
            $contact,
            $address,
            $region
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to save publisher data.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Publisher | Digital Library</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #172033;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: #14213d;
            color: white;
            padding: 30px 20px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #aab8d0;
            font-size: 12px;
            margin-bottom: 40px;
        }

        .menu-label {
            color: #aab8d0;
            font-size: 11px;
            margin-bottom: 15px;
        }

        .menu-item {
            display: block;
            padding: 13px 14px;
            margin-bottom: 8px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .menu-item.active {
            background: #2563eb;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 25px;
            color: #aab8d0;
            font-size: 12px;
            line-height: 1.7;
        }

        .main {
            margin-left: 240px;
            padding: 35px;
        }

        .topbar {
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .topbar p {
            font-size: 14px;
            color: #64748b;
        }

        .breadcrumb {
            font-size: 13px;
            margin-bottom: 20px;
            color: #64748b;
        }

        .breadcrumb a {
            color: #2563eb;
            text-decoration: none;
        }

        .form-card {
            max-width: 850px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .form-header {
            padding: 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-header h2 {
            font-size: 19px;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
        }

        .form-body {
            padding: 25px;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 13px;
            border-radius: 7px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .form-group {
            margin-bottom: 5px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 9px;
        }

        .required {
            color: #dc2626;
        }

        input, textarea {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            background: white;
        }

        input:focus, textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #dbeafe;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .help-text {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 7px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #e2e8f0;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border: none;
            border-radius: 7px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .cancel-button {
            background: #f1f5f9;
            color: #334155;
        }

        .save-button {
            background: #2563eb;
            color: white;
        }

        .save-button:hover {
            background: #1d4ed8;
        }

        .footer {
            max-width: 850px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 850px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }

            .sidebar-footer {
                display: none;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<aside class="sidebar">
    <div class="brand">Digital Library</div>
    <div class="subtitle">Management System</div>

    <div class="menu-label">MAIN MENU</div>

    <a href="index.php" class="menu-item">
        &#9632; Dashboard
    </a>

    <a href="index.php" class="menu-item active">
        &#128218; Publishers & Suppliers
    </a>

    <div class="sidebar-footer">
        Individual C<br>
        Publisher & Supplier Module
    </div>
</aside>

<main class="main">

    <div class="breadcrumb">
        <a href="index.php">Dashboard</a> /
        Publishers & Suppliers / Add Publisher
    </div>

    <header class="topbar">
        <h1>Add Publisher</h1>
        <p>Register a new publisher or supplier in the digital library.</p>
    </header>

    <section class="form-card">

        <div class="form-header">
            <h2>Publisher Information</h2>
            <p>
                Enter the publisher's details below.
                All fields marked with an asterisk (*) are required.
            </p>
        </div>

        <div class="form-body">

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">
                            Publisher Name <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="e.g. PT Gramedia"
                            value="<?= htmlspecialchars($name) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="sales_contact">
                            Sales Contact <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="sales_contact"
                            name="sales_contact"
                            placeholder="Phone number or email"
                            value="<?= htmlspecialchars($contact) ?>"
                            required
                        >
                    </div>

                    <div class="form-group full">
                        <label for="address">
                            Address <span class="required">*</span>
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            placeholder="Enter the full publisher address"
                            required
                        ><?= htmlspecialchars($address) ?></textarea>
                    </div>

                    <div class="form-group full">
                        <label for="region">
                            Region of Origin <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="region"
                            name="region"
                            placeholder="e.g. Jakarta"
                            value="<?= htmlspecialchars($region) ?>"
                            required
                        >

                        <div class="help-text">
                            Enter the city or region where the publisher is located.
                        </div>
                    </div>

                </div>

                <div class="form-actions">
                    <a href="index.php" class="button cancel-button">
                        Cancel
                    </a>

                    <button type="submit" class="button save-button">
                        Save Publisher
                    </button>
                </div>

            </form>

        </div>
    </section>

    <div class="footer">
        Digital Library Management System &copy; 2026
    </div>

</main>

</body>
</html>
