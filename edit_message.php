<?php
require_once('database.php');

// Include PHPMailer classes
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Function to sanitize input
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

// Function to send Email Notifications (add this function)
function sendEmailNotification($students, $title, $content) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Replace with your SMTP username
        $mail->Password = ''; // Replace with your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your mail', 'your name');

        foreach ($students as $student) {
            $mail->addAddress($student['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Updated Message: " . $title;
            $mail->Body    = "Dear " . $student['first_name'] . ",<br><br>"
                . "A message has been updated:<br><br>"
                . "<strong>Title:</strong> " . $title . "<br>"
                . "<strong>Message:</strong> " . $content . "<br><br>"
                . "Best Regards,<br>Your University";

            $mail->send();
            $mail->clearAddresses();
        }

        echo 'Email notifications have been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to send SMS Notifications (add this function)
function sendSMSNotification($students, $title, $content) {
    foreach ($students as $student) {
        $curl = curl_init();
        $data = array(
            "api_key" => " ",
            "to" => $student['phone'],
            "from" => "IAIICT",
            "sms" => "Updated Message: " . $title . " - " . substr($content, 0, 100),
            "type" => "plain",
            "channel" => "generic"
        );

        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://v3.api.termii.com/api/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $message_id = sanitizeInput($conn, $_POST['message_id']);
    $title = sanitizeInput($conn, $_POST['title']);
    $content = sanitizeInput($conn, $_POST['content']);
    $send_email = isset($_POST['send_email']);
    $send_sms = isset($_POST['send_sms']);

    // Check if data is received
    if (empty($message_id) || empty($title) || empty($content)) {
        echo "Please ensure all fields are filled.";
        exit();
    }

    // Update the message
    $updateMessageSql = "UPDATE `messages` SET `title`='$title', `message_content`='$content' WHERE `message_id`='$message_id'";

    if ($conn->query($updateMessageSql) === TRUE) {
        // Update the notification
        $updateNotificationSql = "UPDATE `notifications` SET `title`='$title', `message`='$content', `read_status`= 0 WHERE `message_id` = '$message_id'";

        if ($conn->query($updateNotificationSql) === TRUE) {
            // Fetch all students for notifications
            $students_query = "SELECT * FROM students";
            $students_result = $conn->query($students_query);
            $students = [];

            if ($students_result->num_rows > 0) {
                while ($row = $students_result->fetch_assoc()) {
                    $students[] = $row;
                }
            }

            // Send Email Notification
            if ($send_email) {
                sendEmailNotification($students, $title, $content);
            }

            // Send SMS Notification
            if ($send_sms) {
                sendSMSNotification($students, $title, $content);
            }

            header("Location: messages.php");
            exit();
        } else {
            echo "Error updating notification: " . $conn->error;
        }
    } else {
        echo "Error updating message: " . $conn->error;
    }
}
?>
