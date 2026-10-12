
<?php
require_once "connection.php";

$sql = "SELECT * FROM publishers ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$total = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Publisher Management | Digital Library</title>

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
        }

        .sidebar {
            position: fixed;
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

        .brand-subtitle {
            font-size: 12px;
            color: #aab8d0;
            margin-bottom: 40px;
        }

        .menu-label {
            font-size: 11px;
            color: #aab8d0;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .menu-item {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 14px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .menu-item.active {
            background: #2563eb;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 25px;
            left: 20px;
            color: #aab8d0;
            font-size: 12px;
        }

        .main {
            margin-left: 240px;
            padding: 35px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 35px;
        }

        .topbar h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .topbar p {
            color: #64748b;
            font-size: 14px;
        }

        .admin-label {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
        }

        .welcome {
            background: linear-gradient(120deg, #1d4ed8, #3b82f6);
            color: white;
            padding: 28px;
            border-radius: 14px;
            margin-bottom: 25px;
        }

        .welcome h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .welcome p {
            font-size: 14px;
            line-height: 1.6;
            color: #eaf1ff;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .stat-card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .stat-card h2 {
            font-size: 30px;
            color: #1d4ed8;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 23px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h2 {
            font-size: 18px;
            margin-bottom: 7px;
        }

        .card-header p {
            color: #64748b;
            font-size: 13px;
        }

        .add-button {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 11px 15px;
            border-radius: 7px;
            font-size: 13px;
            white-space: nowrap;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th, td {
            padding: 17px 15px;
            text-align: left;
            border-bottom: 1px solid #edf0f5;
        }

        td {
            font-size: 13px;
            line-height: 1.5;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .publisher-name {
            font-weight: bold;
            color: #172033;
        }

        .region-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 11px;
        }

        .edit-button {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 5px;
            margin-right: 4px;
        }

        .delete-button {
            background: #fee2e2;
            color: #b91c1c;
            padding: 8px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #64748b;
        }

        .footer {
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

            .stats {
                grid-template-columns: 1fr;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .card-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<aside class="sidebar">
    <div class="brand">Digital Library</div>
    <div class="brand-subtitle">Management System</div>

    <div class="menu-label">MAIN MENU</div>

    <a href="index.php" class="menu-item active">
        &#9632; Dashboard
    </a>

    <a href="index.php" class="menu-item">
        &#128218; Publishers & Suppliers
    </a>

    <div class="sidebar-footer">
        Individual C<br>
        Publisher & Supplier Module
    </div>
</aside>

<main class="main">

    <header class="topbar">
        <div>
            <h1>Publisher Management</h1>
            <p>Manage publisher and supplier information.</p>
        </div>

        <div class="admin-label">
            Administrator
        </div>
    </header>

    <section class="welcome">
        <h2>Welcome to Digital Library</h2>
        <p>
            Manage publisher information, sales contacts, addresses,
            and regions of origin in one place.
        </p>
    </section>

    <section class="stats">
        <div class="stat-card">
            <p>Total Publishers & Suppliers</p>
            <h2><?= $total ?></h2>
        </div>

        <div class="stat-card">
            <p>Database Connection</p>
            <h2 style="font-size: 20px; color: #15803d;">
                Connected
            </h2>
        </div>
    </section>

    <section class="content-card">

        <div class="card-header">
            <div>
                <h2>Publisher & Supplier List</h2>
                <p>View and manage registered publisher records.</p>
            </div>

            <a href="create.php" class="add-button">
                + Add Publisher
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Publisher Name</th>
                        <th>Sales Contact</th>
                        <th>Address</th>
                        <th>Region</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result->num_rows > 0): ?>

                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int) $row["id"] ?></td>

                        <td class="publisher-name">
                            <?= htmlspecialchars($row["name"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["sales_contact"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["address"]) ?>
                        </td>

                        <td>
                            <span class="region-badge">
                                <?= htmlspecialchars($row["region"]) ?>
                            </span>
                        </td>

                        <td>
                            <a
                                class="edit-button"
                                href="edit.php?id=<?= (int) $row['id'] ?>"
                            >Edit</a>

                            <form
                                action="delete.php"
                                method="POST"
                                style="display:inline"
                                onsubmit="return confirm('Delete this publisher?')"
                            >
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $row['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete-button"
                                >Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty">
                            No publisher or supplier data found.
                            Click Add Publisher to create a record.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="footer">
        Digital Library Management System &copy; 2026
    </div>

</main>

</body>
</html>

<?php
$result->free();
$conn->close();
?>
