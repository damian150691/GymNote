<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
</div>
<div id="content">
    <h1>Set new password</h1>
    
    <form method="post" action="/setnewpassword?token=<?php echo $token ?>" id="setnewpassword">
    <?php require_once "../views/shared/errors.php";?>
        <div class="input-group">
            <label>Type new password:</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="input-group">
            <label>Confirm new password:</label>
            <input type="password" name="confirm_new_password" required>
        <div>
            <button type="submit" name="setnewpassword">OK</button>
        </div>
    </form>

    <a href="/login">Log in</a>
    <a href="/register">Register</a>
    
</div>