<?php
require_once 'db.php';

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    userName TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    address TEXT NOT NULL,
    contactNumber TEXT ,
    DoB TEXT ,
    userType TEXT CHECK(userType IN ('user', 'admin','pharmacy')) NOT NULL DEFAULT 'user'
   
)");


$db->exec("CREATE TABLE IF NOT EXISTS prescription (
    prescriptionID INTEGER PRIMARY KEY AUTOINCREMENT,
    customerID INTEGER NOT NULL,
   pharmacyID INTEGER,
    note TEXT,
    deliveryAddress TEXT NOT NULL,
    deliveryTimeSlot TEXT NOT NULL,
    FOREIGN KEY (customerID) REFERENCES users(id),
    FOREIGN KEY (pharmacyID) REFERENCES users(id)
)
");
$db->exec("CREATE TABLE IF NOT EXISTS prescriptionImage (
    imageID INTEGER PRIMARY KEY AUTOINCREMENT,
    prescriptionID INTEGER NOT NULL,
    ImagePath TEXT NOT NULL,
    FOREIGN KEY (prescriptionID) REFERENCES Prescription(prescriptionID)
);
");

$db->exec("CREATE TABLE IF NOT EXISTS quotes (
    quoteID INTEGER PRIMARY KEY AUTOINCREMENT,
    prescriptionID INTEGER NOT NULL,
    pharmacyID INTEGER NOT NULL,
    customerID INTEGER NOT NULL,
  status TEXT CHECK(status IN ('pending', 'accepted', 'rejected'))  DEFAULT 'pending',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total REAL NOT NULL,
    csv TEXT,
    FOREIGN KEY (prescriptionID) REFERENCES prescription(prescriptionID),
    FOREIGN KEY (pharmacyID) REFERENCES users(id),
    FOREIGN KEY (customerID) REFERENCES users(id)
)");

$db->exec("CREATE TABLE IF NOT EXISTS quoteItems (
    quoteItemID INTEGER PRIMARY KEY AUTOINCREMENT,
    quoteID INTEGER NOT NULL,
    drugID TEXT NOT NULL,
    quantity INTEGER NOT NULL,
    
    FOREIGN KEY (drugID) REFERENCES drugs(drugID),
    FOREIGN KEY (quoteID) REFERENCES quotes(quoteID)
)");

$db->exec("CREATE TABLE IF NOT EXISTS drugs (
    drugID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    pharmacyID INTEGER NOT NULL,
    FOREIGN KEY (pharmacyID) REFERENCES users(id)
)");

$db->exec("CREATE TABLE IF NOT EXISTS orders (
    orderID INTEGER PRIMARY KEY AUTOINCREMENT,
    customerID INTEGER NOT NULL,
    pharmacyID INTEGER NOT NULL,
    quoteID INTEGER NOT NULL,
    total REAL NOT NULL,

    FOREIGN KEY (quoteID) REFERENCES quotes(quoteID),
    FOREIGN KEY (customerID) REFERENCES users(id),
    FOREIGN KEY (pharmacyID) REFERENCES users(id)
)");






echo "Database and tables created successfully.";

?>