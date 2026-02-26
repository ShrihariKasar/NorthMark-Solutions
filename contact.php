<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = htmlspecialchars($_POST['full_name']);
    $email     = htmlspecialchars($_POST['email']);
    $company   = htmlspecialchars($_POST['company']);
    $phone     = htmlspecialchars($_POST['phone']);
    $message   = htmlspecialchars($_POST['message']);

    /* ================= SMTP EMAIL CONFIG ================= */

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'connect@northmarksolutions.in';
        $mail->Password   = 'NorthMark@2026';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('connect@northmarksolutions.in', 'NorthMark Solutions');
        $mail->addAddress('shriharikasar1436@gmail.com');

        $mail->addReplyTo($email, $full_name);

        $mail->isHTML(false);
        $mail->Subject = 'New Contact Request - NorthMark Solutions';

        $mail->Body = "
New Contact Submission:

Full Name: $full_name
Email: $email
Company: $company
Phone: $phone

Message:
$message
";

        $mail->send();

    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
        exit;
    }

    /* ================= SAVE TO EXCEL (CSV) ================= */

    $file = 'contact_submissions.csv';

    $data = [
        date("Y-m-d H:i:s"),
        $full_name,
        $email,
        $company,
        $phone,
        $message
    ];

    $file_exists = file_exists($file);
    $fp = fopen($file, 'a');

    if (!$file_exists) {
        fputcsv($fp, ['Date', 'Full Name', 'Email', 'Company', 'Phone', 'Message']);
    }

    fputcsv($fp, $data);
    fclose($fp);

    echo "<script>
        alert('Thank you! Your request has been submitted.');
        window.location='contact.html';
    </script>";
}
?>