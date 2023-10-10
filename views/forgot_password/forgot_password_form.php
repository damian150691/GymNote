<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
</div>
<div id="content">
    <div class="spcr"></div>
    <h1>Reset password:</h1>
    
    <form method="post" action="server.php" id="forgotpassword">
    <?php require_once "../views/shared/errors.php";?>
        <div class="input-group">
            <label>E-mail:</label>
            <input type="email" name="reset_email" required>
        </div>
        <div>
            <button type="submit" name="forgot_password">OK</button>
        </div>
    </form>

    <a href="/login">Log in</a>
    <a href="/register">Register</a>
    
</div>