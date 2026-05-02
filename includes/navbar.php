<nav class="navbar navbar-expand-lg navbar-light navbarcss">
    <div class="container">
        <a class="navbar-logo" href="index.php">
            <img src="assets/ChatGPT Image Mar 31, 2026, 09_50_31 PM.png" height="40px" alt="Sugar Bans">
        </a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="menu.php">Our Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><h4 class="nav-link">Welcome, <?php echo $_SESSION['user_fname']; ?></h4></li>
                    <li class="nav-item"><a class="nav-link" href="login.php?action=logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">LogIn / Register</a></li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php 
                        $cart_count = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $cart_count += $item['quantity'];
                            }
                        }
                        if ($cart_count > 0): 
                        ?>
                            <span class="badge badge-warning" style="font-size: 10px; position: absolute; margin-left: -5px;"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>