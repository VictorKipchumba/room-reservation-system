<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

require 'config.php';

// Validate and retrieve room ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Error: Room ID is missing or invalid.'); window.location.href='manage_rooms.php';</script>";
    exit();
}

$room_id = (int)$_GET['id'];

// Fetch existing room details
$sql = "SELECT * FROM rooms WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();

if (!$room) {
    echo "<script>alert('Error: Room not found.'); window.location.href='manage_rooms.php';</script>";
    exit();
}

// Ensure values are not null
$room_type = htmlspecialchars($room['room_type'] ?? '');
$description = htmlspecialchars($room['description'] ?? '');
$price = htmlspecialchars($room['price'] ?? '0');
$location = htmlspecialchars($room['location'] ?? 'N/A');
$beds = htmlspecialchars($room['beds'] ?? '1');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_type = $_POST['room_type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $beds = $_POST['beds'];

    $sql = "UPDATE rooms SET room_type = ?, description = ?, price = ?, location = ?, beds = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsii", $room_type, $description, $price, $location, $beds, $room_id);

    if ($stmt->execute()) {
        echo "<script>alert('Room updated successfully!'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Room</h1>
        <form action="" method="post">
            <label for="room_type">Room Type:</label>
            <input type="text" id="room_type" name="room_type" value="<?php echo $room_type; ?>" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $description; ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $price; ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo $location; ?>" required>

            <label for="beds">Number of Beds:</label>
            <input type="number" id="beds" name="beds" value="<?php echo $beds; ?>" required>

            <input type="submit" value="Update Room">
        </form>
    </div>
</body>
</html>
