<?php
    include "../../include/conn/conn.php";
    include "../../include/conn/session.php";
    // print_r($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Dashboard</title>
    <link rel="stylesheet" href="../../include/css/voter_dashboard.css">
    <link rel="stylesheet" href="../../include/css/admin.css">
</head>
<body>
    <div class="voter-dashboard-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="dashboard-title" style="margin-bottom:0;">Voter Dashboard</h1>
            <form action="" method="POST" style="margin:0;">
                <button type="submit" class="logout-btn" name="logout" value="logout">Logout</button>
            </form>
        </div>
        <section class="profile-section" style="margin-bottom:32px;background:#f8f9fa;border-radius:12px;padding:24px 24px 18px 24px;box-shadow:0 2px 8px rgba(25,118,210,0.06);">
            <div class="section-title">Your Profile</div>
            <div class="profile-info" style="display:flex;align-items:center;gap:24px;">
                <img class="profile-photo" src="<?php echo isset($_SESSION['user']['photo']) ? '../../include/photo/' . $_SESSION['user']['photo'] : '../../include/img/login_bg.jpeg'; ?>" alt="Profile Photo" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid #1976d2;">
                <div class="profile-details" style="font-size:1.1rem;">
                    <div><strong>Name:</strong> <?php echo $_SESSION['user']['name']; ?></div>
                    <div><strong>Email:</strong> <?php echo $_SESSION['user']['email']; ?></div>
                    <div><strong>ID:</strong> <?php echo $_SESSION['user']['id']; ?></div>
                </div>
            </div>
        </section>
        <section class="votes-cast-section" style="margin-bottom:32px;background:#f8f9fa;border-radius:12px;padding:24px 24px 18px 24px;box-shadow:0 2px 8px rgba(25,118,210,0.06);">
            <div class="section-title">Your Votes</div>
            <table class="candidates-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Position</th>
                        <th>Candidate</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $voter_id = $_SESSION['user']['id'];
                    $votes_query = "SELECT v.position_id, v.candidate_id, p.title as position_title, c.name as candidate_name FROM votes v JOIN positions p ON v.position_id = p.id JOIN candidates c ON v.candidate_id = c.id WHERE v.voter_id = '$voter_id'";
                    $votes_result = mysqli_query($conn, $votes_query);
                    if ($votes_result && mysqli_num_rows($votes_result) > 0) {
                        $sno = 1;
                        while ($vote = mysqli_fetch_assoc($votes_result)) {
                            echo '<tr>';
                            echo '<td>' . $sno++ . '</td>';
                            echo '<td>' . htmlspecialchars($vote['position_title']) . '</td>';
                            echo '<td>' . htmlspecialchars($vote['candidate_name']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3" style="text-align:center;">You have not voted yet.</td></tr>';
                    }
                ?>
                </tbody>
            </table>
        </section>

        Voting Progress Section
        <section class="voting-section">
            <div class="section-title">Voting Completion</div>
            <?php
                $votes_query = "select COUNT(*) as total_votes from votes group by position_id";
                $votes_result = mysqli_query($conn, $votes_query);
                $votes_count = 0;
                if ($votes_result) {
                    $votes_row = mysqli_fetch_assoc($votes_result);
                    $votes_count = $votes_row['total_votes'];
                }
                $voters_query = "select COUNT(*) as total_voters from voters";
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
        </section>
        
        <br><br>
        <section class="positions-section">
            <div class="section-title">Positions & Candidates</div>
            <?php
                $positions_query = "SELECT * FROM positions";
                $positions_result = mysqli_query($conn, $positions_query);
                $voter_id = $_SESSION['user']['id'];
                if ($positions_result && mysqli_num_rows($positions_result) > 0) {
                    while ($position = mysqli_fetch_assoc($positions_result)) {
                        $position_id = $position['id'];
                        echo '<div class="position-block">';
                        echo '<div class="position-title">' . htmlspecialchars($position['title']) . '</div>';
                        echo '<div style="margin-bottom:10px;color:#555;font-size:15px;">' . htmlspecialchars($position['description']) . '</div>';
                        $votes_pos_query = "SELECT COUNT(*) as votes_cast FROM votes WHERE position_id = $position_id";
                        $votes_pos_result = mysqli_query($conn, $votes_pos_query);
                        $votes_cast = 0;
                        if ($votes_pos_result) {
                            $votes_pos_row = mysqli_fetch_assoc($votes_pos_result);
                            $votes_cast = $votes_pos_row['votes_cast'];
                        }
                        $voters_query = "SELECT COUNT(*) as total_voters FROM voters";
                        $voters_result = mysqli_query($conn, $voters_query);
                        $voters_count = 1;
                        if ($voters_result) {
                            $voters_row = mysqli_fetch_assoc($voters_result);
                            $voters_count = max(1, $voters_row['total_voters']);
                        }
                        $percent = round(($votes_cast / $voters_count) * 100, 1);
                        echo '<div style="margin-bottom:10px;font-size:14px;color:#1976d2;font-weight:600;">Voting Completed: ' . $votes_cast . ' / ' . $voters_count . ' (' . $percent . '%)</div>';
                        $candidates_query = "SELECT * FROM candidates WHERE position_id = $position_id AND status = 'approved'";
                        $candidates_result = mysqli_query($conn, $candidates_query);
                        $voted_query = "SELECT candidate_id FROM votes WHERE voter_id = '$voter_id' AND position_id = $position_id";
                        $voted_result = mysqli_query($conn, $voted_query);
                        $voted_candidate_id = null;
                        if ($voted_result && mysqli_num_rows($voted_result) > 0) {
                            $vote_row = mysqli_fetch_assoc($voted_result);
                            $voted_candidate_id = $vote_row['candidate_id'];
                        }
                        echo '<table class="candidates-table">';
                        echo '<thead><tr><th>S.No</th><th>Name</th><th>About</th><th>Photo</th><th>Action</th></tr></thead>';
                        echo '<tbody>';
                        if ($candidates_result && mysqli_num_rows($candidates_result) > 0) {
                            $sno = 1;
                            while ($candidate = mysqli_fetch_assoc($candidates_result)) {
                                echo '<tr>';
                                echo '<td>' . $sno++ . '</td>';
                                echo '<td>' . htmlspecialchars($candidate['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($candidate['about']) . '</td>';
                                echo '<td><img src="../../include/photo/' . htmlspecialchars($candidate['photo']) . '" alt="Photo" style="width:48px;height:48px;border-radius:50%;object-fit:cover;"></td>';
                                echo '<td>';
                                if ($voted_candidate_id) {
                                    if ($voted_candidate_id == $candidate['id']) {
                                        echo '<button class="vote-btn" disabled>Voted</button>';
                                    } else {
                                        echo '<button class="vote-btn" disabled>Can\'t vote</button>';
                                    }
                                } else {
                                    echo '<form method="POST" style="display:inline;">';
                                    echo '<input type="hidden" name="vote_candidate_id" value="' . $candidate['id'] . '">';
                                    echo '<input type="hidden" name="vote_position_id" value="' . $position_id . '">';
                                    echo '<button type="submit" class="vote-btn">Vote</button>';
                                    echo '</form>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" style="text-align:center;">No candidates for this position.</td></tr>';
                        }
                        echo '</tbody></table>';
                        echo '</div>';
                    }
                } else {
                    echo '<div style="text-align:center;">No positions available.</div>';
                }

                if (isset($_POST['vote_candidate_id']) && isset($_POST['vote_position_id'])) {
                    $vote_candidate_id = $_POST['vote_candidate_id'];
                    $vote_position_id = intval($_POST['vote_position_id']);
                    $check_vote_query = "SELECT * FROM votes WHERE voter_id = '$voter_id' AND position_id = $vote_position_id";
                    $check_vote_result = mysqli_query($conn, $check_vote_query);
                    if ($check_vote_result && mysqli_num_rows($check_vote_result) == 0) {
                        $insert_vote_query = "INSERT INTO votes (position_id, candidate_id, voter_id) VALUES ($vote_position_id, '$vote_candidate_id', '$voter_id')";
                        if (mysqli_query($conn, $insert_vote_query)) {
                            echo '<script>alert("Vote cast successfully!"); 
                                window.location.href = "./index.php";
                                </script>';
                                exit();
                        } else {
                            echo '<script>alert("Error casting vote.");</script>';
                        }
                    } 
                    // else {
                        // echo '<script>alert("You have already voted for this position.");</script>';
                    // }
                }
            ?>
        </section>
    </div>
</body>
</html>