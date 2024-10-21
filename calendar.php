<?php
$events = [];

function createEvent(&$events, $date, $event) {
    $events[$date][] = $event;
}

// Example events
createEvent($events, '2024-07-07', 'Cybersecurity Workshop');
createEvent($events, '2024-07-10', 'Ethical Hacking Training');
createEvent($events, '2024-07-15', 'Penetration Testing Seminar');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="fullcalendar.min.css">
</head>
<body>
<div class="container">
    <h1 class="my-4">Calendar</h1>
    <div id="calendar"></div>
</div>

<script src="jquery.min.js"></script>
<script src="moment.min.js"></script>
<script src="fullcalendar.min.js"></script>
<script>
    $(document).ready(function () {
        var events = <?php echo json_encode($events); ?>;
        var calendarEvents = [];

        for (var date in events) {
            if (events.hasOwnProperty(date)) {
                events[date].forEach(function (event) {
                    calendarEvents.push({
                        title: event,
                        start: date
                    });
                });
            }
        }

        $('#calendar').fullCalendar({
            defaultView: 'month',
            editable: false,
            events: calendarEvents
        });
    });
</script>
</body>
</html>
