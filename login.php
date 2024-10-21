<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Staff Login</title>
    <link rel="stylesheet" href="styles/css/login-style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box admin-login">
            <h2>Admin Login</h2>
            <form action="login_admin.php" method="POST">
                <div class="form-group">
                    <label for="admin-username">Username:</label>
                    <input type="text" id="admin-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="admin-password">Password:</label>
                    <input type="password" id="admin-password" name="password" required>
                </div>
                <button type="submit" class="login-button admin-button">Login as Admin</button>
            </form>
        </div>

        <div class="login-box staff-login">
            <h2>Staff Login</h2>
            <form action="login_staff.php" method="POST">
                <div class="form-group">
                    <label for="staff-username">Username:</label>
                    <input type="text" id="staff-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="staff-password">Password:</label>
                    <input type="password" id="staff-password" name="password" required>
                </div>
                <button type="submit" class="login-button staff-button">Login as Staff</button>
            </form>
        </div>
    </div>
</body>
</html>
