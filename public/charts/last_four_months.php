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

        //array('label' => 'Month', 'type' => 'string'),
        array('label' => 'username', 'type' => 'string'),
        array('label' => 'total', 'type' => 'number')

    );

    $result = find_all_pushups_monthly();
    foreach($result as $r) {
        $temp = array();
        //$temp[] = array('v' => (string) $r['year'] . '-' . $r['month']);
        $temp[] = array('v' => (string) $r['username']);
        $temp[] = array('v' => (int) $r['total']);
        $rows[] = array('c' => $temp);
    }

    $table['rows'] = $rows;
    $jsonTable = json_encode($table);

    echo $jsonTable;