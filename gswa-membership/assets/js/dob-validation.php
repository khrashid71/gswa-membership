<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enforced MM/DD/YYYY Calendar</title>
    
    <!-- 1. Include Flatpickr CSS Stylesheets for the calendar UI -->
    <link rel="stylesheet" href="https://jsdelivr.net">
</head>
<body>

<form action="/submit-birthday" method="POST">
    <div>
        <label for="birthday">Date of Birth:</label>
        <!-- Flatpickr will turn this text field into a visual calendar -->
        <input type="text" id="birthday" name="birthday" placeholder="Select Date..." required>
    </div>
    <br>
    <button type="submit">Submit Date</button>
</form>

<!-- 2. Include Flatpickr JavaScript Library -->
<script src="https://jsdelivr.net"></script>

<script>
    // 3. Initialize the calendar with your strict format requirements
    flatpickr("#birthday", {
        dateFormat: "m/d/Y",     // Forces the text value to MM/DD/YYYY upon selection
        allowInput: true,        // Allows users to type it manually if they prefer
        maxDate: "today"         // Prevents selecting future dates for birth dates
    });
</script>

</body>
</html>
