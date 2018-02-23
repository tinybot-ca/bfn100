<?php
    require_once('../../private/initialize.php');
    require_login();

    $page_title = 'All Users';
    $user_result = find_all_users();
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h3>Users</h3>
      <div class="actions mb-3">
          <a class="btn btn-primary btn-sm <?php if(!is_admin($_SESSION['user_id'])) { echo "d-none"; } ?>" href="<?php echo url_for('/users/new.php'); ?>">Create New User</a>
      </div>
      <table class="table table-hover table-sm table-responsive-sm">
        <thead class="thead-light">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
        <?php while($user = mysqli_fetch_assoc($user_result)) { ?>
          <tr>
            <td><?php echo h($user['id']); ?></td>
            <td><a class="action" href="<?php echo url_for('/users/show.php?id=' . h(u($user['id']))); ?>"><?php echo h($user['username']); ?></a></td>
            <td><?php echo h($user['first_name']); ?></td>
            <td><?php echo h($user['last_name']); ?></td>
            <td><?php echo h($user['email']); ?></td>
          </tr>
        <?php } // while $user ?>
        </tbody>
      </table>
    </div><!-- col-md-12 -->
  </div><!-- row -->
</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>