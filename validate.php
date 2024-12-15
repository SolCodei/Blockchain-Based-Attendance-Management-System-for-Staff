<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        h2, h3, p {
            margin-bottom: 10px;
        }
        hr {
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color:rgb(27, 123, 197);
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    
    include 'db.php';
    include 'blockchain.php';

    // Set timezone to Kuala Lumpur
    date_default_timezone_set('Asia/Kuala_Lumpur');

    // Validate the blockchain
    echo validateBlockchain($conn);
    ?>
    <br>
    <a href="staff.php" class="btn-back">Back to Staff Management</a>
</div>

</body>
</html>
