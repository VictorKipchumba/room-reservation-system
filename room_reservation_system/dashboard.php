<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

$searchQuery = "";
$location = "";
$beds = "";
$whereClauses = ["availability = 1"];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['search'])) {
        $searchQuery = $_GET['search'];
        $whereClauses[] = "room_type LIKE '%$searchQuery%'";
    }
    if (!empty($_GET['location'])) {
        $location = $_GET['location'];
        $whereClauses[] = "location LIKE '%$location%'";
    }
    if (!empty($_GET['beds'])) {
        $beds = (int)$_GET['beds'];
        $whereClauses[] = "beds = $beds";
    }
}

$whereSQL = implode(" AND ", $whereClauses);
$sql = "SELECT * FROM rooms WHERE $whereSQL";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search room type" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <input type="text" name="location" placeholder="Enter location" value="<?php echo htmlspecialchars($location); ?>">
            <input type="number" name="beds" placeholder="Number of beds" value="<?php echo htmlspecialchars($beds); ?>">
            <button type="submit">Search</button>
        </form>
        
        <h2>Available Rooms</h2>
        <table border="1">
            <tr>
                <th>Room Type</th>
                <th>Location</th>
                <th>Beds</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['location'] ?? 'Downtown'); ?></td>
                    <td><?php echo htmlspecialchars($row['beds'] ?? rand(1, 5)); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>$<?php echo htmlspecialchars($row['price']); ?></td>
                    <td>
                        <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="Room Image" style="width:100px; height:auto;">
                    </td>
                    <td><a href="book.php?id=<?php echo htmlspecialchars($row['id']); ?>">Book</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
