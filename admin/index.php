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
    <link rel="stylesheet" href="../include/css/admin_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="layout">
        <?php include "./pages/sidebar.php"?>
        <main class="content">
            <header class="topbar">
                <h1 class="title">Admin Dashboard</h1>
                <div class="user-badge">
                    <img src="<?php echo "../include/photo/".$_SESSION['user']['photo']; ?>" alt="Profile" style="width:32px;height:30px;border-radius:50%;margin-right:10px;vertical-align:middle;object-fit:cover;">
                    Admin
                </div>
            </header>
            <section class="stats-cards">
                <?php
                    $voters = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM voters"));
                    $candidates = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM candidates"));
                    $positions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM positions"));
                    $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM candidates WHERE status = 'pending'"));
                ?>
                <div class="card">
                    <h3>Total Voters</h3>
                    <p class="metric"><?php echo $voters['cnt']; ?></p>
                </div>
                <div class="card">
                    <h3>Total Candidates</h3>
                    <p class="metric"><?php echo $candidates['cnt']; ?></p>
                </div>
                <div class="card">
                    <h3>Total Positions</h3>
                    <p class="metric"><?php echo $positions['cnt']; ?></p>
                </div>
                <div class="card">
                    <h3>Pending Approvals</h3>
                    <p class="metric"><?php echo $pending['cnt']; ?></p>
                </div>
            </section>
            <section class="graph-section">
                <div class="graph-title">Voting by Position</div>
                <canvas id="votesByPosition" style="max-width:900px;width:100%;height:340px;"></canvas>
                <div class="graph-title" style="margin-top:32px;">Votes by Candidate (Stacked)</div>
                <canvas id="votesByCandidate" style="max-width:900px;width:100%;height:340px;"></canvas>
            </section>
            <script>
            <?php
            $positions = [];
            $votesPerPosition = [];
            $candidateNames = [];
            $candidateVotes = [];
            $position_ids = [];
            $positions_query = "SELECT * FROM positions";
            $positions_result = mysqli_query($conn, $positions_query);
            if ($positions_result && mysqli_num_rows($positions_result) > 0) {
                while ($position = mysqli_fetch_assoc($positions_result)) {
                    $positions[] = $position['title'];
                    $position_ids[] = $position['id'];
                    $votes_pos_query = "SELECT COUNT(*) as votes_cast FROM votes WHERE position_id = {$position['id']}";
                    $votes_pos_result = mysqli_query($conn, $votes_pos_query);
                    $votes_cast = 0;
                    if ($votes_pos_result) {
                        $votes_pos_row = mysqli_fetch_assoc($votes_pos_result);
                        $votes_cast = $votes_pos_row['votes_cast'];
                    }
                    $votesPerPosition[] = $votes_cast;
                }
            }
            foreach ($position_ids as $pid) {
                $candidates_query = "SELECT c.name, COUNT(v.candidate_id) as vote_count FROM candidates c LEFT JOIN votes v ON c.id = v.candidate_id WHERE c.position_id = $pid AND c.status = 'approved' GROUP BY c.id ORDER BY c.name ASC";
                $candidates_result = mysqli_query($conn, $candidates_query);
                $names = [];
                $votes = [];
                if ($candidates_result && mysqli_num_rows($candidates_result) > 0) {
                    while ($candidate = mysqli_fetch_assoc($candidates_result)) {
                        $names[] = $candidate['name'];
                        $votes[] = $candidate['vote_count'];
                    }
                }
                $candidateNames[] = $names;
                $candidateVotes[] = $votes;
            }
            ?>
            const positions = <?php echo json_encode($positions); ?>;
            const votesPerPosition = <?php echo json_encode($votesPerPosition); ?>;
            const candidateNames = <?php echo json_encode($candidateNames); ?>;
            const candidateVotes = <?php echo json_encode($candidateVotes); ?>;
            const ctx1 = document.getElementById('votesByPosition').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: positions,
                    datasets: [{
                        label: 'Votes',
                        data: votesPerPosition,
                        backgroundColor: 'rgba(25, 118, 210, 0.7)',
                        borderColor: 'rgba(25, 118, 210, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            const ctx2 = document.getElementById('votesByCandidate').getContext('2d');
            const datasets = candidateNames.map((names, i) => {
                return names.map((name, j) => ({
                    label: positions[i] + ': ' + name,
                    data: candidateVotes[i].map((v, k) => k === j ? v : 0),
                    backgroundColor: `hsl(${(i*60+j*30)%360},70%,60%)`,
                    stack: 'Stack ' + i
                }));
            }).flat();
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: [].concat(...candidateNames),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: true } },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
            </script>
        </main>
    </div>
</body>
</html>