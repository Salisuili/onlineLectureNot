<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
}

$message_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($message_id > 0) {
    $sql = "SELECT * FROM messages WHERE message_id = '$message_id'";
$result = $conn->query($sql);

$message_cards = "";

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
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
            <div id="comments{$message_id}" class="comments" style="display: none;">
                $comments_html
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

            <button type="submit">Post</button>
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

    function closeModal() {
        document.getElementById("postModal").style.display = "none";
    }
</script>
</body>
</html>
