<?php
    include "../include/conn/conn.php";
    include "../include/conn/session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../include/css/admin.css">
    <title>Admin Dashboard</title>

    <style>
        .data-table {
            width: 100%;
            border: 1px solid #dbe7ff;
            border-radius: 8px;
            border-collapse: separate;
            table-layout: fixed;
        }
        .data-table th, .data-table td {
            border-right: 2px solid #dbe7ff;
        }
        .data-table th:last-child, .data-table td:last-child {
            border-right: none;
        }
        .data-table th {
            text-align: left;
            padding-left: 32px;
        }
        .data-table td.voter-name, td.voter-email {
            padding-left: 24px;
            padding-right: 24px;
        }
        .data-table td.action-col {
            text-align: center;
            padding-left: 16px;
        }
        .data-table td.id-col, .data-table th.id-col {
            padding-left: 16px;
            padding-right: 16px;
            text-align: center;
        }

        .data-table td, .data-table th {
            padding-top: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #dbe7ff;
            font-weight: 600;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table tr {
            height: 40px;
        }
        .data-table td img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #e57373 100%);
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.15);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.25);
            transform: translateY(-2px);
        }
    </style>
    <script src="../include/js/voters_position_table.js"></script>

    <script>
        window.onload = function() {
            filterVoters();
        };
    </script>
</head>
<body>
    <div class="layout">
        <?php include "./pages/sidebar.php"?>
        
        <main class="content">
            <header class="topbar">
                <h1 class="title">Voters Management</h1>
                <div class="user-badge">
                    <img src="<?php echo "../include/photo/".$_SESSION['user']['photo']; ?>" alt="Profile" style="width:32px;height:30px;border-radius:50%;margin-right:10px;vertical-align:middle;object-fit:cover;">
                    Admin
                </div>
            </header>
            <section class="panel candidate-panel">
                <h2 class="panel-title">Add New Voter</h2>
                <form method="POST" class="candidate-form" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="voter_name">Full Name <span class="required-star">*</span></label>
                            <input type="text" id="voter_name" name="name" placeholder="Enter voter's full name" required>
                        </div>
                        <div class="form-group">
                            <label for="voter_email">Email <span class="required-star">*</span></label>
                            <input type="email" id="voter_email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="voter_password">Password <span class="required-star">*</span></label>
                            <input type="password" id="voter_password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="form-group">
                            <label for="voter_photo">Photo Max Size 5Mb (allowed type jpeg, jpg, png)</label>
                            <input type="file" id="voter_photo" name="photo">
                        </div>
                    </div>
                    <input type="submit" name="add_voter" value="ADD VOTER" class="btn-primary">
                </form>
                <section class="panel">
                    <h2 class="panel-title">Voters List</h2>
                    <div class="form-group">
                        <input type="text" id="voterSearch" placeholder="Search voters by id, name or email..." style="width:100%;padding:10px 14px;margin-bottom:16px;font-size:15px;border:1px solid #ccc;border-radius:6px;" onkeyup="filterVoters()">
                    </div>
                    <div id="display">
                    </div>
                </section>
            </section>

        </main>
    </div>
</body>
</html>




<?php

function validate($email, $conn) {
    $query = "SELECT * FROM voters WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}



if(isset($_POST['add_voter']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $photo = $_FILES['photo'];

    echo "<script>console.log('Adding voter:', '$name', '$password', '$email', '$photo[name]');</script>";
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $id = "V".str_replace('-', '',$date).str_replace(':', '', $time).rand(1,99);
    if(validate($email, $conn))
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
        move_uploaded_file($photo['tmp_name'], "../include/photo/" . $new_photo_name);
    }
    else
        $new_photo_name = "default.jpg";

    $query = "insert into voters(name, id, email, password, photo) values ('$name','$id','$email', '$password', '$new_photo_name')";

    if(mysqli_query($conn, $query))
    {
        echo "<script> alert('Voter added successfully'); 
            window.location.href='./voters.php';
            </script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error adding candidate. Please try again.');</script>";
        echo "<script>console.log('Error: " . mysqli_error($conn) . "');</script>";
    }
}

if(isset($_POST['delete_voter']))
{
    $voter_id = $_POST['voter_id'];
    $query1 = "delete from voters where id='$voter_id'";
    $query2 = "delete from votes where voter_id='$voter_id'";
    if(mysqli_query($conn, $query1) && mysqli_query($conn, $query2))
    {
        echo "<script>alert('Voter deleted successfully'); 
        window.location.href='voters.php';</script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error deleting voter. Please try again.');</script>";
        echo "<script>console.log('Error: " . mysqli_error($conn) . "');</script>";
    }
}   

?>