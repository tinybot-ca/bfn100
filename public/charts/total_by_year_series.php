<?php
  require_once('../../private/initialize.php');

  // Next, if I'm going to use the query results as-is without cross tabbing
  // (which is undesirable since I don't think I can make it dynamic)
  // I need to create a loop and array to capture data by username
  // I think I need to pull a unique list of usernames first, and then loop through
  // to get and accumulate the 'total' for each year, which is already in the order
  // that I want....

// 1 - Get a unique list of usernames

  $usernames = array();
  $result = chart_total_by_year();
  while($row = mysqli_fetch_assoc($result)) {
    $usernames[] = $row['username'];
  }
  $usernames_unique = array_unique($usernames);

  echo "$usernames_unique: ";
  print_r($usernames_unique);
  echo '<br /><br />';

  $series_bernie = array();
  $series_bernie['name'] = 'bernie';
  $series_moti = array();
  $series_moti['name'] = 'moti';
  //$result = chart_total_by_year();
  mysqli_data_seek($result, 0);
  while($row = mysqli_fetch_assoc($result)) {
    if($row['username'] == 'bernie') {
      $series_bernie['data'][] = (int)$row['total'];
    } elseif($row['username'] == 'moti') {
      $series_moti['data'][] = (int)$row['total'];
    }
  }

  print_r($series_bernie);
  echo '<br /><br />';
  print_r($series_moti);
  echo '<br /><br />';

  $series=array();
  $series[] = $series_bernie;
  $series[] = $series_moti;

  echo json_encode($series);


/*

series: [{
  name: 'bernie',
  data: [3600, 1200]
}, {
  name: 'moti',
  data: [2400, 1800]
}]



series: [{name: 'bernie', data: [3600, 1200]}, {name: 'moti', data: [2400, 1800]}]

*/