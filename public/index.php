<?php
    require_once('../private/initialize.php');

    $pushups_set = find_all_pushups();
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<script type="text/javascript">

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.

    google.charts.setOnLoadCallback(drawChartTotal2018);
    google.charts.setOnLoadCallback(drawChartTotal2017);
    google.charts.setOnLoadCallback(drawChartTotalByMonth);

    function drawChartTotal2018() {

        var jsonData = $.ajax({
            url: "<?php echo url_for('/charts/total_2018.php'); ?>",
            dataType: "json",
            async: false
        }).responseText;

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

        var options = {
            title: '2018',
            is3D: 'false',
            width: 300,
            height: 300
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('total_2018'));
        chart.draw(data, options);
    }

    function drawChartTotal2017() {

        var jsonData = $.ajax({
            url: "<?php echo url_for('/charts/total_2017.php'); ?>",
            dataType: "json",
            async: false
        }).responseText;

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

        var options = {
            title: '2017',
            is3D: 'false',
            width: 300,
            height: 300
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('total_2017'));
        chart.draw(data, options);
    }

    function drawChartTotalByMonth() {

        var jsonData = $.ajax({
            url: "<?php echo url_for('/charts/total_by_month.php'); ?>",
            dataType: "json",
            async: false
        }).responseText;

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

        var options = {
            title: 'Monthly',
            is3D: 'false',
            width: 300,
            height: 300
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('total_by_month'));
        chart.draw(data, options);
    }

</script>

<div class="container">

    <!-- Latest Activity -->
    <h3 class="mt-3">Recent Activity</h3>
    <a class="btn btn-primary btn-sm mb-3" href="<?php echo url_for('/pushups/new.php'); ?>">Submit</a>
    <table class="table table-hover table-responsive-sm">
        <tbody>
        <?php while($pushup = mysqli_fetch_assoc($pushups_set)) { ?>
            <tr>
                <td>
                    <strong><?php echo date('D, M j',strtotime(h($pushup['date']))); ?></strong> <u><?php echo h($pushup['username']); ?></u> completed <?php echo h($pushup['amount']); ?> push-ups. <?php if($pushup['comment']) { echo '<i>"' . h($pushup['comment']) . '"</i>'; } ?>
                </td>
            </tr>
        <?php } // while ?>
        </tbody>
    </table>

    <!-- OLD TABLE
    <table class="table table-hover table-sm table-responsive-sm">
        <thead class="thead-light">
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Amount</th>
                <th>Comment</th>
            </tr>
        </thead>

        <tbody>
        <?php $pushups_set = find_all_pushups(); while($pushup = mysqli_fetch_assoc($pushups_set)) { ?>
            <tr>
                <td><?php echo date('D, M j, Y',strtotime(h($pushup['date']))); ?></td>
                <td><?php echo h($pushup['username']); ?></td>
                <td><?php echo h($pushup['amount']); ?></td>
                <td><?php echo h($pushup['comment']); ?></td>
            </tr>
        <?php } // while ?>
        </tbody>
    </table>
    -->
    <!-- Google Charts -->
    <h3 class="mt-3">Charts</h3>

    <div id="total_2018"></div>
    <div id="total_2017"></div>
    <div id="total_by_month"></div>

</div><!-- container -->

<?php include(SHARED_PATH . '/public_footer.php'); ?>