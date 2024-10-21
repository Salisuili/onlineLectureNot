<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch timetable data
$query = "SELECT * FROM timetable ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), start_time";
$result = $conn->query($query);

$timetable = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timetable[$row['day_of_week']][] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Table</title>
    <link rel="stylesheet" href="styles/css/timetable-style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <?php include 'navbar.php'; ?>

        <section class="timetable-section">
    <h1>Class Timetable</h1>
    <table class="timetable-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>8am - 10am</th>
                <th>10am - 12pm</th>
                <th>2pm - 4pm</th>
                <th>4pm - 6pm</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            foreach ($days as $day) {
                echo "<tr>";
                echo "<td>$day</td>";

                $time_slots = [
                    ['start' => '08:00:00', 'end' => '10:00:00'],
                    ['start' => '10:00:00', 'end' => '12:00:00'],
                    ['start' => '14:00:00', 'end' => '16:00:00'],
                    ['start' => '16:00:00', 'end' => '18:00:00'],
                ];

                foreach ($time_slots as $slot) {
                    echo "<td>";

                    if (isset($timetable[$day])) {
                        $found = false;

                        foreach ($timetable[$day] as $class) {
                            $class_start_time = strtotime($class['start_time']);
                            $class_end_time = strtotime($class['end_time']);
                            $slot_start_time = strtotime($slot['start']);
                            $slot_end_time = strtotime($slot['end']);

                            // Check if the class falls within the time slot
                            if ($class_start_time == $slot_start_time && $class_end_time == $slot_end_time) {
                                echo "{$class['course_name']}<br>({$class['lecturer_name']})<br>{$class['location']}<br><br>";
                                $found = true;
                            }
                        }

                        if (!$found) {
                            echo "No class";
                        }
                    } else {
                        echo "No class";
                    }

                    echo "</td>";
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</section>


    </main>
</body>
</html>

