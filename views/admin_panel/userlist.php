<div class="UserList">
    <table id="adminUserList">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date registered</th>
                <th>Last logged</th>
                <th>Confirmed</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['first_name']; ?></td>
                <td><?php echo $user['last_name']; ?></td>
                <td><?php echo $user['date_registered']; ?></td>
                <td><?php echo $user['last_logged']; ?></td>
                <td>
                <?php if ($user['confirmed'] == 1): ?>
                        <span class="confirmed">Yes</span>
                    <?php else: ?>
                        <span class="not-confirmed">No</span>
                    <?php endif; ?>    
                </td>
                <td><?php echo $user['user_role']; ?></td>
                <td>
                    <a href="admin/edituser/<?php echo $user['id'] ?>">Edit</a>
                    <a href="admin/deleteuser/<?php echo $user['id'] ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="addUserButton"><a href="/admin/adduser">Add User</a></button>
</div>