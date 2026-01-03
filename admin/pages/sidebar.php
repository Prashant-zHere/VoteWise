<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
	<aside class="sidebar">
		<div class="brand">
			<img class="brand-logo" src="../include/img/logo.webp" alt="Logo">
			<div class="brand-text">
				<div class="brand-name">Admin Panel</div>
				<div class="brand-sub">Online Voting System</div>
			</div>
		</div>
		<nav class="nav">
			<ul>
				<li><a href="./index.php" class="nav-btn <?php echo ($current_page == 'index') ? 'active' : ''; ?>">Dashboard</a></li>
				<li><a href="./voters.php" class="nav-btn <?php echo ($current_page == 'voters') ? 'active' : ''; ?>">Voters</a></li>
				<li><a href="./candidates.php" class="nav-btn <?php echo ($current_page == 'candidates') ? 'active' : ''; ?>">Candidates</a></li>
				<li><a href="./position.php" class="nav-btn <?php echo ($current_page == 'position') ? 'active' : ''; ?>">Position</a></li>
				<li><a href="./results.php" class="nav-btn <?php echo ($current_page == 'results') ? 'active' : ''; ?>">Results</a></li>
			</ul>
		</nav>
		<div class="logout-section">
			<form action="#" method="POST">
				<button type="submit" class="logout-btn" name="logout" value="logout">
					Logout
				</button>
			</form>
		</div>
	</aside>