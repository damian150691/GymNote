<h3>Add new user</h3>
<?php require_once "../views/shared/errors.php"; ?>
<form id="addUser" action="/admin/adduser" method="post">
    <div class="fifty-fifty">
        <div class="form-group">
            <label for="username">Username*</label>
            <input id="username" name="username" type="text" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email address*</label>
            <input id="email" name="email" type="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password*</label>
            <input id="password" name="password" type="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm password*</label
            ><input id="confirm_password" name="confirm_password" type="password" class="form-control" required>
        </div>
    </div>
    <div class="fifty-fifty">
        <div class="form-group">
            <label for="first_name">First name</label>
            <input id="first_name" name="first_name" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="last_name">Last name</label>
            <input id="last_name" name="last_name" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="user_role">User role</label>
            <select id="user_role" name="user_role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="confirmed">Confirmed</label>
            <select id="confirmed" name="confirmed" class="form-control">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
    <div class="spcr"></div>
    <button type="submit">Add user</button>
</form>
