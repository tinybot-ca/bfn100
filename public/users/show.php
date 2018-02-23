<?php
    require_once('../../private/initialize.php');
    //require_login();

    $page_title = 'User Detail';
    $id = $_GET['id'] ?? '1';
    $user = find_user_by_id($id);
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h3>User Profile</h3>
      <div class="actions mb-3">
          <a class="btn btn-primary btn-sm" href="<?php echo url_for('/users/index.php'); ?>">Back to List</a>
          <a class="btn btn-primary btn-sm" href="<?php echo url_for('/users/edit.php?id=' . h(u($user['id']))); ?>">Edit</a>
          <a class="btn btn-primary btn-sm" href="<?php echo url_for('/users/delete.php?id=' . h(u($user['id']))); ?>">Delete</a>
      </div>

      <!-- User Profile -->
      <div class="attributes">
          <dl>
              <dt>First Name</dt>
              <dd><?php echo h($user['first_name']); ?></dd>
          </dl>
          <dl>
              <dt>Last Name</dt>
              <dd><?php echo h($user['last_name']); ?></dd>
          </dl>
          <dl>
              <dt>Email</dt>
              <dd><?php echo h($user['email']); ?></dd>
          </dl>
          <dl>
              <dt>Username</dt>
              <dd><?php echo h($user['username']); ?></dd>
          </dl>
      </div>

      <!-- Historical Push-up Activity -->

    </div><!-- col-md-12 -->
  </div><!-- row -->
</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>
