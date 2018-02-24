<?php
  require_once('../../private/initialize.php');

  $page_title = 'Push-Up Detail';
  $id = $_GET['id'] ?? '1';
  $pushup = find_pushup_by_id($id);
  $user = find_user_by_id($pushup['user_id']);

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h3>Record Detail</h3>
      <div class="actions mb-3">
        <a class="btn btn-primary btn-sm" href="<?php echo url_for('/users/show.php?id=' . h(u($pushup['user_id']))); ?>">Back to User</a>
        <a class="btn btn-primary btn-sm <?php if(!is_profile_owner_or_admin($pushup['user_id'])) { echo "d-none"; } ?>" href="<?php echo url_for('/pushups/edit.php?id=' . h(u($pushup['id']))); ?>">Edit</a>
        <a class="btn btn-primary btn-sm <?php if(!is_profile_owner_or_admin($pushup['user_id'])) { echo "d-none"; } ?>" href="<?php echo url_for('/pushups/delete.php?id=' . h(u($pushup['id']))); ?>">Delete</a>
      </div>

      <!-- Push-Up Detail -->
      <div class="attributes">
        <dl>
          <dt>Record ID</dt>
          <dd><?php echo h($pushup['id']); ?></dd>
        </dl>
        <dl>
          <dt>Date</dt>
          <dd><?php echo date('l, F j, Y', strtotime(h($pushup['date']))); ?></dd>
        </dl>
        <dl>
          <dt>Username</dt>
          <dd><?php echo h($pushup['username']); ?></dd>
        </dl>
        <dl>
          <dt># of push-ups</dt>
          <dd><?php echo h($pushup['amount']); ?></dd>
        </dl>
        <dl>
          <dt>Comment</dt>
          <dd><?php echo h($pushup['comment']); ?></dd>
        </dl>
      </div>

    </div><!-- col-md-12 -->
  </div><!-- row -->
</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>
