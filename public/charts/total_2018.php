<?php
  require_once('../../private/initialize.php');

  $labels = array();
  $series = array();

  $result = chart_total_by_year('2018');

  foreach($result as $r) {
    $labels[] = "'" . (string)$r['username'] . "'";
    $series[] = (int)$r['total'];
  }

  $chart_labels = implode(', ', $labels);
  $chart_series = implode(', ', $series);

  echo $chart_labels . '<br />';
  echo $chart_series;