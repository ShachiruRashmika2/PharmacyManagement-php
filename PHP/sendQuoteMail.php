

<?php
session_start();
require_once '../Database/db.php';
require ('../vendor/setasign/fpdf/fpdf.php');
require('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$pharmacyID = $_SESSION['user_id'];






$quote = [
  'id'=> $_SESSION['quoteID'],
    'csv' => $_SESSION['csv'],
   
];

$stmt = $db->prepare("SELECT * FROM quotes WHERE quoteID = ?");
$stmt->bindValue(1, $quote['id'], SQLITE3_INTEGER);
$result = $stmt->execute();
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $quote['prescriptionID'] = $row['prescriptionID'];
 
    $quote['customerID'] = $row['customerID'];
    $quote['status'] = $row['status'];


    $stmt->close();
} else {
    http_response_code(404);
    echo 'Quote not found';
    exit();
}



$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bindValue(1, $quote['customerID'], SQLITE3_INTEGER);
$result = $stmt->execute();
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $quote['customerID'] = $row['id'];
    $quote['customerName'] = $row['name'];
    $quote['customerAddress'] = $row['address'];
    
    $stmt->close();
} else {
    http_response_code(404);
    echo 'Customer not found';
    exit();
}



$stmt = $db->prepare("SELECT name,address,contactNumber FROM users WHERE id = ?");
$stmt->bindValue(1, $pharmacyID, SQLITE3_INTEGER);
$result = $stmt->execute();
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $pharmacyName = $row['name'];
    $pharmacyAddress = $row['address'];
    $pharmacyContact = $row['contactNumber'];
    $stmt->close();
} else {
    http_response_code(404);
    echo 'Pharmacy not found';
    exit();
}


    if (empty($quote['csv'])) {
        http_response_code(400);
        echo 'CSV data is missing';
        exit();
    }

    $rows = array_map("str_getcsv", explode("\n", $quote['csv']));

 
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Image('../UI/Assets/18896636_v870-mynt-13.jpg', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
    $pdf->SetDrawColor(0, 128, 255); 
$pdf->SetLineWidth(1);
$margin = 5;
$pdf->Rect($margin, $margin, $pdf->GetPageWidth() - 2 * $margin, $pdf->GetPageHeight() - 2 * $margin);
$pdf->SetLineWidth(0);


$pdf->SetFont('Arial', 'B', 16); 

$pdf->SetY(30);
$pdf->Image('../UI/Assets/LogoAlpha.png', 80, 10, 50);
$pdf->Ln(8);
$pdf->SetFont('Arial', '', 12); 
$pdf->Cell(0, 10, 'Pharmacy - '. $pharmacyName, 0, 1, 'C');

$pdf->Ln(0);
$pdf->SetFont('Arial', '', 10); 
$pdf->Cell(0, 10,$pharmacyAddress, 0, 1, 'C'); 
$pdf->Ln(0);
$pdf->Cell(0, 10, 'Tp -'. $pharmacyContact, 0, 1, 'C'); 
$pdf->Ln(0);


$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'R'); 

$pdf->Cell(0, 10, 'To: ' .$quote['customerName'] , 0, 1, 'L');
$pdf->Cell(0, 10, $quote['customerAddress'] , 0, 1, 'L');



$pdf->Ln(5);






    $cellWidth = 60;
    $cellHeight = 10;

$header = array_shift($rows);

$pdf->SetFont('Arial', 'B', 12);
foreach ($header as $heading) {
    $pdf->Cell($cellWidth, $cellHeight, trim($heading, '"'), 1);
}
$pdf->Ln();

    $pdf->SetFont('Arial', '', 12);

    foreach ($rows as $row) {
        foreach ($row as $cell) {
            $pdf->Cell($cellWidth, $cellHeight, trim($cell, '"'), 1);
        }
        $pdf->Ln();
    }

    

   

    

$filePath = __DIR__ . '/../Database/Uploads/Quoutes/' . time() . '.pdf';

$pdf->Output('F', $filePath);
$pdf->Output('D', "Quotation_{$quote['customerName']}_{$quote['id']}.pdf");



$mail = new PHPMailer(true);

try {
   
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '######';
    $mail->Password   = '######';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

  
    $mail->setFrom('##', $pharmacyName);
    $mail->addAddress('##', $quote['customerName']);

    $mail->addAttachment($filePath, 'quotation.pdf');

    
    $mail->isHTML(true);
    $mail->Subject = 'Your Pharmacy Quotation';
    $mail->Body    = 'Dear ' . htmlspecialchars($quote['customerName']) . ',<br><br>Please find your quotation attached.<br><br>Thank you!';

    $mail->send();
    echo 'Email sent successfully!';

} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
} finally {

  
   
}



?>