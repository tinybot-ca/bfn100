<?php
    require_once('../../private/initialize.php');
    require_login();

    $page_title = 'New Submission';
    $user = find_user_by_id($_SESSION['user_id']);

    if (is_post_request()) {

        $pushup = [];
        $pushup['user_id'] = $user['id'];
        $pushup['date'] = $_POST['date'] ?? '';
        $pushup['amount'] = $_POST['amount'] ?? '';
        $pushup['comment'] = $_POST['comment'] ?? '';

        $result = insert_pushup($pushup);
        if ($result === true) {
            $_SESSION['message'] = '<strong>Boom!</strong> New submission received.';
            $new_id = mysqli_insert_id($db); // not currently using
            send_email_notification($pushup);
            redirect_to(url_for('/index.php'));
        } else {
            $errors = $result;
        }

    } else {
        // display the blank form
        $pushup['date'] = date('Y-m-d');
        $pushup['amount'] = '';
        $pushup['comment'] = '';
    }
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

    <div class="container">
        <h3 class="mt-3">New Submission</h3>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/pushups/new.php'); ?>" method="post">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo h($pushup['date']); ?>" />
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo h($user['username']); ?>" disabled />
            </div>
            <div class="form-group">
                <label for="amount"># of push-ups</label>
                <input type="text" class="form-control" id="amount" name="amount" value="<?php echo h($pushup['amount']); ?>" />
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <input type="text" class="form-control" id="comment" name="comment" value="<?php echo h($pushup['comment']); ?>" />
            </div>
            <input class="btn btn-primary" type="submit" name="submit" value="Submit" />
        </form>
    </div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>