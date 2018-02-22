<?php
  require_once('../../private/initialize.php');

  $result = chart_total_by_year();
  $year_labels = array();

  while($row = mysqli_fetch_assoc($result)) {
    $year_labels[] = "'" . $row['year'] . "'";
  }

  $year_labels_unique = array_unique($year_labels);

  echo implode(', ', $year_labels_unique);