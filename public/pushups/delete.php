<?php

  require_once('../../private/initialize.php');
  require_login();

  $page_title = 'Delete Record';
  $id = $_GET['id'];
  $pushup = find_pushup_by_id($id);
  $date = date('Y-m-d', strtotime($pushup['date']));
  $user = find_user_by_id($pushup['user_id']);

  if(!isset($_GET['id'])) {
    redirect_to(url_for('/'));
  }

  if (is_post_request()) {

    $result = delete_pushup($id);
    if ($result === true) {
      $_SESSION['message'] = '<strong>Success:</strong> Record has been deleted.';
      redirect_to(url_for('/users/show.php?id=' . h(u($pushup['user_id']))));
    } else {
      $errors = $result;
    }
  } else {
    $_SESSION['message'] = '<strong>Warning:</strong> Choose "Confirm Delete" to remove this record.';
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
        <h3>Delete Record</h3>
        <div class="actions mb-3">
          <a class="btn btn-primary btn-sm" href="<?php echo url_for('/pushups/show.php?id=' . h(u($pushup['id']))); ?>">Cancel</a>
        </div>
        <?php echo display_errors($errors); ?>
        <form action="<?php echo url_for('/pushups/delete.php?id=' . h(u($id))); ?>" method="post">
          <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo h($date); ?>" disabled />
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo h($user['username']); ?>" disabled />
          </div>
          <div class="form-group">
            <label for="amount"># of push-ups</label>
            <input type="text" class="form-control" id="amount" name="amount" value="<?php echo h($pushup['amount']); ?>" disabled />
          </div>
          <div class="form-group">
            <label for="comment">Comment</label>
            <input type="text" class="form-control" id="comment" name="comment" value="<?php echo h($pushup['comment']); ?>" disabled />
          </div>
          <input class="btn btn-primary" type="submit" name="submit" value="Confirm Delete" />
        </form>
      </div>
    </div><!-- row -->
  </div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>