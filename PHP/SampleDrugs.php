<?php
// SampleDrugs.php

// Sample array of drugs
$drugs = [
    [
        'name' => 'Paracetamol',
        'description' => 'Used to treat pain and fever.',
        'price' => 2.50,
        'stock' => 100,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Amoxicillin',
        'description' => 'Antibiotic for bacterial infections.',
        'price' => 5.00,
        'stock' => 50,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Cetirizine',
        'description' => 'Relieves allergy symptoms.',
        'price' => 3.75,
        'stock' => 75,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Ibuprofen',
        'description' => 'Nonsteroidal anti-inflammatory drug for pain relief.',
        'price' => 4.20,
        'stock' => 60,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Metformin',
        'description' => 'Used to treat type 2 diabetes.',
        'price' => 6.00,
        'stock' => 40,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Aspirin',
        'description' => 'Used to reduce pain, fever, or inflammation.',
        'price' => 2.80,
        'stock' => 90,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Loratadine',
        'description' => 'Antihistamine for allergy relief.',
        'price' => 3.20,
        'stock' => 80,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Omeprazole',
        'description' => 'Used to treat acid reflux and ulcers.',
        'price' => 7.50,
        'stock' => 30,
        'pharmacyID' => 4
    ],
    [
        'name' => 'Azithromycin',
        'description' => 'Antibiotic for various infections.',
        'price' => 8.00,
        'stock' => 25,
        'pharmacyID' => 4
    ]
];

require_once '../Database/db.php';


foreach ($drugs as $drug) {
    $stmt = $db->prepare("INSERT INTO drugs (name, description, price, stock,pharmacyID) VALUES (?, ?, ?, ?,?)");
    $stmt->bindValue(1, $drug['name'], SQLITE3_TEXT);
    $stmt->bindValue(2, $drug['description'], SQLITE3_TEXT);
    $stmt->bindValue(3, $drug['price'], SQLITE3_FLOAT);
    $stmt->bindValue(4, $drug['stock'], SQLITE3_INTEGER);
    $stmt->bindValue(5, $drug['pharmacyID'], SQLITE3_INTEGER);
    
    if (!$stmt->execute()) {
        echo "Error inserting drug: " . $drug['name'] . "\n";
    }
}
echo "Sample drugs inserted successfully.\n";

$db->close();



?>