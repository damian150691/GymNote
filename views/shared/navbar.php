<nav id ="nav-bar">
    <a href="/dashboard"><i class="fa-solid fa-table-columns" style="color: #ffffff;"></i><span class="textlink">Dashboard</span></a>
    <a href="/myplans"><i class="fa-regular fa-clipboard" style="color: #ffffff;"></i><span class="textlink">My plans</span></a>
    <a href="/makenewplan"><i class="fa-solid fa-square-plus" style="color: #ffffff;"></i><span class="textlink">Make a plan</span></a>
    
    <?php if (isset($_SESSION['username'])) : ?>
    <div class="dropdown">
        <a class="dropbtn" href="/profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i><span class="textlink userLink"><?php echo $_SESSION['username']?></span></a>
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