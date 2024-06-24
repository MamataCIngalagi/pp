<?php
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure that the required fields are present in the POST request
    if (isset($_POST['classDate'], $_POST['semester'], $_POST['division'], $_POST['regNumber'], $_POST['name'], $_POST['status'])) {
        $classDate = $_POST['classDate'];
        $semester = $_POST['semester'];
        $division = $_POST['division'];
        $regNumbers = $_POST['regNumber'];
        $names = $_POST['name'];
        $statuses = $_POST['status'];

        // Use prepared statements to prevent SQL injection
        $stmt = $connection->prepare("INSERT INTO attendance_records (classDate, semester, division, regNumber, name, status) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            die("Error preparing statement: " . $connection->error);
        }

        // Bind parameters to the prepared statement and execute for each set of attendance data
        for ($i = 0; $i < count($regNumbers); $i++) {
            $stmt->bind_param("ssssss", $classDate, $semester, $division, $regNumbers[$i], $names[$i], $statuses[$i]);
            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }
        }

        // Close the prepared statement
        $stmt->close();

        // Provide feedback to the user
        echo "Attendance data submitted successfully!";
    } else {
        echo "Required fields are missing in the POST request.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$connection->close();
?>
