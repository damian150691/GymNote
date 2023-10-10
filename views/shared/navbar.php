<nav id ="nav-bar">
    <a href="#">My plans</a>
    <a href="makenewplan.php">Make a plan</a>
    
    <?php if (isset($_SESSION['username'])) : ?>
    <div class="dropdown">
        <a class="dropbtn" href="profile.php"><?php echo $_SESSION['username']?></a>
        <div class="dropdown-content">
           <!-- adding admin panel if the role of the user is admin -->
            <?php 
            if ($_SESSION['user_role'] == 'admin') {
                echo '<a href="adminpanel.php">Admin panel</a>';
            } 
            ?>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <?php endif ?>
    
    
</nav>