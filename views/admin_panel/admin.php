<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>Admin Panel</h2>
    <div class="grid">
        <div class="admin-left">
            <?php require_once "../views/admin_panel/nav.php";?>
        </div>
        <div class="admin-right">
            <?php
                require_once "../views/shared/errors.php";
                if ($url == 'userlist' || $url == 'admin') {
                    require_once "../views/admin_panel/userlist.php";
                } else if (isset($isEdit)) {
                    require_once "../views/admin_panel/edituser.php";
                } else {
                    require_once "../views/admin_panel/" . $url . ".php";
                }
            ?>
        </div>
    </div>
    
    
</div>