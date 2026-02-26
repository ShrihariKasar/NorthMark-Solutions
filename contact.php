<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* ==============================
       0️⃣ HONEYPOT (SPAM PROTECTION)
    ============================== */
    if (!empty($_POST['website'])) {
        exit(); // Bot detected
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

    // Basic validation
    if (empty($full_name) || empty($email) || empty($message)) {
        die("Required fields missing.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    /* ==============================
       2️⃣ SEND EMAIL
    ============================== */

    $to = "connect@northmarksolutions.in";
    $subject = "New Contact Request - NorthMark Solutions";

    $body = "New Contact Submission:\n\n";
    $body .= "Name: $full_name\n";
    $body .= "Email: $email\n";
    $body .= "Company: $company\n";
    $body .= "Phone: $phone\n\n";
    $body .= "Message:\n$message\n";
    $body .= "\nSubmitted At: $date";

    $headers = "From: NorthMark Solutions <no-reply@northmarksolutions.in>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $mail_sent = mail($to, $subject, $body, $headers);

    /* ==============================
       3️⃣ APPEND TO CSV FILE
    ============================== */

    $file = __DIR__ . "/contact_submissions.csv";
    $file_exists = file_exists($file);

    $fp = fopen($file, "a");

    if ($fp) {

        // Lock file while writing (prevents corruption)
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