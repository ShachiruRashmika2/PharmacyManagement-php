<?php
if (isset($_GET['id'])) {
    $quoteid= $_GET['id'];
   $status = strtolower($_GET['status'] ?? 'pending'); 

   
} else {
    echo "No quote ID provided.";
    exit();
}

require_once '../Database/db.php';

$stmt = $db->prepare("UPDATE quotes SET status = ? WHERE quoteID = ?");
if ($stmt) {
    $stmt->bindValue(1, $status, SQLITE3_TEXT);
    $stmt->bindValue(2, $quoteid, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    if ($result) {
        echo "<script>alert('Quote status updated successfully'); window.location.href = '../UI/userQuotes.php';</script>";
    } else {
        echo "<script>alert('Error updating quote status'); window.location.href = '../UI/userQuotes.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Error preparing statement'); window.location.href = '../UI/userQuotes.php';</script>";
}
?>