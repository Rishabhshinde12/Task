<?php

include_once 'connection.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    
    $result = $conn->query("SELECT * FROM users WHERE id = $id");

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found!";
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ageGroup = $_POST['ageGroup'];
    $graduation = $_POST['graduation'];
    $dob = $_POST['dob'];
    $hobbies = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : '';

    
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, ageGroup = ?, graduation = ?, hobbies = ?, dob = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $email, $phone, $ageGroup, $graduation, $hobbies, $dob, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully!";
        header("Location: display_records.php"); 
        exit;
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
    <title>Edit User Record</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script>
        $(document).ready(function() {
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                $('.error').text('');

                
                if (!$('#name').val()) {
                    $('#nameError').text('Name is required.');
                    isValid = false;
                }
                if (!$('#name').val() || !/^[A-Za-z\s]+$/.test($('#name').val())) {
                    $('#nameError').text('Name must contain only letters.');
                    isValid = false;
                }

                
                if (!$('#email').val() || !/^\S+@\S+\.\S+$/.test($('#email').val())) {
                    $('#emailError').text('Valid email is required.');
                    isValid = false;
                }

                
                if (!$('#phone').val() || !/^\d{10}$/.test($('#phone').val())) {
                    $('#phoneError').text('Phone number must be exactly 10 digits.');
                    isValid = false;
                }

                
                if (!$('input[name="ageGroup"]:checked').val()) {
                    $('#ageError').text('Please select an age group.');
                    isValid = false;
                }

               
                if (!$('#graduation').val()) {
                    $('#graduationError').text('Please select your graduation status.');
                    isValid = false;
                }

                
                if ($('input[name="hobbies[]"]:checked').length === 0) {
                    $('#hobbiesError').text('Please select at least one hobby.');
                    isValid = false;
                }

                
                if (isValid) {
                    let formData = new FormData(this); 
                    $.ajax({
                        url: 'edit.php?id=<?php echo $row['id']; ?>',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            alert('record update successfully');
                            window.location.href = 'display_records.php'; 
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User Record</h2>
        <form id="editForm" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')" required>
                <small id="nameError" class="text-danger error"></small>
            </div>

            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                <small id="emailError" class="text-danger error"></small>
            </div>

            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                <small id="phoneError" class="text-danger error"></small>
            </div>

            
            <div class="mb-3">
                <label class="form-label">Age Group:</label><br>
                <input type="radio" id="ageGroup1" name="ageGroup" value="18-25" <?php echo ($row['ageGroup'] == '18-25') ? 'checked' : ''; ?>>
                <label for="ageGroup1">18-25</label><br>
                <input type="radio" id="ageGroup2" name="ageGroup" value="26-35" <?php echo ($row['ageGroup'] == '26-35') ? 'checked' : ''; ?>>
                <label for="ageGroup2">26-35</label><br>
                <input type="radio" id="ageGroup3" name="ageGroup" value="36-45" <?php echo ($row['ageGroup'] == '36-45') ? 'checked' : ''; ?>>
                <label for="ageGroup3">36-45</label>
                <small id="ageError" class="text-danger error"></small>
            </div>

            
            <div class="mb-3">
                <label for="graduation" class="form-label">Graduation:</label>
                <select id="graduation" name="graduation" class="form-select" required>
                    <option value="">Select</option>
                    <option value="Undergraduate" <?php echo ($row['graduation'] == 'Undergraduate') ? 'selected' : ''; ?>>Undergraduate</option>
                    <option value="Postgraduate" <?php echo ($row['graduation'] == 'Postgraduate') ? 'selected' : ''; ?>>Postgraduate</option>
                </select>
                <small id="graduationError" class="text-danger error"></small>
            </div>

            
            <div class="mb-3">
                <label class="form-label">Hobbies:</label><br>
                <input type="checkbox" id="hobby1" name="hobbies[]" value="Reading" <?php echo (in_array('Reading', explode(', ', $row['hobbies']))) ? 'checked' : ''; ?>>
                <label for="hobby1">Reading</label><br>
                <input type="checkbox" id="hobby2" name="hobbies[]" value="Traveling" <?php echo (in_array('Traveling', explode(', ', $row['hobbies']))) ? 'checked' : ''; ?>>
                <label for="hobby2">Traveling</label><br>
                <input type="checkbox" id="hobby3" name="hobbies[]" value="Sports" <?php echo (in_array('Sports', explode(', ', $row['hobbies']))) ? 'checked' : ''; ?>>
                <label for="hobby3">Sports</label>
                <small id="hobbiesError" class="text-danger error"></small>
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth:</label>
                <input type="date" id="dob" name="dob" class="form-control" value='<?php echo $row['dob']; ?>' required>
                <small id="dobError" class="text-danger error"></small>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Record</button>
            <a href="display_records.php" class="btn btn-secondary mt-3">View Records</a>
        </form>
    </div>
</body>
</html>
