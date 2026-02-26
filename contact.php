<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = htmlspecialchars($_POST['full_name']);
    $email     = htmlspecialchars($_POST['email']);
    $company   = htmlspecialchars($_POST['company']);
    $phone     = htmlspecialchars($_POST['phone']);
    $message   = htmlspecialchars($_POST['message']);

    /* ===== EMAIL CONFIG ===== */

    $to = "shriharikasar1436@gmail.com";   // testing email
    $subject = "New Contact Request - NorthMark Solutions";

    $body = "
    New Contact Submission:

    Full Name: $full_name
    Email: $email
    Company: $company
    Phone: $phone

    Message:
    $message
    ";

    $headers = "From: connect@northmarksolutions.in\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($to, $subject, $body, $headers);


    /* ===== SAVE TO EXCEL (CSV FILE) ===== */

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