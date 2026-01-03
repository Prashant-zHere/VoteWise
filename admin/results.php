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
    <title>Election Results</title>
    <style>
        .vote-count {
            font-weight: 700;
            color: #1976d2;
        }

.voting-progress-bar {
    background: #e3f2fd;
    border-radius: 8px;
    overflow: hidden;
    height: 28px;
    box-shadow: 0 1px 4px rgba(25,118,210,0.07);
    margin-top: 6px;
    margin-bottom: 18px;
    width: 100%;
    /* max-width: 500px; */
}
.voting-progress {
    background: #1976d2;
    color: #fff;
    font-weight: 600;
    height: 100%;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: width 0.4s;
    font-size: 16px;
}
    </style>
</head>
<body>
    <div class="layout">
        <?php include "./pages/sidebar.php"?>
        <main class="content">
            <header class="topbar">
                <h1 class="title">Election Results</h1>
                <div class="user-badge">
                    <img src="<?php echo "../include/photo/".$_SESSION['user']['photo']; ?>" alt="Profile" style="width:32px;height:30px;border-radius:50%;margin-right:10px;vertical-align:middle;object-fit:cover;">
                    Admin
                </div>
            </header>
            <section class="panel">
                <div style="text-align:right;margin-bottom:18px;">
                    <a href="./pages/print_results_pdf.php" target="_blank" class="btn-primary" style="float:right;padding:8px 22px;font-size:15px;text-decoration:none;">Print PDF</a>
                </div>
                <h2 class="panel-title">Overall Voting Completion</h2>
                <?php
                    $votes_query = "select COUNT(*) as total_votes from votes group by position_id";
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
                <div style="margin-bottom:10px;font-size:15px;color:#1976d2;font-weight:600;">Total Votes Cast: <?php echo $votes_count; ?> / <?php echo $voters_count; ?> voters (<?php echo $voting_percent; ?>%)</div>
                <div class="voting-progress-bar" style="margin-bottom:18px;">
                    <div class="voting-progress" style="width: <?php echo $voting_percent; ?>%;">
                        <?php echo $voting_percent; ?>%
                    </div>
                </div>
            </section>
            <section class="panel">
                <h2 class="panel-title">Results by Position</h2>
                <?php
                    $positions_query = "SELECT * FROM positions";
                    $positions_result = mysqli_query($conn, $positions_query);
                    if ($positions_result && mysqli_num_rows($positions_result) > 0) {
                        while ($position = mysqli_fetch_assoc($positions_result)) {
                            $position_id = $position['id'];
                            echo '<div class="table-container" style="margin-bottom:32px;">';
                            echo '<h3 style="color:#1565c0;font-size:1.15rem;margin-bottom:8px;">' . htmlspecialchars($position['title']) . '</h3>';
                            echo '<div style="margin-bottom:8px;color:#555;font-size:14px;">' . htmlspecialchars($position['description']) . '</div>';
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
                            echo '<div style="margin-bottom:8px;font-size:13px;color:#1976d2;font-weight:600;">Voting Completed: ' . $votes_cast . ' / ' . $voters_count . ' (' . $percent . '%)</div>';
                            $candidates_query = "SELECT c.name, c.photo, c.about, COUNT(v.candidate_id) as vote_count FROM candidates c LEFT JOIN votes v ON c.id = v.candidate_id WHERE c.position_id = $position_id AND c.status = 'approved' GROUP BY c.id ORDER BY vote_count DESC, c.name ASC";
                            $candidates_result = mysqli_query($conn, $candidates_query);
                            echo '<div class="table-container" style="margin-bottom:32px;">';
                            echo '<table class="data-table">';
                            echo '<thead>
                                    <tr>
                                        <th style="width:8%">S.No</th>
                                        <th style="width:22%">Name</th>
                                        <th style="width:40%">About</th>
                                        <th style="width:18%">Photo</th>
                                        <th style="width:12%">Votes</th>
                                        </tr>
                                  </thead>';
                            echo '<tbody>';
                            if ($candidates_result && mysqli_num_rows($candidates_result) > 0) {
                                $sno = 1;
                                while ($candidate = mysqli_fetch_assoc($candidates_result)) {
                                    echo '<tr>';
                                    echo '<td style="text-align:center;width:8%">' . $sno++ . '</td>';
                                    echo '<td style="text-align:center;width:22%">' . htmlspecialchars($candidate['name']) . '</td>';
                                    echo '<td style="width:40%">' . htmlspecialchars($candidate['about']) . '</td>';
                                    echo '<td style="text-align:center;width:18%"><img src="../include/photo/' . htmlspecialchars($candidate['photo']) . '" alt="Photo" style="width:48px;height:48px;border-radius:50%;object-fit:cover;"></td>';
                                    echo '<td class="vote-count" style="text-align:center;width:12%">' . $candidate['vote_count'] . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr class="no-results"><td colspan="5">No candidates for this position.</td></tr>';
                            }
                            echo '</tbody></table>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div style="text-align:center;">No positions available.</div>';
                    }
                ?>
            </section>
            
        </main>
    </div>
</body>
</html>