<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'vendor/autoload.php'; // if installed via composer
// If manual install, use this instead:
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/SMTP.php';

// Your Gmail (recipient)
$siteOwnersEmail = 'sugumaran1147@gmail.com';

if ($_POST) {

    $name    = trim(stripslashes($_POST['contactName']));
    $email   = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    $error = [];

    // Validation
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Please enter a valid email address.";
    }

    if (strlen($contact_message) < 15) {
        $error['message'] = "Please enter your message (min 15 characters).";
    }

    if ($subject == '') {
        $subject = "Contact Form Submission";
    }

    if (empty($error)) {
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $siteOwnersEmail; // Your Gmail
            $mail->Password   = 'YOUR_APP_PASSWORD'; // ⚠️ Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Sender & recipient
            $mail->setFrom($siteOwnersEmail, 'Website Contact Form');
            $mail->addAddress($siteOwnersEmail);  // Send to yourself
            $mail->addReplyTo($email, $name);     // Replies go to the visitor

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = "
                <strong>Email from:</strong> " . htmlspecialchars($name) . "<br>
                <strong>Email address:</strong> " . htmlspecialchars($email) . "<br><br>
                <strong>Message:</strong><br>" . nl2br(htmlspecialchars($contact_message)) . "<br><br>
                ----- <br>This email was sent from your site's contact form.
            ";

            $mail->send();
            echo "OK"; // Success response
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        // Show validation errors
        $response  = isset($error['name']) ? $error['name'] . "<br>" : '';
        $response .= isset($error['email']) ? $error['email'] . "<br>" : '';
        $response .= isset($error['message']) ? $error['message'] . "<br>" : '';
        echo $response;
    }
}
?>
