<?php
    require_once('../../private/initialize.php');

    $rows = array();
    $table = array();
    $table['cols'] = array(

        // Labels for your chart, these represent the column titles.
        /*
            note that one column is in "string" format and another one is in "number" format
            as pie chart only required "numbers" for calculating percentage
            and string will be used for Slice title
        */

        array('label' => 'Month', 'type' => 'string'),
        array('label' => 'bernie', 'type' => 'number'),
        array('label' => 'moti', 'type' => 'number')

    );

    $result = chart_total_by_month();
    foreach($result as $r) {
        $temp = array();
        $temp[] = array('v' => (string) $r['month']);
        $temp[] = array('v' => (int) $r['bernie']);
        $temp[] = array('v' => (int) $r['moti']);
        $rows[] = array('c' => $temp);
    }

    $table['rows'] = $rows;
    $jsonTable = json_encode($table);

    echo $jsonTable;