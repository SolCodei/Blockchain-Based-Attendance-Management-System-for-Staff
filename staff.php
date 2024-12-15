<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_staff') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $security_code = password_hash($_POST['security_code'], PASSWORD_DEFAULT);

        // Check if the email already exists
        $stmt_check_email = $conn->prepare("SELECT COUNT(*) FROM staff WHERE email = ?");
        $stmt_check_email->execute([$email]);
        $email_count = $stmt_check_email->fetchColumn();

        if ($email_count > 0) {
            echo "Error: Email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO staff (name, email, security_code) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $security_code]);

            // Redirect to avoid resubmission
            header("Location: staff.php");
            exit();
        }
    }
}

// Fetch staff data
$stmt = $conn->query("SELECT id, name, email FROM staff");
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch attendance data
$stmt_attendance = $conn->query("SELECT * FROM attendance ORDER BY id DESC");
$attendance = $stmt_attendance->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff and Attendance Management</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

    <!-- Custom CSS for styling -->
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            width: calc(100% - 22px);
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #0056b3;
        }
        table.dataTable {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            border-spacing: 0;
        }
        table.dataTable th, table.dataTable td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table.dataTable th {
            background-color: #007bff;
            color: white;
        }
        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }
        table.dataTable tfoot th {
            background-color: #007bff;
            color: white;
        }

        
    </style>
</head>
<body>

<div class="container">
    <h2>Staff Management</h2>

    <!-- Create Staff Section -->
    <div style="margin-bottom: 40px;">
        <h3>Create Staff</h3>
        <form method="POST" action="staff.php">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="security_code" placeholder="Security Code" required>
            <input type="hidden" name="action" value="add_staff">
            <button type="submit">Add Staff</button>
        </form>
    </div>

    <!-- Staff Table Section -->
    <div>
        <h3>Staff List</h3>
        <table id="staffTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                       
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Attendance Table Section -->
    <div style="margin-top: 40px;">
        <h3>Attendance Records</h3>
        <table id="attendanceTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Staff ID</th>
                    <th>Timestamp</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['staff_id'] ?></td>
                        <td><?= $row['timestamp'] ?></td>
                       
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Navigation Button -->

    <div style="margin-top: 40px;">
        <a href="attendance.php"><button>Take Attedance</button></a>
    </div>
    <div style="margin-top: 20px;">
        <a href="validate.php"><button>Validate Data</button></a>
    </div>
</div>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {
        $('#staffTable').DataTable();
        $('#attendanceTable').DataTable();
    });
</script>

</body>
</html>
