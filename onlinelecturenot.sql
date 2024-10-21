-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 01:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlinelecturenot`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `course_name` varchar(11) DEFAULT NULL,
  `assessment_type` varchar(50) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessment_id`, `course_name`, `assessment_type`, `title`, `description`, `total_marks`, `due_date`) VALUES
(7, 'Operating S', 'Assignment', 'Operating System Lab', 'Practice CMD command', 10, '2024-10-10');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_submissions`
--

CREATE TABLE `assessment_submissions` (
  `submission_id` int(11) NOT NULL,
  `assessment_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `submitted_date` date DEFAULT NULL,
  `marks_obtained` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `credit_unit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `description`, `department`, `credit_unit`) VALUES
(1, 'DCS105', 'Intro to Programming', 'An introduction to the basic concepts of programming using a modern programming language.', 'Computer Science', 3),
(2, 'DCS109', 'Web Application Engineering', 'Covers the principles and practices of web application development.', 'Computer Science', 3),
(3, 'DCS108', 'Operating System', 'Study of operating system concepts, design, and implementation.', 'Computer Science', 3),
(4, 'DCS111', 'Computer Organization', 'Examines the structure and behavior of the functional units of computers.', 'Computer Science', 2),
(5, 'DGN101', 'English', 'Covers the basics of English grammar, composition, and literature.', 'Computer Science', 2),
(6, 'MTH110', 'Mathematics', 'Introduction to fundamental concepts in mathematics.', 'Computer Science', 2),
(7, 'DCE108', 'Physics Electronics', 'Study of electronic principles and applications in physics.', 'Computer Science', 3),
(8, 'DCS103', 'Discrete Structure', 'Covers discrete mathematical structures and their applications in computer science.', 'Computer Science', 2),
(9, 'STA107', 'Statistics', 'Introduction to statistical methods and applications.', 'Computer Science', 2),
(10, 'CSE101', 'Data Structures', 'Introduction to data structures and their applications.', 'Computer Science', 3),
(11, 'CSE102', 'Algorithms', 'Design and analysis of algorithms.', 'Computer Science', 3),
(12, 'CSE103', 'Database Systems', 'Introduction to database design, implementation, and management.', 'Computer Science', 3),
(13, 'CSE104', 'Software Engineering', 'Covers software development methodologies and practices.', 'Computer Science', 3),
(14, 'CSE105', 'Computer Networks', 'Study of computer network principles and applications.', 'Computer Science', 3),
(15, 'CSE106', 'Artificial Intelligence', 'Introduction to the principles and practices of artificial intelligence.', 'Computer Science', 3),
(16, 'ECE101', 'Digital Logic Design', 'Covers the principles of digital logic design.', 'Engineering', 3),
(17, 'ECE102', 'Microprocessors', 'Introduction to microprocessor architecture and programming.', 'Engineering', 3),
(18, 'ECE103', 'Electromagnetic Fields', 'Study of electromagnetic field theory and applications.', 'Engineering', 2),
(19, 'ECE104', 'Control Systems', 'Introduction to the principles of control systems engineering.', 'Engineering', 3),
(20, 'ECE105', 'Signal Processing', 'Covers the fundamentals of digital signal processing.', 'Engineering', 3);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(200) NOT NULL,
  `faculty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `faculty_id`) VALUES
(1, 'Computer Science', 4),
(2, 'Computer Engineering', 4),
(3, 'Cyber Security', 4);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `event` varchar(200) NOT NULL,
  `date` varchar(20) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event`, `date`, `created_at`) VALUES
(5, 'Testing', 'This event is for testing purpose, the date for the event to happen is 25 of this month as displayed in the calendar.', '2024-10-25', '2024-10-02');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`faculty_id`, `faculty_name`) VALUES
(2, 'Physical Science'),
(3, 'Social Science'),
(4, 'Iya Abubakar Computer Center');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `lecturer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `course` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`lecturer_id`, `name`, `address`, `phone`, `course`, `department`, `email`, `username`, `password`) VALUES
(1, 'Dr. Akinwumi Adesina', '123 Ahmadu Bello Way, Abuja', '08012345678', 'Intro to Programming', 'Computer Science', 'adesina@university.edu.ng', 'adesina', '$2y$10$bT89IaqEU5upqENapfJk8ubCSQj25qtay3l.Nw23l574mFdwN7HTS'),
(2, 'Prof. Maryam Abubakar', '456 Independence Avenue, Lagos', '08023456789', 'Web Application Engineering', 'Computer Science', 'maryam@university.edu.ng', 'maryam', '$2y$10$8qWSx0tVAsXTYnJgELernegxvOwCD0u541YejNCTCo./dWZq3erhq'),
(8, 'Prof. Emeka Uche', '258 Victoria Island, Lagos', '08089012345', 'Discrete Structure', 'Computer Science', 'emeka@university.edu.ng', 'emekauche', '$2y$10$X95cHkjHW3u9/UDOAkcCOOLM0v2Nzwft87Ej/bymi.SyTRljL3Wue'),
(9, 'Dr. Olawale Afolabi', '369 Sabo, Yaba, Lagos', '08090123456', 'Statistics', 'Computer Science', 'olawale@university.edu.ng', 'olawaleafolabi', '$2y$10$6hl0bqIVeogZhaJoFDOX3ugFY2njmo6I1HK5KwOSYMfGfxRN.DLyu'),
(11, 'Dr. salisu sani', 'no. 40 kaduna street', '09040305030', 'database management', 'computer science', 'salisu@gmail.com', 'salis', '$2y$10$ExoL31M6kOD44I.Hwm4lk.9LtL0aiLm5.O3w67W6Lp7yQdPz3C/IO'),
(12, 'Dr. Musa Ahmed', 'No. 24 Ahmadu Bello Way, Kaduna', '08012345671', 'Computer Science', 'Faculty of Science', 'musa.ahmed@abu.edu.ng', 'musa', 'password123'),
(13, 'Prof. Amina Yusuf', 'No. 12 Constitution Road, Zaria', '08012345672', 'Cyber Security', 'Faculty of Science', 'amina.yusuf@abu.edu.ng', 'amina', 'password123'),
(14, 'Dr. Ibrahim Umar', 'No. 5 Tafawa Balewa Street, Kano', '08012345673', 'Computer Engineering', 'Faculty of Engineering', 'ibrahim.umar@abu.edu.ng', 'ibrahim', 'password123'),
(15, 'Mr. Sani Bello', 'No. 18 Independence Way, Abuja', '08012345674', 'Software Engineering', 'Faculty of Science', 'sani.bello@abu.edu.ng', 'sani', 'password123'),
(16, 'Engr. Fatima Suleiman', 'No. 22 Airport Road, Sokoto', '08012345675', 'Electrical Engineering', 'Faculty of Engineering', 'fatima.suleiman@abu.edu.ng', 'fatima', 'password123');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `message_content` text DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `title`, `message_content`, `created_at`) VALUES
(6, 1, NULL, 'Welcome to the New Semester', 'Dear students, welcome to a new academic semester. Please ensure you attend the first lecture on Monday.', '2024-09-19'),
(8, NULL, NULL, 'Assignment Due Reminder', 'Please submit your assignment on Database Systems by Friday, September 22nd. Late submissions will not be accepted.', '2024-09-19'),
(9, NULL, NULL, 'Lecture Cancelled', 'Please note that the lecture scheduled for Wednesday, September 20th, has been cancelled due to unforeseen circumstances.', '2024-09-19'),
(10, NULL, NULL, 'Mid-Semester Exams', 'Mid-semester exams will commence on October 5th. Ensure you have completed all required readings and assignments.', '2024-09-19'),
(11, NULL, NULL, 'Group Project Details', 'You are required to form groups of 5 for the upcoming project. Submit your group names by September 25th.', '2024-09-19');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `message_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `notification_type` enum('reminder','announcement') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message_id`, `message`, `notification_type`, `created_at`, `read_status`) VALUES
(6, 1, 'Welcome to the New Semester', 6, 'Dear students, welcome to a new academic semester. Please ensure you attend the first lecture on Monday.', '', '2024-09-19 07:55:25', 1),
(7, NULL, 'Assignment Due Reminder', 8, 'Please submit your assignment on Database Systems by Friday, September 22nd. Late submissions will not be accepted.', '', '2024-09-19 07:57:55', 1),
(8, NULL, 'Lecture Cancelled', 9, 'Please note that the lecture scheduled for Wednesday, September 20th, has been cancelled due to unforeseen circumstances.', '', '2024-09-19 07:58:39', 1),
(9, NULL, 'Mid-Semester Exams', 10, 'Mid-semester exams will commence on October 5th. Ensure you have completed all required readings and assignments.', '', '2024-09-19 07:59:20', 1),
(10, NULL, 'Group Project Details', 11, 'You are required to form groups of 5 for the upcoming project. Submit your group names by September 25th.', '', '2024-09-19 08:00:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `enrollment_year` year(4) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `enrollment_year`, `department`) VALUES
(1, NULL, 'Umar', 'umar123', 'Umar', 'Sani', 'salisuiliyasu101@gmail.com', '+2348168350130', '2000-12-25', '2022', 'computer science'),
(21, NULL, 'isa', 'isa123', 'isa', 'Yahaya', 'isa123@gmail.com', '+2349031395808', '2024-10-04', '2022', 'Compputer Science');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `user_id`, `assessment_id`, `file_path`, `submission_date`) VALUES
(6, 2, 1, 'uploads/DAYES.docx', '2024-08-11 14:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','medium','high') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `timetable_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `course_name` varchar(200) NOT NULL,
  `lecturer_name` varchar(20) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`timetable_id`, `course_id`, `course_name`, `lecturer_name`, `day_of_week`, `start_time`, `end_time`, `location`) VALUES
(12, NULL, 'Web Application Engineering', 'Dr. Akinwumi Adesina', 'Monday', '08:00:00', '10:00:00', 'RM205'),
(13, NULL, 'English', 'Prof. Emeka Uche', 'Tuesday', '10:00:00', '12:00:00', 'DLC'),
(14, NULL, 'Intro to Programming', 'Dr. Akinwumi Adesina', 'Friday', '16:00:00', '18:00:00', 'RM202'),
(15, NULL, 'Operating System', 'Dr. Akinwumi Adesina', 'Thursday', '14:00:00', '16:00:00', 'DLC'),
(16, NULL, 'English', 'Dr. Olawale Afolabi', 'Tuesday', '14:00:00', '16:00:00', 'DLC'),
(17, NULL, 'Statistics', 'Dr. Olawale Afolabi', 'Friday', '08:00:00', '10:00:00', 'RM202');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'admin123', 'admin123@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `course_id` (`course_name`);

--
-- Indexes for table `assessment_submissions`
--
ALTER TABLE `assessment_submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `assessment_id` (`assessment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`lecturer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `messages_ibfk_1` (`sender_id`),
  ADD KEY `messages_ibfk_2` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`timetable_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `assessment_submissions`
--
ALTER TABLE `assessment_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `lecturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `timetable_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment_submissions`
--
ALTER TABLE `assessment_submissions`
  ADD CONSTRAINT `assessment_submissions_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`assessment_id`),
  ADD CONSTRAINT `assessment_submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`message_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
