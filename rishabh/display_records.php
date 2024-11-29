<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <title>User Records</title>
</head>
<body>

<div class="container mt-5">
    <h1>User Records</h1>
    
    <!-- Add Record Button -->
    <a href="html_code.php" class="btn btn-primary mb-3">Add New Record</a>
    
    <?php
    include_once 'connection.php';
    $result = $conn->query("SELECT * FROM users");

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age Group</th>
                    <th>Graduation</th>
                    <th>Hobbies</th>
                    <th>DOB</th>
                    <th>Image</th>
                    <th>Actions</th> <!-- Add actions column for Edit and Delete buttons -->
                </tr>
              </thead>
              <tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ageGroup']) . "</td>";
            echo "<td>" . htmlspecialchars($row['graduation']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hobbies']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['imagePath']) . "' alt='Image' width='50'></td>";
            echo "<td>
                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                  </td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No records found.</p>";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
