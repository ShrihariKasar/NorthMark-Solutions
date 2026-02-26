<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';
require __DIR__ . '/phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* ==============================
       0️⃣ HONEYPOT (SPAM PROTECTION)
    ============================== */
    if (!empty($_POST['website'])) {
        exit();
    }

    /* ==============================
       1️⃣ SANITIZE + VALIDATE INPUT
    ============================== */

    function clean($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    $full_name = clean($_POST['full_name'] ?? '');
    $email     = clean($_POST['email'] ?? '');
    $company   = clean($_POST['company'] ?? '');
    $phone     = clean($_POST['phone'] ?? '');
    $message   = clean($_POST['message'] ?? '');
    $date      = date("Y-m-d H:i:s");

    if (empty($full_name) || empty($email) || empty($message)) {
        die("Required fields missing.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    /* ==============================
       2️⃣ SEND EMAIL VIA SMTP
    ============================== */

    $body = "
New Contact Submission

Name: $full_name
Email: $email
Company: $company
Phone: $phone

Message:
$message

Submitted At: $date
";

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'connect@northmarksolutions.in';
        $mail->Password   = 'YOUR_CONNECT_EMAIL_PASSWORD'; // 🔴 PUT REAL PASSWORD
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Send from your main mailbox
        $mail->setFrom('connect@northmarksolutions.in', 'NorthMark Solutions');

        // Receive in same inbox
        $mail->addAddress('connect@northmarksolutions.in');

        // When you click Reply, it goes to customer
        $mail->addReplyTo($email, $full_name);

        $mail->Subject = 'New Contact Request - NorthMark Solutions';
        $mail->Body    = $body;

        $mail->send();

    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }

    /* ==============================
       3️⃣ APPEND TO CSV FILE
    ============================== */

    $file = __DIR__ . "/contact_submissions.csv";
    $file_exists = file_exists($file);

    $fp = fopen($file, "a");

    if ($fp) {

        flock($fp, LOCK_EX);

        if (!$file_exists) {
            fputcsv($fp, [
                "Full Name",
                "Email",
                "Company",
                "Phone",
                "Message",
                "Submitted At"
            ]);
        }

        fputcsv($fp, [
            $full_name,
            $email,
            $company,
            $phone,
            $message,
            $date
        ]);

        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /* ==============================
       4️⃣ REDIRECT
    ============================== */

    header("Location: thankyou.html");
    exit();
}
?>