<nav id ="nav-bar">
    <a href="/dashboard"><img class="icon" src="../img/icons/dashboard.png" /><span class="textlink">Dashboard</span></a>
    <a href="/myplans"><img class="icon" src="../img/icons/myplans.png" /><span class="textlink">My plans</span></a>
    <a href="/makenewplan"><img class="icon" src="../img/icons/mnp.png" /><span class="textlink">Make a plan</span></a>
    
    <?php if (isset($_SESSION['username'])) : ?>
    <div class="dropdown">
        <a class="dropbtn" href="/profile"><img class="icon" src="../img/icons/user.png" /><span class="textlink userLink"><?php echo $_SESSION['username']?></span></a>
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