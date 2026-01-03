<?php
    include "../../include/conn/conn.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
    <link rel="stylesheet" href="../../include/css/candidate.css">
    <!-- <link rel="stylesheet" href="../../include/css/admin.css"> -->
</head>
<body>
    <div class="candidate-register-container">
        <h2 class="register-title">Candidate Registration</h2>
        <form method="POST" class="candidate-form" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name <span class="required-star">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required-star">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password <span class="required-star">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender <span class="required-star">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo">
                </div>
            </div>
            <div class="form-group">
                <label for="about">About <span class="required-star">*</span></label>
                <textarea id="about" name="about" placeholder="Write something about yourself" rows="3" required></textarea>
            </div>
            <input type="submit" name="register_candidate" value="Register as Candidate" class="btn-primary">
        </form>
    </div>
</body>
</html>


<?php
function validate($email, $conn)
{
    $query = "SELECT * FROM candidates WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0)
    {
        return false;
    }
    return true;
}


if(isset($_POST['register_candidate']))
{

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $about = mysqli_real_escape_string($conn, $_POST['about']);
    
    $photo = $_FILES['photo'];

    echo "<script>console.log('Adding candidate:', '$name', '$password', ' $about', '$gender',  '$email', '$photo[name]');</script>";


    $date = date("Y-m-d");
    $time = date("H:i:s");
    $id = "C".str_replace('-', '',$date).str_replace(':', '', $time).rand(1,99);
    if(!validate($email, $conn))
    {
        echo "<script> alert('Candidate with this email already registered for the selected position.');</script>";
        exit;
    }

    if($photo['name'] != "")
    {
        $photo_ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if(!in_array(strtolower($photo_ext), $allowed_ext) || $photo['size'] >= 5000000)
        {
            echo "<script>alert('Invalid photo format. Allowed formats: jpg, jpeg, png. and MAX size 5MB');</script>";
            exit;
        }
        $new_photo_name = $id.".". $photo_ext;
        move_uploaded_file($photo['tmp_name'], "../../include/photo/" . $new_photo_name);
    }
    else
        $new_photo_name = "default.jpg";

    $query = "insert into candidates(id, name, email, password, about, photo, gender, status) values ('$id', '$name', '$email', '$password', '$about','$new_photo_name', '$gender', 'pending')";

    if(mysqli_query($conn, $query))
    {
        session_start();
        $_SESSION['user'] = ["user_type" => "candidate", "id" => $id,"email" => $email, "name" => $name, "photo" => $new_photo_name];

        echo "<script>alert('Candidate added successfully');
            window.location.href='./index.php';
        </script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error adding candidate. Please try again.');</script>";
        echo "<script>console.log('Error: " . mysqli_error($conn) . "');</script>";
    }
}

?>