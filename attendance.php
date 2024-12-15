<?php
include 'db.php';
include 'blockchain.php';


date_default_timezone_set('Asia/Kuala_Lumpur');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_attendance') {
    $staff_id = (int) $_POST['staff_id'];
    $security_code = $_POST['security_code'];


    $stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");
    $stmt->execute([$staff_id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($staff && password_verify($security_code, $staff['security_code'])) {
        $last_block = $conn->query("SELECT * FROM attendance ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $previous_hash = $last_block ? $last_block['block_hash'] : '0';

        $timestamp = date('Y-m-d H:i:s');
        $difficulty = 4;

        $mined_data = mineBlock($staff_id, $timestamp, $previous_hash, $difficulty);

        $stmt = $conn->prepare("INSERT INTO attendance (staff_id, timestamp, block_hash, previous_hash, nonce) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$staff_id, $timestamp, $mined_data['hash'], $previous_hash, $mined_data['nonce']]);

        echo "Attendance marked! Block Hash: " . $mined_data['hash'];
    } else {
        echo "Invalid Security Code or Staff ID.";
    }
}
?>

<!-- Attendance Form -->
<div class="container">
    <h2>Mark Attendance</h2>
    <form method="POST" action="attendance.php">
        <div class="form-group">
            <label for="staff_id">Staff ID</label>
            <input type="number" id="staff_id" name="staff_id" class="form-control" placeholder="Staff ID" required>
        </div>
        <div class="form-group">
            <label for="security_code">Security Code</label>
            <input type="password" id="security_code" name="security_code" class="form-control" placeholder="Security Code" required>
        </div>
        <input type="hidden" name="action" value="mark_attendance">
        <button type="submit" class="btn btn-primary">Mark Attendance</button>
    </form>

    <!-- Navigate to Staff Management -->
    <div style="margin-top: 20px;">
        <a href="staff.php"><button type="button" class="btn btn-secondary">Manage Staff</button></a>
    </div>
</div>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    .container {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

