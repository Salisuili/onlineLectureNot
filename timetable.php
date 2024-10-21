<?php
session_start();
require_once('database.php');

// Query to fetch faculties
$query = "SELECT faculty_id, faculty_name FROM faculties";

$result = $conn->query($query);
if (!$result) {
    die("Error: " . $conn->error);
}

$faculties = $result->fetch_all(MYSQLI_ASSOC);


// Include PHPMailer classes
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

// Fetch student data for notifications
$students_query = "SELECT * FROM students";
$students_result = $conn->query($students_query);
$students = [];
if ($students_result->num_rows > 0) {
    while ($row = $students_result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch courses data
$courses_query = "SELECT course_name FROM courses";
$courses_result = $conn->query($courses_query);
$courses = [];
if ($courses_result->num_rows > 0) {
    while ($row = $courses_result->fetch_assoc()) {
        $courses[] = $row['course_name'];
    }
}

// Fetch lecturers data
$lecturers_query = "SELECT name FROM lecturers";
$lecturers_result = $conn->query($lecturers_query);
$lecturers = [];
if ($lecturers_result->num_rows > 0) {
    while ($row = $lecturers_result->fetch_assoc()) {
        $lecturers[] = $row['name'];
    }
}


function sendEmailNotification($students, $course) {
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

        $mail->setFrom('', '');

        foreach ($students as $student) {
            $mail->addAddress($student['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Class Added: " . $course['course_name'];
            $mail->Body    = "Dear " . $student['first_name'] . ",<br><br>"
                . "A new class has been added to the timetable:<br><br>"
                . "Course: " . $course['course_name'] . "<br>"
                . "Lecturer: " . $course['lecturer_name'] . "<br>"
                . "Time: " . $course['start_time'] . " - " . $course['end_time'] . "<br>"
                . "Day: " . $course['day'] . "<br><br>"
                . "Best Regards,<br>Your University";

            $mail->send();
            $mail->clearAddresses();
        }

        echo 'Email notifications have been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function sendSMSNotification($students, $course) {
    foreach ($students as $student) {
        $curl = curl_init();
        $data = array(
            "api_key" => "",
            "to" => $student['phone'],
            "from" => "IAIICT",
            "sms" => "New class added: " . $course['course_name'] . " by " . $course['lecturer_name'] . " on " . $course['day'] . " from " . $course['start_time'] . " to " . $course['end_time'],
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

if (isset($_POST['submit'])) {
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $lecturer_name = mysqli_real_escape_string($conn, $_POST['lecturer_name']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $send_email = isset($_POST['send_email']);
    $send_sms = isset($_POST['send_sms']);

    $query = "INSERT INTO timetable (course_name, lecturer_name, day_of_week, start_time, end_time, location) VALUES ('$course_name', '$lecturer_name', '$day', '$start_time', '$end_time', '$location')";
    if ($conn->query($query)) {
        $course = [
            'course_name' => $course_name,
            'lecturer_name' => $lecturer_name,
            'day' => $day,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];

        if ($send_email) {
            sendEmailNotification($students, $course);
        }
        if ($send_sms) {
            sendSMSNotification($students, $course);
        }

        $_SESSION['login_status'] = 'Class added successfully!';
        header('Location: timetable.php');
    } else {
        echo "Error: " . $conn->error;
        $_SESSION['login_status'] = 'Error: ' . $conn->error;
        header('Location: timetable.php');
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
    <style type="text/css">
        .delete-button {
            background-color: #e74c3c; /* Red color */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 12px;
            border-radius: 3px;
        }

        .delete-button:hover {
            background-color: #c0392b; /* Darker red */
        }

        .edit-button {
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 12px;
            border-radius: 3px;
        }

        .edit-button:hover {
            background-color: darkgreen;
        }

        .delete-button {
            background-color: #e74c3c; /* Red color */
            color: white;
            border: none;
            padding: 5px 10px;
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

        <section class="timetable-section">
    <button class="add-class-button" id="addClassBtn">Add Class</button>
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

            // Define the time slots that match the table header
            $timeSlots = [
                ['start' => 8, 'end' => 10],
                ['start' => 10, 'end' => 12],
                ['start' => 14, 'end' => 16], // 2pm - 4pm
                ['start' => 16, 'end' => 18]  // 4pm - 6pm
            ];

            foreach ($days as $day) {
                echo "<tr>";
                echo "<td>$day</td>";

                // Loop through each time slot
                foreach ($timeSlots as $slot) {
                    echo "<td>";

                    if (isset($timetable[$day])) {
                        $found = false;

                        foreach ($timetable[$day] as $class) {
                            $class_start_hour = (int) date('G', strtotime($class['start_time']));
                            $class_end_hour = (int) date('G', strtotime($class['end_time']));

                            // Check if the class start time falls within the current time slot
                            if ($class_start_hour >= $slot['start'] && $class_start_hour < $slot['end']) {
                                echo "{$class['course_name']}<br>({$class['lecturer_name']})<br>{$class['location']}<br><br>";

                                // Add Edit button
                                echo "<button class='edit-button' style='background-color: green;' onclick=\"openEditModal(" . htmlspecialchars(json_encode($class)) . ")\">Edit</button>";

                                // Add Delete button
                                echo "<form action='delete_class.php' method='POST' style='display:inline;'>";
                                echo "<input type='hidden' name='class_id' value='{$class['timetable_id']}'>";
                                echo "<button type='submit' class='delete-button'>Delete</button>";
                                echo "</form>";

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



       <!-- The Modal -->
       <div id="addClassModal" class="modal">
    <div class="modal-content">
        <span class="close-button" id="closeModalBtn">&times;</span>
        <h2>Add a Class</h2>
        <form action="timetable.php" method="POST" class="modal-form">
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <select id="course_name" name="course_name" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="faculty">Faculty:</label>
                <select id="faculty" name="faculty" required>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?= htmlspecialchars($faculty['faculty_id']) ?>">
                            <?= htmlspecialchars($faculty['faculty_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <!-- Options will be populated dynamically based on selected faculty -->
                </select>
            </div>

            <div class="form-group">
                <label for="lecturer_name">Lecturer Name:</label>
                <select id="lecturer_name" name="lecturer_name" required>
                    <?php foreach ($lecturers as $lecturer): ?>
                        <option value="<?= htmlspecialchars($lecturer) ?>"><?= htmlspecialchars($lecturer) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="day">Day:</label>
                <select id="day" name="day" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
            </div>

            <!-- Start Time Dropdown -->
            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <select id="start_time" name="start_time" required>
                    <option value="08:00:00">8:00 AM - 10:00 AM</option>
                    <option value="10:00:00">10:00 AM - 12:00 PM</option>
                    <option value="14:00:00">2:00 PM - 4:00 PM</option>
                    <option value="16:00:00">4:00 PM - 6:00 PM</option>
                </select>
            </div>

            <!-- End Time Dropdown -->
            <div class="form-group">
                <label for="end_time">End Time:</label>
                <select id="end_time" name="end_time" required>
                    <option value="10:00:00">10:00 AM</option>
                    <option value="12:00:00">12:00 PM</option>
                    <option value="16:00:00">4:00 PM</option>
                    <option value="18:00:00">6:00 PM</option>
                </select>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="send_email">Send Email Notification:</label>
                <input type="checkbox" id="send_email" name="send_email">
            </div>

            <div class="form-group">
                <label for="send_sms">Send SMS Notification:</label>
                <input type="checkbox" id="send_sms" name="send_sms">
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="submit-button">Add Class</button>
            </div>
        </form>
    </div>
</div>


<style>
.modal-content {
    max-width: 1000px; /* Increased width */
    margin: auto;
    padding: 20px;
}

.modal-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%; /* Set full width */
}

.form-group {
    flex: 0 0 48%; /* Keeps fields side by side */
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input, select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.submit-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.submit-button:hover {
    background-color: #45a049;
}
</style>

        <!-- The edit Modal -->
        
        
            <div id="editClassModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" id="closeEditModalBtn">&times;</span>
                    <h2>Edit Class</h2>
                    <form id="editClassForm" action="edit_class.php" method="POST">
                        <input type="hidden" id="edit_class_id" name="class_id">

                        <div class="form-group">
                            <label for="edit_course_name">Course Name:</label>
                            <select id="edit_course_name" name="course_name" required>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= htmlspecialchars($course) ?>"><?= htmlspecialchars($course) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_lecturer_name">Lecturer Name:</label>
                            <select id="edit_lecturer_name" name="lecturer_name" required>
                                <?php foreach ($lecturers as $lecturer): ?>
                                    <option value="<?= htmlspecialchars($lecturer) ?>"><?= htmlspecialchars($lecturer) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_day">Day:</label>
                            <select id="edit_day" name="day" required>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_start_time">Start Time:</label>
                            <input type="time" id="edit_start_time" name="start_time" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_end_time">End Time:</label>
                            <input type="time" id="edit_end_time" name="end_time" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_location">Location:</label>
                            <input type="text" id="edit_location" name="location" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="submit">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>


        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const facultySelect = document.getElementById('faculty');
            const departmentSelect = document.getElementById('department');

            facultySelect.addEventListener('change', function () {
                const facultyId = this.value;

                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                fetch('get_departments.php?faculty_id=' + facultyId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error:', data.error);
                            return;
                        }
                        data.forEach(department => {
                            const option = document.createElement('option');
                            option.value = department.department_id; // Ensure this matches the field name in your JSON
                            option.textContent = department.department_name; // Ensure this matches the field name in your JSON
                            departmentSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Fetch Error:', error));
            });
        });

        </script>


        <script>
            var modal = document.getElementById("addClassModal");
            var btn = document.getElementById("addClassBtn");
            var span = document.getElementById("closeModalBtn");

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
        <script>
            // Function to open the Edit Modal and populate the form with the selected class's data
                function openEditModal(classData) {
                    document.getElementById("edit_class_id").value = classData.timetable_id;
                    
                    // Set the selected value for course and lecturer dropdowns
                    document.getElementById("edit_course_name").value = classData.course_name;
                    document.getElementById("edit_lecturer_name").value = classData.lecturer_name;
                    
                    // Set other input values
                    document.getElementById("edit_day").value = classData.day_of_week;
                    document.getElementById("edit_start_time").value = classData.start_time;
                    document.getElementById("edit_end_time").value = classData.end_time;
                    document.getElementById("edit_location").value = classData.location;

                    // Show the modal
                    document.getElementById("editClassModal").style.display = "block";
                }

                // Function to close the Edit Modal
                document.getElementById("closeEditModalBtn").onclick = function() {
                    document.getElementById("editClassModal").style.display = "none";
                }

                // Close modal if clicked outside of the modal content
                window.onclick = function(event) {
                    if (event.target == document.getElementById("editClassModal")) {
                        document.getElementById("editClassModal").style.display = "none";
                    }
                }


        </script>
    </main>
</body>
</html>

