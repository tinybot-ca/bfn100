<?php
    require_once('../private/initialize.php');

    $page_title = 'Login';

    $errors = [];
    $username = '';
    $password = '';

    if(is_post_request()) {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validations
        if(is_blank($username)) {
            $errors[] = "Username cannot be blank.";
        }
        if(is_blank($password)) {
            $errors[] = "Password cannot be blank.";
        }

        // if there were no errors, try to login
        if(empty($errors)) {
            $user = find_user_by_username($username);
            // using one variable ensures that error message is the same
            $login_failure_message = "Login was unsuccessful.";
            if ($user) {
                if (password_verify($password, $user['hashed_password'])) {
                    // password matches
                    log_in_user($user);
                    $_SESSION['message'] = 'Welcome back, <strong>' . $user['first_name'] . '</strong>!';
                    redirect_to(url_for('/index.php'));
                } else {
                    // username found but password does not match
                    $errors[] = $login_failure_message;
                }
            } else {
                // no username found
                $errors[] = $login_failure_message;
            }
        }
    }

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
    <h3 class="mt-3">Login</h3>

    <?php echo display_errors($errors); ?>

    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo h($username); ?>" />
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="" />
        </div>
        <input class="btn btn-primary" type="submit" name="submit" value="Submit" />
    </form>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
