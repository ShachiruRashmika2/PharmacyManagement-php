

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
   <form method="POST" action="../PHP/register.php">
    <h2>Register</h2>
    <input type="text" name="name" placeholder="Full Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="text" name="address" placeholder="Address" required><br><br>
    <input type="text" name="contactNumber" placeholder="Contact Number"><br><br>
    <input type="date" name="dob" placeholder="Date of Birth"><br><br>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
      <input type="hidden" name="userType" value="user"><br><br>
    <button type="submit">Register</button>
</form> 
</body>
</html>
