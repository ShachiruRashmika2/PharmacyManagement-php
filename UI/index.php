<?php
session_start();
$status = $_SESSION['status'] ?? "";
unset($_SESSION['status']);

if ($status === "success") {
    echo "<script>alert('✅ Registration successful!'); window.location.href = '../UI/index.php';</script>";
    exit();
} elseif ($status === "error") {
    echo "<script>
   
       alert('❌ Registration failed!');
   
    </script>";
}


require_once '../Database/db.php'; 
$error='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['userName']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE userName = ?");
    if (!$stmt) {
        die("Database error: " . $db->lastErrorMsg());
    }

    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['userType'] = $user['userType'];

        if ($user['userType'] === 'pharmacy') {
            header("Location: pahrmacyDashboard.php");
        } if($user['userType']=='user') {
            header("Location: userDashboard.php");
        }
        exit;
    } else {
        $error = "Invalid username or password.";
     echo "<script>alert('❌ Registration Error! $error'); window.location.href = '../UI/index.php';</script>";
        exit;
    }
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="index.php">
        <input type="text" name="userName" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="user_registerUI.php">Register User</a><br/><a href="pahrmacy_registerUI.php">Register Pharmacy</a></p>
</body>
</html>