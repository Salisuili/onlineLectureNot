<?php
session_start();

include 'database.php';
if(!isset($_SESSION['user_id'])){
    header('location: Login.php');
    exit;
}
 if (isset($_SESSION['username']) && $_SESSION['username'] != 'admin'){
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM `students` WHERE `student_id` = $user_id";
            $result = $conn->query($query);

            if($result->num_rows > 0){
                $user = $result->fetch_assoc();
            } else {
                echo "User not found";
            }
        }


// Fetch events from the database
$events = [];
$sql = "SELECT title, `date`, `event`, created_at FROM events";
$result = $conn->query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $events[] = [
            'title' => htmlspecialchars($row['title']),
            'event' => htmlspecialchars($row['event']),
            'date' => htmlspecialchars($row['date'])
        ];
    }
} else {
    echo "No Events found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Online Lecture Notification</title>
    <link rel="stylesheet" href="outstyles/styles.css">
    <link rel="stylesheet" href="styles/css/fullcalendar.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Add Chart.js library -->
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'navbar.php'; ?>
        <div class="container">
            <h3 class="my-4">Welcome <?php echo ucfirst(htmlspecialchars($user['username'])); ?></h3>
            <div>
                <div id="calendar" style="width: 50%; float: left; background-color: lightcyan; color: darkcyan;"></div>
            
                <div style="width: 40%; float: right;">
                    <div>
                    <h3 style="background-color: lightcyan; color: darkcyan;">Message</h3>
                    <?php
                    $message = "";
                    $sql = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 2";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $message_title = htmlspecialchars($row['title']);
                        $message_content = htmlspecialchars($row['message_content']);
                        $message .= <<<HTML
                            <h5>{$message_title}</h5>
                            <p>{$message_content}</p>
                            <p><a href="messages.php">Go to messages</a></p>
                        HTML;
                    } else {
                        echo "No Messages found.";
                    }
                    echo $message;
                    ?>
                </div>
                <div>
                    <h3 style="background-color: lightcyan; color: darkcyan;">Events</h3>
                    <?php
                    $event = "";
                    $sql = "SELECT * FROM events ORDER BY created_at DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $event_title = htmlspecialchars($row['title']);
                        $event_content = htmlspecialchars($row['event']);
                        $event .= <<<HTML
                            <h5>{$event_title}</h5>
                            <p>{$event_content}</p>
                            <p><a href="events.php">Go to events</a></p>
                        HTML;
                    } else {
                        echo "No Events found.";
                    }
                    echo $event;
                    ?>
                </div>
                    
                </div>
            </div>
       
        </div>
    </div>
    <script src="styles/js/jquery.min.js"></script>
    <script src="styles/js/moment.min.js"></script>
    <script src="styles/js/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function () {
            var events = <?php echo json_encode($events); ?>;
            var calendarEvents = events.map(event => ({
                title: event.title,
                start: event.date
            }));

            $('#calendar').fullCalendar({
                defaultView: 'month',
                editable: false,
                events: calendarEvents
            });
        });
    </script>


</body>
</html>
