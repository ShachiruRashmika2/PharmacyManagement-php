Pharmacy Prescription Quotation System
--------------------------------------

Description:
This PHP app helps generate prescription quotations. It uses PHPMailer to send emails and a PDF library to create printable quotations.

taken Time-"I spent roughly 10 hours completing this, all while studying for my semester final exams."



Setup and Run Instructions:

1.Save this Folder in XAMPP/htdocs Folder

2. Install Composer dependencies (PHPMailer and PDF library):
   Make sure Composer is installed on your system:
   https://getcomposer.org/download/

   Run:
   composer require phpmailer/phpmailer
   composer require tecnickcom/tcpdf
   (or you can choose fpdf by downloading manually)

3. If you do not want to use Composer, download libraries manually:

   - PHPMailer:
     https://github.com/PHPMailer/PHPMailer
     Extract and place the folder in your project, e.g., /phpmailer/

   - PDF Library:
     For TCPDF: https://tcpdf.org/downloads/
     For FPDF: http://www.fpdf.org/


4.Backend Steps
	1)  http://localhost:(port)/PHP-Test/Database/db.php
	2)  http://localhost:(port)/PHP-Test/Database/create_db.php


5. Open your browser and visit:
   http://localhost:(port)/PHP-Test/UI

(sample cridentials)
Pharmacy-
	username-rioPharma
	password-123456
User- 
	username-host
	password-123



6. The app should be running. You can add prescriptions, generate PDFs, and send emails.
// There was an authentication error in mailservice
	you can set your email creadentialas in ../PHP-Test/PHP/sendQuoteMail.php and check.

---Thank You----
--Shachiru Rashmika Wijegunarathna---
--sachiru@outlook.com--




---
