<?php
    require_once('../../private/initialize.php');
    //require_login();

    $page_title = 'User Detail';
    $id = $_GET['id'] ?? '1';
    $user = find_user_by_id($id);
    $pushups = find_all_pushups_by_user_id($id);
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h3>User Profile</h3>
      <div class="actions mb-3">
          <a class="btn btn-primary btn-sm" href="<?php echo url_for('/users/index.php'); ?>">View Users</a>
          <a class="btn btn-primary btn-sm <?php if(!is_profile_owner_or_admin($id)) { echo "d-none"; } ?>" href="<?php echo url_for('/users/edit.php?id=' . h(u($user['id']))); ?>">Edit</a>
          <a class="btn btn-primary btn-sm <?php if(!is_admin($_SESSION['user_id'])) { echo "d-none"; } ?>" href="<?php echo url_for('/users/delete.php?id=' . h(u($user['id']))); ?>">Delete</a>
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
      <h3>Activity History</h3>
      <table class="table table-hover table-sm table-responsive-sm">
        <thead class="thead-light">
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Comment</th>
        </tr>
        </thead>
        <tbody>
        <?php while($pushup = mysqli_fetch_assoc($pushups)) { ?>
          <tr>
            <td><a href="<?php echo url_for('/pushups/show.php?id=' . h(u($pushup['id']))); ?>"><?php echo date('Y-m-d', strtotime(h($pushup['date']))); ?></a></td>
            <td><?php echo h($pushup['amount']); ?></td>
            <td><?php echo h($pushup['comment']); ?></td>
          </tr>
        <?php } // while $pushup ?>
        </tbody>
      </table>

    </div><!-- col-md-12 -->
  </div><!-- row -->
</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>
