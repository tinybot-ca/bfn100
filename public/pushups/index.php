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
            $_SESSION['message'] = 'SUCCESS - New subject created.';
            $new_id = mysqli_insert_id($db);
            redirect_to(url_for('/staff/subjects/show.php?id=' . $new_id));
        } else {
            $errors = $result;
        }

    } else {
        // display the blank form
    }

    $subject_set = find_all_subjects();
    $subject_count = mysqli_num_rows($subject_set) + 1;
    mysqli_free_result($subject_set);

    $subject = [];
    $subject['position'] = $subject_count;

?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div class="container">
    <h3 class="mt-3">Login</h3>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/pushups/new.php'); ?>" method="post">
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


    <div id="content">

        <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

        <div class="subject new">
            <h1>Create Subject</h1>

            <?php echo display_errors($errors); ?>

            <form action="<?php echo url_for('/staff/subjects/new.php'); ?>" method="post">
                <dl>
                    <dt>Menu Name</dt>
                    <dd><input type="text" name="menu_name" value="" /></dd>
                </dl>
                <dl>
                    <dt>Position</dt>
                    <dd>
                        <select name="position">
                            <?php
                            for ($i=1; $i <= $subject_count; $i++) {
                                echo "<option value=\"{$i}\"";
                                if ($subject["position"] == $i) {
                                    echo " selected";
                                }
                                echo ">{$i}</option>";
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
                <dl>
                    <dt>Visible</dt>
                    <dd>
                        <input type="hidden" name="visible" value="0" />
                        <input type="checkbox" name="visible" value="1" />
                    </dd>
                </dl>
                <div id="operations">
                    <input type="submit" value="Create Subject" />
                </div>
            </form>

        </div>

    </div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>