<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
</div>
<div id="content">
    <div class="spcr"></div>
    <h1>Log in:</h1>
    
    <form action="/login" method="post" id="log-in">
        
    <?php require_once "../views/shared/errors.php";?>

        <div class="input-group">
            <label>Username or e-mail:</label>
            <input type="text" name="login_input" >
        </div>
        <div class="input-group">
            <label>Password:</label>
            <input type="password" name="password">
        </div>
        <label for="remember_me">Remember Me</label>
    <input type="checkbox" name="remember_me" id="remember_me">
        <div class="input-group">
            <button type="submit" class="btn"
                        name="login_user">
                Ok
            </button>
        </div>

    </form>
    <a href="/register">Register</a>
    <a href="/forgotpassword">Forgot password?</a>
    
</div>
