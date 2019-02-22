<?php
    $output = array(
        "draw"				=>	intval($_POST["draw"]),
        "recordsTotal"		=> 	$filtered_rows,
        "recordsFiltered"	=>	$totalRow,
        "data"				=>	$data
    );
    echo json_encode($output);