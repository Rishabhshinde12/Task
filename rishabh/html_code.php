<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add record</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script>
    function validateImageSize() {
        const fileInput = document.getElementById('userImage');
        const file = fileInput.files[0];
        const imageError = document.getElementById('imageError');
        
        if (file) {
            const fileSizeInBytes = file.size; // Size in bytes
            const minFileSize = 50 * 1024; // 50 KB in bytes
            const maxFileSize = 100 * 1024; // 100 KB in bytes

            console.log("File size: " + fileSizeInBytes + " bytes"); // Log file size for debugging

            // Check if the file size is within the allowed range
            if (fileSizeInBytes < minFileSize || fileSizeInBytes > maxFileSize) {
                imageError.textContent = 'Image size must be between 50 KB and 100 KB.';
                fileInput.value = ''; // Clear the file input
            } else {
                imageError.textContent = ''; // Clear any previous error message
            }
        } else {
            imageError.textContent = ''; // Clear error if no file is selected
        }
    }

    // Add an event listener to trigger validation when the user selects a file
    document.getElementById('userImage').addEventListener('change', validateImageSize);
</script>




    <script>
        
        $(document).ready(function() {
            $('#myForm').on('submit', function(e) {
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
                    $('#phoneError').text('Please enter a valid phone number. Phone number must be exactly 10 digits.');
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

                
              

                
                const dob = $('#dob').val();
                if (dob) {
                    const dobDate = new Date(dob);
                    const age = new Date().getFullYear() - dobDate.getFullYear();
                    const m = new Date().getMonth() - dobDate.getMonth();
                    if (age < 19 || (age === 19 && m < 0)) {
                        $('#dobError').text('You must be at least 19 years old.');
                        isValid = false;
                    }
                } else {
                    $('#dobError').text('Please select a date of birth.');
                    isValid = false;
                }

                if (isValid) {
                    let formData = new FormData(this);
                    $.ajax({
                        url: 'controller.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            alert(response);
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
</head>

<body>
    <div class="container mt-5">
        <h2>User Form</h2>
        <form id="myForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required
                    oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                <small id="nameError" class="text-danger error"></small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
                <small id="emailError" class="text-danger error"></small>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" maxlength="10" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <small id="phoneError" class="text-danger error"></small>
            </div>

            <div class="mb-3">
                <label class="form-label">Age Group:</label><br>
                <input type="radio" id="ageGroup1" name="ageGroup" value="18-25">
                <label for="ageGroup1">18-25</label><br>
                <input type="radio" id="ageGroup2" name="ageGroup" value="26-35">
                <label for="ageGroup2">26-35</label><br>
                <input type="radio" id="ageGroup3" name="ageGroup" value="36-45">
                <label for="ageGroup3">36-45</label>
                <small id="ageError" class="text-danger error"></small>
            </div>
            <div class="mb-3">
                <label for="graduation" class="form-label">Graduation:</label>
                <select id="graduation" name="graduation" class="form-select" required>
                    <option value="">Select</option>
                    <option value="Undergraduate">Undergraduate</option>
                    <option value="Postgraduate">Postgraduate</option>
                </select>
                <small id="graduationError" class="text-danger error"></small>
            </div>
            <div class="mb-3">
                <label class="form-label">Hobbies:</label><br>
                <input type="checkbox" id="hobby1" name="hobbies[]" value="Reading">
                <label for="hobby1">Reading</label><br>
                <input type="checkbox" id="hobby2" name="hobbies[]" value="Traveling">
                <label for="hobby2">Traveling</label><br>
                <input type="checkbox" id="hobby3" name="hobbies[]" value="Sports">
                <label for="hobby3">Sports</label>
                <small id="hobbiesError" class="text-danger error"></small>
            </div>
            <div class="mb-3">
    <label for="userImage" class="form-label">Upload Image:</label>
    <input type="file" id="userImage" name="userImage" class="form-control" required 
           onchange="validateImageSize()">
    <small id="imageError" class="text-danger error"></small>
</div>
            
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth:</label>
                <input type="date" id="dob" name="dob" class="form-control" required>
                <small id="dobError" class="text-danger error"></small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="display_records.php" class="btn btn-secondary mt-3">View Records</a>
        </form>
    </div>
</body>

</html>
