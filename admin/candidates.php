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
    .data-table th, .data-table th {
        text-align: left;
        padding-left: 22px;
    }
    .data-table td, .data-table th {
        font-weight: 600;
        padding-top: 18px;
        padding-bottom: 18px;
        border-bottom: 1px solid #dbe7ff;
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
    .btn-view {
        background: #1976d2;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin-right: 6px;
        transition: background 0.2s;
    }
    .btn-view:hover {
        background: #1251a1;
    }
    .modal .btn-approve {
        background: #43a047;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin: 0 4px;
    }
    .modal .btn-approve:hover {
        background: #2e7031;
    }
    .modal .btn-reject {
        background: #e53935;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin: 0 4px;
    }
    .modal .btn-reject:hover {
        background: #b71c1c;
    }
    .modal .btn-delete {
        background: #dc3545;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin: 0 4px;
    }
    .modal .btn-delete:hover {
        background: #a71d2a;
    }

    </style>

    <script src="../include/js/admin_candidate.js"></script>
    <script>
        window.onload = function() {
            filterCandidates();
            //  filterByGender();

        };
    </script>
</head>
<body>
    <div class="layout">
        <?php include "./pages/sidebar.php"?>
        
        <main class="content">
            <header class="topbar">
                <h1 class="title">Candidates Management</h1>
                <div class="user-badge">
                    <img src="<?php echo "../include/photo/".$_SESSION['user']['photo']; ?>" alt="Profile" style="width:32px;height:30px;border-radius:50%;margin-right:10px;vertical-align:middle;object-fit:cover;">
                    Admin
                </div>
            </header>

            <section class="panel candidate-panel">
                <h2 class="panel-title">Add New Candidate</h2>
                <form method="POST" class="candidate-form" enctype="multipart/form-data" style="min-width:300px;min-height:350px;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required-star">*</span></label>
                            <input type="text" id="candidate_name" name="name" placeholder="Enter candidate's full name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="required-star">*</span></label>
                            <input type="email" id="candidate_email" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password <span class="required-star">*</span></label>
                            <input type="password" id="candidate_password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Position <span class="required-star">*</span></label>
                            <select id="candidate_position" name="position" required>
                                <option value="" disabled selected>Select Position</option>
                                <?php
                                    $pos_query = "SELECT * FROM positions";
                                    $pos_result = mysqli_query($conn, $pos_query);
                                    while($pos_row = mysqli_fetch_assoc($pos_result))
                                    {
                                        echo "<option value=". $pos_row['id']. ">".$pos_row['title']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="candidate_photo">Photo Max Size 5Mb (allowed type jpeg, jpg, png)</label>
                            <input type="file" id="candidate_photo" name="candidate_photo">
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
                    <div class="form-group">
                        <label for="candidate_about">About <span class="required-star">*</span></label>
                        <textarea id="candidate_about" name="candidate_about" placeholder="Write something about the candidate" rows="3" required></textarea>
                    </div>
                    <input type="submit" name="add_candidate" value="ADD CANDIDATE" class="btn-primary">
                </form>
            </section>

            <section class="panel">
                <h2 class="panel-title">Candidates List</h2>
                <div class="form-group">
                    <input type="text" id="candidateSearch" placeholder="Search candidates by id, name or email..." style="width:75%;padding:10px 14px;margin-bottom:16px;font-size:15px;border:1px solid #ccc;border-radius:6px;" onkeyup="filterCandidates()">
                    <select  id="status" style="width:20%;" onchange="filterByStatus()">
                        <option value="" disabled selected>Select By Status</option>
                        <option value="all">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <!-- <select  id="gender" style="width:15%;" onchange="filterByGender()">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="all">All Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select> -->
                </div>

                <div id="display">
                </div>
            </section>
        </main>
    </div>

    <div id="profileModal" class="modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
        <div class="modal-content" style="background:#fff;padding:32px 28px;border-radius:12px;max-width:400px;width:95%;position:relative;">
            <span class="close" onclick="closeProfileModal()" style="position:absolute;top:12px;right:18px;font-size:24px;cursor:pointer;">&times;</span>
            <div id="modalProfileContent"></div>
        </div>
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

if(isset($_POST['add_candidate']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $position = $_POST['position'];
    $gender = $_POST['gender'];
    $about = mysqli_real_escape_string($conn, $_POST['candidate_about']);
    $photo = $_FILES['candidate_photo'];

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
        move_uploaded_file($photo['tmp_name'], "../include/photo/" . $new_photo_name);
    }
    else
        $new_photo_name = "default.jpg";

    $query = "insert into candidates(id, name, email, password, about, position_id, photo, gender, status) values ('$id', '$name', '$email', '$password', '$about', '$position', '$new_photo_name', '$gender', 'approved')";

    if(mysqli_query($conn, $query))
    {
        echo "<script>alert('Candidate added successfully');
            window.location.href='./candidates.php';
        </script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error adding candidate. Please try again.');</script>";
        echo "<script>console.log('Error: " . mysqli_error($conn) . "');</script>";
    }
}

if(isset($_POST['delete_candidate']))
{
    $candidate_id = $_POST['candidate_id'];
    $query1 = "delete from candidates where id = '$candidate_id'";
    $query2 = "delete from votes where candidate_id = '$candidate_id'";
    if(mysqli_query($conn, $query1) && mysqli_query($conn, $query2))
    {
        echo "<script>alert('Candidate deleted successfully');
            window.location.href='./candidates.php';
        </script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error deleting candidate. Please try again.');</script>";
        echo "<script>console.log('Error: " . mysqli_error($conn) . "');</script>";
    }
}

if(isset($_POST['approve_candidate'])) {
    $candidate_id = $_POST['candidate_id'];
    $query = "UPDATE candidates SET status='approved' WHERE id='$candidate_id'";
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Candidate approved successfully');window.location.href='./candidates.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error approving candidate.');</script>";
    }
}

if(isset($_POST['reject_candidate'])) {
    $candidate_id = $_POST['candidate_id'];
    $query = "UPDATE candidates SET status='rejected' WHERE id='$candidate_id'";
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Candidate rejected successfully');window.location.href='./candidates.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error rejecting candidate.');</script>";
    }
}
?>