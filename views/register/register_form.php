<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
</div>
<div id="content">
    <div class="spcr"></div>
    <h1>Register</h1>
    <form method="post" action="/register" id="register_form">
    <?php 
        

    
        require_once "../views/shared/errors.php";
        
    ?>

        <div class="input-group">
            <label>Username:</label>
            <input type="text" name="username">
        </div>
        <div class="input-group">
            <label>E-mail:</label>
            <input type="email" name="email">
        </div>
        <div class="input-group">
            <label>Password:</label>
            <input type="password" name="password">
        </div>
        <div class="input-group">
            <label>Confirm password:</label>
            <input type="password" name="confirm_password">
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="register_user">
                Ok
            </button>
        </div>
    </form>
    <a href="/login">Log in</a>
</div>
    