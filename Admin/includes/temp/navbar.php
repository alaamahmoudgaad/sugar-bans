
<?php session_start(); ?>
<nav class="navbar navbar-expand-md navbar-light shadow">
    
    <a class="navbar-brand font-weight-bold" href="dashboard.php">
        <img src="includes/assets/img/ChatGPT Image Mar 31, 2026, 09_50_31 PM.png" height="45px" alt="Sugar Bans">
    </a>
    
    <button class="navbar-toggler" data-toggle="collapse" data-target="#adminNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNavbar">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link btn btn-outline-light btn-sm " href="../index.php" target="_blank">
                    <i class="fas fa-eye"></i><span> View Site as User</span>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown">
                    <span>Welcome, <?php echo $_SESSION['user_fname']; ?></span><i class="fas fa-user-circle"></i>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item text-danger" href="../logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>