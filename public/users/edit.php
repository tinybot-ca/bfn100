<?php

    require_once('../../private/initialize.php');
    require_login();

    $page_title = 'Edit User';

    if (!isset($_GET['id'])) {
        redirect_to(url_for('/users/index.php'));
    }

    $id = $_GET['id'];

    if (is_post_request()) {
        $user = [];
        $user['id'] = $id;
        $user['first_name'] = $_POST['first_name'] ?? '';
        $user['last_name'] = $_POST['last_name'] ?? '';
        $user['email'] = $_POST['email'] ?? '';
        $user['username'] = $_POST['username'] ?? '';
        $user['password'] = $_POST['password'] ?? '';
        $user['confirm_password'] = $_POST['confirm_password'] ?? '';

        $result = update_user($user);
        if ($result === true) {
            $_SESSION['message'] = '<strong>Success:</strong> User updated.';
            redirect_to(url_for('/users/show.php?id=' . $id));
        } else {
            $errors = $result;
        }
    } else {
        $user = find_user_by_id($id);
    }

    $user['password'] = '';
    $user['confirm_password'] = '';
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">

    <h3 class="mt-3">Edit User</h3>

    <div class="actions mb-3">
        <a class="btn btn-primary btn-sm" href="<?php echo url_for('/users/index.php'); ?>">Back to List</a>
    </div>

    <?php echo display_errors($errors); ?>

    <form class="form" action="<?php echo url_for('/users/edit.php?id=' . h(u($id))); ?>" method="post">
        <dl>
            <dt>First Name</dt>
            <dd><input type="text" name="first_name" value="<?php echo h($user['first_name']); ?>" /></dd>
        </dl>
        <dl>
            <dt>Last Name</dt>
            <dd><input type="text" name="last_name" value="<?php echo h($user['last_name']); ?>" /></dd>
        </dl>
        <dl>
            <dt>Email</dt>
            <dd><input type="text" name="email" value="<?php echo h($user['email']); ?>" /></dd>
        </dl>
        <dl>
            <dt>Username</dt>
            <dd><input type="text" name="username" value="<?php echo h($user['username']); ?>" /></dd>
        </dl>
        <dl>
            <dt>Password</dt>
            <dd><input type="password" name="password" value="" /></dd>
        </dl>
        <dl>
            <dt>Confirm Password</dt>
            <dd><input type="password" name="confirm_password" value="" /></dd>
        </dl>
        <p>Passwords should be at least 8 characters and include at least one uppercase letter, lowercase letter, number, and symbol.</p>
        <div id="operations">
            <input class="btn btn-success" type="submit" value="Edit User" />
        </div>
    </form>

</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>
