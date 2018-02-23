<?php
  require_once('../private/initialize.php');
  $pushups_set = find_all_pushups();
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
  <div class="row">
     <div class="col-md-12">
      <!-- Latest Activity -->
      <h3>Recent Activity</h3>
      <a class="btn btn-primary btn-sm mb-3" href="<?php echo url_for('/pushups/new.php'); ?>">Submit</a>
      <table class="table table-hover table-responsive-sm">
        <tbody>
        <?php while($pushup = mysqli_fetch_assoc($pushups_set)) { ?>
          <tr class="">
            <td class="pl-0 pr-0">
              <strong><?php echo date('D, M j',strtotime(h($pushup['date']))); ?></strong> <a href="<?php echo url_for('/users/show.php?id=' . h($pushup['user_id'])) ?>"><u><?php echo h($pushup['username']); ?></u></a> completed <?php echo h($pushup['amount']); ?> push-ups. <?php if($pushup['comment']) { echo '<i>"' . h($pushup['comment']) . '"</i>'; } ?>
            </td>
          </tr>
        <?php } // while ?>
        </tbody>
      </table>
    </div><!-- col-md-12 -->
  </div><!-- row -->

<!-- Highcharts -->
  <div class="row">
    <div class="col-md-12">
      <h3>Charts</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div id="chart1" class="mt-2 mb-2"></div>
    </div>
    <div class="col-md-6">
      <div id="chart2" class="mt-2 mb-2"></div>
    </div>
  </div><!-- row -->
  <div class="row mb-5">
    <div class="col-md-12">
      <div id="chart3" class="mt-2 mb-2"></div>
    </div>
  </div><!-- row -->

</div><!-- container -->

<?php include(PROJECT_PATH . '/public/charts/charts.php'); ?>

<?php include(SHARED_PATH . '/public_footer.php'); ?>