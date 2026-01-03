<?php
    include "./include/conn/conn.php";
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusVoice VoteWise</title>
    <link rel="stylesheet" href="./include/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Sign in to your account</h1>
            <h2>Secure Voting system</h2>
        </div>
        
        <form action="" method="post">
            <div class="user-type">
                <label class="radio-label">
                    <input type="radio" name="user_type" value="admin" required>
                    <span class="radio-text">Admin</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="user_type" value="candidate" required>
                    <span class="radio-text">Candidate</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="user_type" value="voter" required>
                    <span class="radio-text">Voter</span>
                </label>
            </div>
            
            <div class="form-group">
                <input type="text" id="id" placeholder="Enter Email address or ID" name="id" required>
            </div>
            
            <div class="form-group">
                <input type="password" id="password" placeholder="Enter Password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn" name="login">
                Sign in
            </button>
        </form>
        
        <a href="./user/candidate/register.php" class="forgot-password">Register As Candidate</a>
    </div>
</body>
</html>


<?php

    function validate_login($id, $password, $user_type, $conn)
    {
        if($user_type == "admin")
            $query = "select * from admin where (id = '$id' or email = '$id') and password = '$password'";
        else if($user_type == "candidate")
            $query = "select * from candidates where (id = '$id' or email = '$id') and password = '$password'";
        else if($user_type == "voter")
            $query = "select * from voters where (id = '$id' or email = '$id') and password = '$password'";
        
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        // print_r($row);

        if(mysqli_num_rows($result) == 1)
        {
            $_SESSION['user'] = ["user_type" => $user_type, "id" => $row['id'],"email" => $row['email'], "name" => $row['name'], "photo" => $row['photo']];
            return true;
        }
        return false;
    }

    if(isset($_POST['login'])){
        $id = $_POST['id'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];
        echo "<script>console.log('$id','$password','$user_type');</script>";

        if(validate_login($id, $password, $user_type, $conn))
        {
            if($user_type == "voter")
            {
                header("Location: ./user/voter");
                exit();
            }
            else if($user_type == "admin")
            {
                header("Location: ./admin/");
                exit();
            }
            else if($user_type == "candidate")
            {
                header("Location: ./user/candidate");
                exit();
            }
            echo "<script>console.log('Login successful');</script>";
        }else
            echo "<script>alert('ID or Password is incorrect. Please try again.');</script>";
        
    }
?>