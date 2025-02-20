<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

require 'config.php';

// Fetch rooms
$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            text-align: center;
            color: white;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            color: black;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #ff4b5c;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: linear-gradient(135deg, #36D1DC, #5B86E5);
            color: white;
        }
        td img {
            border-radius: 10px;
            max-width: 100px;
            height: auto;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .edit-btn {
            background: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .edit-btn:hover {
            background: #27ae60;
        }
        .delete-btn {
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel - Manage Rooms</h1>

        <h3>Add New Room</h3>
        <form method="POST" action="add_room.php">
            <input type="text" name="room_type" placeholder="Room Type" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" name="price" placeholder="Price" required>
            <input type="text" name="location" placeholder="Location" required>
            <input type="number" name="beds" placeholder="Number of Beds" required>
            <button type="submit">Add Room</button>
        </form>

        <h3>Room List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Room Type</th>
                <th>Description</th>
                <th>Price</th>
                <th>Location</th>
                <th>Beds</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $rooms_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>$<?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['beds']); ?></td>
                    <td>
                        <img src="images/<?php echo htmlspecialchars($row['image'] ?? 'default.jpg'); ?>" alt="Room Image">
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_room.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                            <form method="POST" action="delete_room.php" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                <input type="hidden" name="room_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
