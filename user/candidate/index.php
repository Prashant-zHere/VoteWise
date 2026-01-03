<?php
    include "../../include/conn/conn.php";
    include "../../include/conn/session.php";
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
    <link rel="stylesheet" href="../../include/css/candidate_dashboard.css">
    <link rel="stylesheet" href="../../include/css/admin.css">
</head>
<body>
    <div class="candidate-dashboard-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="dashboard-title" style="margin-bottom:0;">Candidate Dashboard</h1>
            <form action="" method="POST" style="margin:0;">
                <button type="submit" class="logout-btn" name="logout" value="logout">Logout</button>
            </form>
        </div>

        <section class="profile-section">
            <div class="section-title">Your Profile</div>
            <div class="profile-info">
                <img class="profile-photo" src="<?php echo isset($_SESSION['user']['photo']) ? '../../include/photo/' . $_SESSION['user']['photo'] : '../../include/img/login_bg.jpeg'; ?>" alt="Profile Photo">
                <div class="profile-details">
                    <div><strong>Name:</strong> <?php echo $_SESSION['user']['name']; ?></div>
                    <div><strong>Email:</strong> <?php echo $_SESSION['user']['email']; ?></div>
                    <?php
                        $email = $_SESSION['user']['email'];
                        $status = 'Pending';
                        $about = '';
                        $candidate_query = "SELECT status, about FROM candidates WHERE email = '$email'";
                        $candidate_result = mysqli_query($conn, $candidate_query);
                        if ($candidate_result && mysqli_num_rows($candidate_result) > 0) {
                            $row = mysqli_fetch_assoc($candidate_result);
                            $status = $row['status'];
                            $about = $row['about'];
                        }
                        $status_class = $status === 'approved' ? 'status-approved' : ($status === 'rejected' ? 'status-rejected' : 'status-pending');
                    ?>
                    <div><strong>Status:</strong> <span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span></div>
                    <div><strong>About:</strong> <?php echo htmlspecialchars($about); ?></div>
                </div>
            </div>
        </section>

        <section class="positions-section">
            <div class="section-title">Available Positions to Apply</div>
            <table class="positions-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $positions_query = "select * from positions";
                        $positions_result = mysqli_query($conn, $positions_query);
                        $applied_query = "select position_id from candidates WHERE email = '$email'";
                        $applied_result = mysqli_query($conn, $applied_query);
                        $applied_position_id = null;
                        if ($applied_result && mysqli_num_rows($applied_result) > 0) {
                            $row = mysqli_fetch_assoc($applied_result);
                            $applied_position_id = $row['position_id'];
                        }
                        if ($positions_result && mysqli_num_rows($positions_result) > 0) {
                            $sno = 1;
                            while ($row = mysqli_fetch_assoc($positions_result)) {
                                $already_applied = ($applied_position_id == $row['id']);
                                echo '<tr>';
                                echo '<td>' . $sno++ . '</td>';
                                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                echo '<td>';
                                if ($already_applied) {
                                    echo '<button class="apply-btn" disabled>Applied</button>';
                                } else {
                                    if ($applied_position_id) {
                                        echo '<button class="apply-btn" disabled>Can\'t apply</button>';
                                    } else {
                                        echo '<form method="POST" style="display:inline;">';
                                        echo '<input type="hidden" name="apply_position_id" value="' . $row['id'] . '">';
                                        echo '<button type="submit" class="apply-btn">Apply</button>';
                                        echo '</form>';
                                    }
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" style="text-align:center;">No positions available.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
            <?php
            if (isset($_POST['apply_position_id']) && $status !== 'approved') {
                $position_id = intval($_POST['apply_position_id']);
                if (!$applied_position_id) {
                    $update_query = "UPDATE candidates SET position_id = $position_id, status = 'pending' WHERE email = '$email'";
                    if (mysqli_query($conn, $update_query)) {
                        echo '<script>alert("Applied for position successfully. Status set to Pending."); window.location.reload();</script>';
                    } else {
                        echo '<script>alert("Error applying for position.");</script>';
                    }
                } else if ($applied_position_id != $position_id) {
                    echo '<script>alert("Error: You have already applied for another position. You cannot apply for more than one position.");</script>';
                }
                
                echo '<script>window.location = "./";</script>';
            }
            ?>
        </section>
<!-- 
        <section class="voting-section">
            <div class="section-title">Voting Completion</div>
            <?php
                $votes_query = "SELECT COUNT(*) as total_votes FROM votes";
                $votes_result = mysqli_query($conn, $votes_query);
                $votes_count = 0;
                if ($votes_result) {
                    $votes_row = mysqli_fetch_assoc($votes_result);
                    $votes_count = $votes_row['total_votes'];
                }
                $voters_query = "SELECT COUNT(*) as total_voters FROM voters";
                $voters_result = mysqli_query($conn, $voters_query);
                $voters_count = 1;
                if ($voters_result) {
                    $voters_row = mysqli_fetch_assoc($voters_result);
                    $voters_count = max(1, $voters_row['total_voters']);
                }
                $voting_percent = round(($votes_count / $voters_count) * 100, 1);
            ?>
            <div class="voting-progress-bar">
                <div class="voting-progress" style="width: <?php echo $voting_percent; ?>%;">
                    <?php echo $voting_percent; ?>%
                </div>
            </div>
            <div style="margin-top:8px;color:#1976d2;font-weight:600;">Total Votes Cast: <?php echo $votes_count; ?> / <?php echo $voters_count; ?> voters</div>
        </section> -->
    </div>
</body>
</html>