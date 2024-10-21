<?php
include 'database.php';
session_start();

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

$user_id = $_SESSION['user_id'];

// Function to send Email Notifications
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

        $mail->setFrom('@gmail.com', 'name');

        foreach ($students as $student) {
            $mail->addAddress($student['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Message: " . $title;
            $mail->Body    = "Dear " . $student['first_name'] . ",<br><br>"
                . "A new message has been posted:<br><br>"
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

// Function to send SMS Notifications
function sendSMSNotification($students, $title, $content) {
    foreach ($students as $student) {
        $curl = curl_init();
        $data = array(
            "api_key" => "",
            "to" => $student['phone'],
            "from" => "IAIICT",
            "sms" => "New Message Posted: " . $title . " - " . substr($content, 0, 100), // Truncate content for SMS length
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
    $title = sanitizeInput($conn, $_POST['title']);
    $content = sanitizeInput($conn, $_POST['content']);
    $send_email = isset($_POST['send_email']);
    $send_sms = isset($_POST['send_sms']);

    // Insert message into the database
    $insertMessageSql = "INSERT INTO messages (title, message_content, created_at) VALUES ('$title', '$content', NOW())";

    if ($conn->query($insertMessageSql) === TRUE) {
        $lastInsertedId = $conn->insert_id;

        $insertNotificationSql = "INSERT INTO notifications (message_id, title, message, notification_type, created_at, read_status) 
                                  VALUES ($lastInsertedId, '$title', '$content', 'general', NOW(), 0)";

        if ($conn->query($insertNotificationSql) === TRUE) {
            // Fetch all students to send notifications
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
            echo "Error adding notification: " . $conn->error;
        }
    } else {
        echo "Error adding message: " . $conn->error;
    }
}

$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);

$message_cards = ""; // Initialize an empty string to store all message cards

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $message_id = $row['message_id'];
        $message_title = ucfirst(htmlspecialchars($row['title']));
        $message_content = htmlspecialchars($row['message_content']);
        $message_created_at = date("F j, Y, g:i a", strtotime($row['created_at']));

        // Fetch comments for this message
        $comment_sql = "SELECT * FROM comments WHERE message_id = $message_id ORDER BY timestamp DESC";
        $comment_result = $conn->query($comment_sql);

        $comments_html = ""; // Initialize comments section

        if ($comment_result && $comment_result->num_rows > 0) {
            while ($comment_row = $comment_result->fetch_assoc()) {
                $comment_content = htmlspecialchars($comment_row['comment_content']);
                $comment_timestamp = date("F j, Y, g:i a", strtotime($comment_row['timestamp']));

                $comments_html .= <<<HTML
                <div class="comment">
                    <p><strong>Comment:</strong> $comment_content</p>
                    <span class="comment-meta">Posted at $comment_timestamp</span>
                </div>
HTML;
            }
        } else {
            $comments_html = "<p>No comments yet. Be the first to comment!</p>";
        }

        // Append each message card with comments and a form to add a new comment
        $message_cards .= <<<HTML
        <article class="message-card">
            <div class="message-header">
                <h2>{$message_title}</h2>
                <span class="message-meta">Posted at {$message_created_at}</span>
            </div> 
            <p>{$message_content}</p>
            <button class="toggle-comments" onclick="toggleComments('comments{$message_id}')">Comments</button>
            <button class="edit-button toggle-comments" onclick="openEditModal('$message_id', '$message_title', '$message_content')">Edit</button>
            <form action='delete_message.php' method='POST' style='display:inline;'>
                <input type="hidden" name="message_id" value="{$message_id}" >
                <button type='submit' class='delete-button'>Delete</button>
            </form>

            <div id="comments{$message_id}" class="comments" style="display: none;">
                $comments_html
                <form action="add_comment.php" method="POST" class="comment-form">
                    <input type="hidden" name="message_id" value="{$message_id}">
                    <textarea name="comment_content" rows="2" placeholder="Add a comment..." required></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </article> 
HTML;
    }
} else {
    // Handle case where no messages are found
    $message_cards = "<p>No messages found.</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="styles/css/message-style.css">
</head>
<body>
<style>
    .delete-button {
            background-color: #e74c3c; /* Red color */
            color: white;
            border: none;
            padding: 10px 10px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 12px;
            border-radius: 3px;
        }

        .delete-button:hover {
            background-color: #c0392b; /* Darker red */
        }
</style>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<main class="main-content">
    <?php include 'navbar.php'; ?>
    <section class="messages-section" style="width:80%;">
        <div class="section-header">
            <h1>Lecturer's Messages</h1>
            <button class="post-message-button" onclick="openModal()">Post Message</button>
        </div>
         
        <!-- Display all message cards -->
        <?php echo $message_cards; ?>
        
    </section>
</main>

<!-- Modal for posting messages -->
<div id="postModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Post a Message</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="message-title">Title</label>
            <input type="text" id="message-title" name="title" required>

            <label for="message-content">Content</label>
            <textarea id="message-content" name="content" rows="4" required></textarea>

            <label for="send_email">Send Email Notification:</label>
            <input type="checkbox" id="send_email" name="send_email">

            <label for="send_sms">Send SMS Notification:</label>
            <input type="checkbox" id="send_sms" name="send_sms">

            <button type="submit">Post</button>
        </form>
    </div>
</div>


<!-- Modal for editing messages -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Edit Message</h2>
        <form method="post" action="edit_message.php">
            <input type="hidden" id="edit_message_id" name="message_id">
            <label for="edit_message_title">Title</label>
            <input type="text" id="edit_message_title" name="title" required>

            <label for="edit_message_content">Content</label>
            <textarea id="edit_message_content" name="content" rows="4" required></textarea>

            <label for="send_email">Send Email Notification:</label>
            <input type="checkbox" id="send_email" name="send_email">

            <label for="send_sms">Send SMS Notification:</label>
            <input type="checkbox" id="send_sms" name="send_sms">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function toggleComments(id) {
        var comments = document.getElementById(id);
        if (comments.style.display === "none" || comments.style.display === "") {
            comments.style.display = "block";
        } else {
            comments.style.display = "none";
        }
    }

    function openModal() {
        document.getElementById("postModal").style.display = "block";
    }
    function openEditModal(messageId, title, content) {
        document.getElementById("edit_message_id").value = messageId;
        document.getElementById("edit_message_title").value = title;
        document.getElementById("edit_message_content").value = content;
        document.getElementById("editModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("postModal").style.display = "none";
        document.getElementById("editModal").style.display = "none";
    }
</script>
</body>
</html>
