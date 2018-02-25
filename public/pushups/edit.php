<?php

  require_once('../../private/initialize.php');
  require_login();

  $page_title = 'Edit Push-Up';
  $id = $_GET['id'];

  if (!isset($_GET['id'])) {
    redirect_to(url_for('/'));
  }

  if (is_post_request()) {
    $pushup = [];
    $pushup['id'] = $id;
    $pushup['date'] = $_POST['date'] ?? '';
    $pushup['amount'] = $_POST['amount'] ?? '';
    $pushup['comment'] = $_POST['comment'] ?? '';

    $result = update_pushup($pushup);
    if ($result === true) {
      $_SESSION['message'] = '<strong>Success:</strong> Changes have been made.';
      redirect_to(url_for('/pushups/show.php?id=' . $pushup['id']));
    } else {
      $errors = $result;
    }
  } else {
    $pushup = find_pushup_by_id($_GET['id']);
    $date = date('Y-m-d', strtotime($pushup['date']));
    $user = find_user_by_id($pushup['user_id']);
    // Kick user out if they are not an admin or push-up record owner
    if(!is_profile_owner_or_admin($pushup['user_id'])) {
      $_SESSION['message'] = '<strong>Warning:</strong> You are not the owner of this record.';
      redirect_to(url_for('/'));
    }
  }
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h3>Edit Record</h3>
      <div class="actions mb-3">
        <a class="btn btn-primary btn-sm" href="<?php echo url_for('/pushups/show.php?id=' . h(u($pushup['id']))); ?>">Cancel</a>
      </div>
      <?php echo display_errors($errors); ?>
      <form action="<?php echo url_for('/pushups/edit.php?id=' . h(u($id))); ?>" method="post">
        <div class="form-group">
          <label for="date">Date</label>
          <input type="date" class="form-control" id="date" name="date" value="<?php echo h($date); ?>" />
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
  </div><!-- row -->
</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>