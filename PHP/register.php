<?php
session_start();
require_once '../Database/db.php';

 if($_SERVER['REQUEST_METHOD'] === 'POST') 
    $userName =$_POST['username'];
    $name= $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contactNumber'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];  

 
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (userName,name,email,address,DoB,contactNumber ,password, userType) VALUES (?, ?, ?,?,?, ?, ?, ?)");    
    $stmt->bindValue(1, $userName, SQLITE3_TEXT);
    $stmt->bindValue(2, $name, SQLITE3_TEXT);
    $stmt->bindValue(3, $email, SQLITE3_TEXT);
    $stmt->bindValue(4, $address, SQLITE3_TEXT);
    $stmt->bindValue(5, $dob, SQLITE3_TEXT);
    $stmt->bindValue(6, $contactNumber, SQLITE3_TEXT);
    $stmt->bindValue(7, $hashed_password, SQLITE3_TEXT);
    $stmt->bindValue(8, $userType, SQLITE3_TEXT);

    if ($stmt->execute()) {
       $_SESSION['status'] = "success";

        header("Location: ../UI/index.php");
        exit();
    } else {
        $_SESSION['status'] = "error";
        echo "❌ Registration failed. Please try again. $userName";
        header("Location: ../UI/index.php");
        exit();
    }

?>




