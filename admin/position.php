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
        .data-table td.title-col {
            width: 18%;
            padding-right: 32px;
            padding-left: 32px;
            word-break: break-word;
        }
        .data-table td.desc-col {
            width: 60%;
            padding-left: 24px;
            word-break: break-word;
        }
        .data-table td.action-col {
            width: 12%;
            text-align: center;
            padding-left: 16px;
        }
        .data-table td, .data-table th {
            padding-top: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #dbe7ff;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table tr {
            height: 56px;
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
            filterPosition();
        };
    </script>
</head>
<body>
    <div class="layout">
        <?php include "./pages/sidebar.php"?>
        
        <main class="content">
            <header class="topbar">
                <h1 class="title">Position Management</h1>
                <div class="user-badge">
                    <img src="<?php echo "../include/photo/".$_SESSION['user']['photo']; ?>" alt="Profile" style="width:32px;height:30px;border-radius:50%;margin-right:10px;vertical-align:middle;object-fit:cover;">
                    Admin
                </div>
            </header>
            <section class="panel candidate-panel">
                <h2 class="panel-title">Add New Position</h2>
                <form method="POST" class="candidate-form" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="position_title">Title <span class="required-star">*</span></label>
                            <input type="text" id="position_title" name="title" placeholder="Enter position title" required>
                        </div>
                        <div class="form-group">
                            <label for="position_description">Description <span class="required-star">*</span></label>
                            <textarea id="position_description" name="description" placeholder="Enter position description" rows="3" required></textarea>
                        </div>
                    </div>
                    <input type="submit" name="add_position" value="ADD POSITION" class="btn-primary">
                </form>

            <section class="panel">
                <h2 class="panel-title">Existing Positions</h2>
                <div class="form-group">
                        <input type="text" id="positionSearch" placeholder="Search Position ..." style="width:100%;padding:10px 14px;margin-bottom:16px;font-size:15px;border:1px solid #ccc;border-radius:6px;" onkeyup="filterPosition()">
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

if(isset($_POST['add_position']))
{
    $title = $_POST['title'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "insert into positions (title, description) values ('$title', '$description')";
    if(mysqli_query($conn, $query))
    {
        echo "<script>alert('Position added successfully'); 
        window.location.href='position.php';</script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error adding position:');</script>";
        echo "<script>console.log('".mysqli_error($conn)."');</script>";
    }
}

if(isset($_POST['delete_position']))
{
    $position_id = $_POST['position_id'];

    $query1 = "delete from positions where id='$position_id'";
    $query2 = "update candidates set position_id = 0 where position_id='$position_id'";
    $query3 = "delete from votes where position_id='$position_id'";
    if(mysqli_query($conn, $query1) && mysqli_query($conn, $query2) && mysqli_query($conn, $query3))
    {
        echo "<script>alert('Position deleted successfully'); 
        window.location.href='position.php';</script>";
        exit();
    }
    else
    {
        echo "<script>alert('Error deleting position:');</script>";
        echo "<script>console.log('".mysqli_error($conn)."');</script>";
    }
}

?>