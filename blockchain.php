<?php
function calculateHash($staff_id, $timestamp, $previous_hash, $nonce) {
    $data = $staff_id . $timestamp . $previous_hash . $nonce;
    return hash('sha256', $data);
}

function mineBlock($staff_id, $timestamp, $previous_hash, $difficulty) {
    $nonce = 0;
    $prefix = str_repeat('0', $difficulty);

    while (true) {
        $hash = calculateHash($staff_id, $timestamp, $previous_hash, $nonce);
        if (substr($hash, 0, $difficulty) === $prefix) {
            return ['hash' => $hash, 'nonce' => $nonce];
        }
        $nonce++;
    }
}

function validateProofOfWork($hash, $difficulty) {
    $prefix = str_repeat('0', $difficulty);

    echo "<p>Validating Proof-of-Work:</p>";
    echo "<p>Difficulty: $difficulty (Prefix: $prefix)</p>";
    echo "<p>Hash to Validate: $hash</p>";

    if (substr($hash, 0, $difficulty) === $prefix) {
        echo "<p>Proof-of-Work is valid! Hash starts with '$prefix'.</p>";
        return true;
    } else {
        echo "<p>Proof-of-Work is invalid! Hash does not start with '$prefix'.</p>";
        return false;
    }
}

function validateBlockchain($conn) {
    $stmt = $conn->query("SELECT * FROM attendance ORDER BY id ASC");
    $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $previous_hash = '0'; // For genesis block
    $difficulty = 4;

    echo "<h2>Starting Blockchain Validation...</h2><br>";

    foreach ($blocks as $block) {
        echo "<hr>";
        echo "<h3>Validating Block ID: " . $block['id'] . "</h3>";

        // Step 1: Display block details
        echo "<p>Staff ID: " . $block['staff_id'] . "</p>";
        echo "<p>Timestamp: " . $block['timestamp'] . "</p>";
        echo "<p>Previous Hash (stored): " . $block['previous_hash'] . "</p>";
        echo "<p>Block Hash (stored): " . $block['block_hash'] . "</p>";
        echo "<p>Nonce: " . $block['nonce'] . "</p>";

        // Step 2: Recompute the block's hash
        echo "<p>Recomputing Hash...</p>";
        $recomputed_hash = calculateHash(
            $block['staff_id'],
            $block['timestamp'],
            $previous_hash,
            $block['nonce']
        );
        echo "<p>Recomputed Hash: " . $recomputed_hash . "</p>";

        // Step 3: Compare the stored hash with the recomputed hash
        if ($recomputed_hash !== $block['block_hash']) {
            echo "<p>ERROR: Hash mismatch! Validation failed at Block ID: " . $block['id'] . "</p>";
            return "Blockchain validation failed at Block ID: " . $block['id'];
        }
        echo "<p>Hash matches!</p>";

        // Step 4: Validate Proof-of-Work
        echo "<p>Validating Proof-of-Work for Block ID: " . $block['id'] . "</p>";
        if (!validateProofOfWork($block['block_hash'], $difficulty)) {
            echo "<p>ERROR: Invalid Proof-of-Work at Block ID: " . $block['id'] . "</p>";
            return "Invalid Proof-of-Work at Block ID: " . $block['id'];
        }

        // Step 5: Update the previous hash for the next block
        echo "<p>Previous hash updated for the next block.</p><br>";
        $previous_hash = $block['block_hash'];

        
    }

    echo "<h2>Blockchain validation completed successfully. All blocks are valid!</h2>";
    return "Blockchain is valid.";
}
?>



