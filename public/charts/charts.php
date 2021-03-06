<?php

  // Total by Year - Categories
  $result = chart_total_by_year();
  $year_labels = array();
  while($row = mysqli_fetch_assoc($result)) {
    $year_labels[] = "'" . $row['year'] . "'";
  }
  $year_labels_unique = array_unique($year_labels);
  $total_by_year_categories = implode(', ', $year_labels_unique);

  // Total by Year - Series Data
  $series_bernie = array();
  $series_bernie['name'] = 'bernie';
  $series_moti = array();
  $series_moti['name'] = 'moti';
  $series_nikosuave = array();
  $series_nikosuave['name'] = 'nikosuave';
  $series_ashman = array();
  $series_ashman['name'] = 'ashman';

  mysqli_data_seek($result, 0);
  while($row = mysqli_fetch_assoc($result)) {
    if($row['username'] == 'bernie') {
      $series_bernie['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'moti') {
      $series_moti['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'nikosuave') {
      $series_nikosuave['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'ashman') {
      $series_ashman['data'][] = (int)$row['total'];
    }
  }

  $series=array();
  $series[] = $series_bernie;
  $series[] = $series_moti;
  $series[] = $series_nikosuave;
  $series[] = $series_ashman;

 // Total Overall
  $series_bernie_pie = array();
  $series_moti_pie = array();
  $series_nikosuave_pie = array();
  $series_ashman_pie = array();
  $series_bernie_pie['name'] = 'bernie';
  $series_moti_pie['name'] = 'moti';
  $series_nikosuave_pie['name'] = 'nikosuave';
  $series_ashman_pie['name'] = 'ashman';

  $result = chart_total_overall();
  while($row = mysqli_fetch_assoc($result)) {
    if($row['username'] == 'bernie') {
      $series_bernie_pie['y'] = (int)$row['total'];
    } elseif($row['username'] == 'moti') {
      $series_moti_pie['y'] = (int)$row['total'];
    } elseif($row['username'] == 'nikosuave') {
      $series_nikosuave_pie['y'] = (int)$row['total'];
    } elseif($row['username'] == 'ashman') {
      $series_ashman_pie['y'] = (int)$row['total'];
    }
  }
  $series_pie = array();
  $series_pie[] = $series_bernie_pie;
  $series_pie[] = $series_moti_pie;
  $series_pie[] = $series_nikosuave_pie;
  $series_pie[] = $series_ashman_pie;

  // Total by Month
  $categories_for_chart3 = array();
  $series_chart3_bernie = array();
  $series_chart3_moti = array();
  $series_chart3_nikosuave = array();
  $series_chart3_ashman = array();
  $series_chart3_bernie['name'] = 'bernie';
  $series_chart3_moti['name'] = 'moti';
  $series_chart3_nikosuave['name'] = 'nikosuave';
  $series_chart3_ashman['name'] = 'ashman';

  $result = chart_total_by_month();
  while($row = mysqli_fetch_assoc($result)) {
    $month = (int)$row['month'];
    $categories_for_chart3[] = "'" . date("M", mktime(0, 0, 0, $month))  . "'";
    if($row['username'] == 'bernie') {
      $series_chart3_bernie['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'moti') {
      $series_chart3_moti['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'nikosuave') {
      $series_chart3_nikosuave['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'ashman') {
      $series_chart3_ashman['data'][] = (int)$row['total'];
    }
  }

  // Todo: Need to have better handling for months with null push-up values - for now, I will backfill zero entries for each missing month to make this chart work.
  // Create a process in MySQL to backfill a "zero" entry for any months that do not have any activity. Hide these zero entries from appearing in User's Activity History (e.g., set WHERE to amount > 0)
  // Ideally, update query to insert these "zero" entries on the fly instead of having these exist in the database.

  $series_chart3 = array();
  $series_chart3[] = $series_chart3_bernie;
  $series_chart3[] = $series_chart3_moti;
  $series_chart3[] = $series_chart3_nikosuave;
  $series_chart3[] = $series_chart3_ashman;

?>

<script>

  Highcharts.setOptions({
      lang: {
          thousandsSep: ','
      },
      chart: {
          backgroundColor: {
              linearGradient: [0, 0, 500, 500],
              stops: [
                  [0, 'rgb(255, 255, 255)'],
                  [1, 'rgb(240, 240, 240)']
              ]
          },
          borderWidth: 1,
          borderColor: '#DFE2E6',
          plotShadow: false,
          plotBorderWidth: 0
      }
  });

  // myChart1 -- COLUMN CHART -- Total by Year
  $(function () {
      var myChart1 = Highcharts.chart('chart1', {
          credits: {
              enabled: false
          },
          chart: {
              type: 'column'
          },
          title: {
              text: 'Annual'
          },
          subtitle: {
              text: 'Total push-ups by year'
          },
          xAxis: {
              categories: [<?php echo $total_by_year_categories; ?>]
          },
          yAxis: {
              title: {
                  text: 'Push-ups'
              }
          },
          series: <?php echo json_encode($series); ?>
      });
  });

  // myChart2 -- PIE CHART -- Total Overall
  $(function () {
      var myChart2 = Highcharts.chart('chart2', {
          credits: {
              enabled: false
          },
          chart: {
              type: 'pie'
          },
          title: {
              text: 'Overall'
          },
          subtitle: {
              text: 'Total overall push-ups'
          },
          tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  dataLabels: {
                      enabled: true,
                      format: '<b>{point.name}</b>:<br />{point.y:,.0f}',
                      style: {
                          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                      }
                  }
              }
          },
          series: [{
              name: 'Push-ups',
              colorByPoint: true,
              data: <?php echo json_encode($series_pie); ?>
          }]
      });
  });

  // myChart3 -- LINE CHART -- Total by Month
  $(function () {
      var myChart3 = Highcharts.chart('chart3', {
          credits: {
              enabled: false
          },
          chart: {
              type: 'line'
          },
          title: {
              text: 'Monthly'
          },
          subtitle: {
              text: 'Total push-ups by month'
          },
          xAxis: {
              categories: [<?php echo implode(', ', array_unique($categories_for_chart3)); ?>]
          },
          yAxis: {
              title: {
                  text: 'Push-ups'
              }
          },
          plotOptions: {
              line: {
                  dataLabels: {
                      enabled: true
                  },
                  enableMouseTracking: true
              }
          },
          series: <?php echo json_encode($series_chart3); ?>
      });
  });

</script>