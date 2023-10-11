<nav id ="nav-bar">
    <a href="/dashboard">Dashboard</a>
    <a href="/myplans">My plans</a>
    <a href="/makenewplan">Make a plan</a>
    
    <?php if (isset($_SESSION['username'])) : ?>
    <div class="dropdown">
        <a class="dropbtn" href="/profile"><?php echo $_SESSION['username']?></a>
        <div class="dropdown-content">
           <!-- adding admin panel if the role of the user is admin -->
            <?php 
            if ($_SESSION['user_role'] == 'admin') {
                echo '<a href="/admin">Admin panel</a>';
            } 
            ?>
            <a href="/logout">Logout</a>
        </div>
    </div>
    <?php endif ?>
    
    
</nav>