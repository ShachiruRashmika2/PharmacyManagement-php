<?php
if (isset($_GET['id'])) {
    $quoteid= $_GET['id'];
   
} else {
    echo "No prescription ID provided.";
    exit();
}

require_once '../Database/db.php';
$stmt = $db->prepare("DELETE FROM quotes WHERE prescriptionID = $quoteid");
if ($stmt) {
    $result = $stmt->execute();
    if ($result) {
        echo "<script>alert('Quote deleted successfully'); window.location.href = '../UI/pharmAllQuates.php';</script>";
    } else {
        echo "<script>alert('Error deleting quote'); window.location.href = '../UI/pharmAllQuates.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Error preparing statement'); window.location.href = '../UI/pharmAllQuates.php';</script>";
}

?>