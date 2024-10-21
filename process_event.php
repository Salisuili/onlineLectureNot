<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $event = $_POST['event'];
    $date = $_POST['date'];
    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT INTO events (title, `event`, `date`, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $event, $date, $created_at);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event added successful!";
        header("Location: events.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
        header("Location: events.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
