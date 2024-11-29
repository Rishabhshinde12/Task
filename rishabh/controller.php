<?php
include_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ageGroup = $_POST['ageGroup'];
    $graduation = $_POST['graduation'];
    $dob = $_POST['dob'];
    $hobbies = implode(", ", $_POST['hobbies']);
    
    
    if (isset($_FILES['userImage'])) {
        $imageName = basename($_FILES['userImage']['name']);
        $targetDir = "uploads/";
    
        
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) { 
                echo "Failed to create directory for uploads!";
                exit;
            }
        }
    
        $targetFilePath = $targetDir . $imageName;
    
        
        if (move_uploaded_file($_FILES['userImage']['tmp_name'], $targetFilePath)) {
            $imagePath = $targetFilePath;
        } else {
            echo "Image upload failed!";
            exit;
        }
    }
    

    $stmt = $conn->prepare("INSERT INTO users (`name`, `email`, `phone`, `ageGroup`, `graduation`, `hobbies`, `imagePath`, `dob`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $phone, $ageGroup, $graduation, $hobbies, $imagePath, $dob);

    if ($stmt->execute()) {
        echo "Record added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM users");
    
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Age Group</th><th>Graduation</th><th>Hobbies</th><th>Image</th></tr></thead><tbody>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['ageGroup'] . "</td>";
            echo "<td>" . $row['graduation'] . "</td>";
            echo "<td>" . $row['hobbies'] . "</td>";
            echo "<td><img src='" . $row['imagePath'] . "' alt='Image' width='50'></td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "No records found.";
    }

    exit; 
}
$conn->close();
?>
