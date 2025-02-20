<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$booking_details = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $user_id, $room_id, $check_in, $check_out);

    if ($stmt->execute()) {
        $room_sql = "SELECT * FROM rooms WHERE id = ?";
        $room_stmt = $conn->prepare($room_sql);
        $room_stmt->bind_param("i", $room_id);
        $room_stmt->execute();
        $room_result = $room_stmt->get_result();
        $room = $room_result->fetch_assoc();
        $room_stmt->close();

        $booking_details = true;
        echo "<script>alert('Booking Successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php if ($booking_details && isset($room)): ?>
            <h1>Booking Details</h1>
            <img src="images/<?php echo htmlspecialchars($room['image'] ?? 'default-room.jpg'); ?>" alt="Room Image" style="width: 100%; max-height: 400px; object-fit: cover; margin-bottom: 20px; border-radius: 10px;">
            <p><strong>Room:</strong> <?php echo htmlspecialchars($room['room_type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($room['location'] ?? 'Downtown'); ?></p>
            <p><strong>Beds:</strong> <?php echo htmlspecialchars($room['beds'] ?? rand(1, 5)); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
            <p><strong>Price per Night:</strong> $<?php echo htmlspecialchars($room['price']); ?></p>
            <p><strong>Check-in Date:</strong> <?php echo htmlspecialchars($check_in); ?></p>
            <p><strong>Check-out Date:</strong> <?php echo htmlspecialchars($check_out); ?></p>
        <?php else: ?>
            <h1>Book a Room</h1>
            <form action="" method="post">
                <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
                <label for="check_in">Check-in Date:</label>
                <input type="date" id="check_in" name="check_in" required>
                <label for="check_out">Check-out Date:</label>
                <input type="date" id="check_out" name="check_out" required>
                <input type="submit" value="Book Now">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
