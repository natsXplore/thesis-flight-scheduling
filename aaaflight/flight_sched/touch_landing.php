<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
    $phu=new php_util();
    $db=new DatabaseConnect();

    $flight_sched_id=$_GET['recordID'];

    $query_rs = "select * FROM `flight_sched` WHERE `flight_sched_id`=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_id);
    $rs=$db->rowsingle();

    $query_rs = "select * FROM `flight_touch_details` WHERE `flight_sched_id`=? ORDER BY flight_touch_details_id DESC LIMIT 1";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_id);
    $rslast=$db->rowsingle();

    //$time=strtotime(date('H:i:s'));
    //$landing=date('H:i:s', strtotime('+7 minutes', $time));

    $landing=date('H:i:s');

    $SQLcrud = "UPDATE flight_touch_details SET landing=? WHERE flight_touch_details_id=?";
    $db->query($SQLcrud);
    $db->bind(1, $landing);
    $db->bind(2,htmlentities($rslast['flight_touch_details_id']));
    $db->execute();

    $SQLcrud = "UPDATE flight_sched SET etime_arrival=? WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1, $landing);
    $db->bind(2, $flight_sched_id);
    $db->execute();

    $query_rs = "select * FROM `flight_touch_details` WHERE `flight_sched_id`=? ORDER BY flight_touch_details_id DESC LIMIT 2";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_id);
    $rslast2=$db->rowset();
    
    $start_time=strtotime($rslast2[1]['takeoff']);
    $end_time= strtotime($rslast2[0]['landing']);

    //echo $rslast2[1]['takeoff'].'-'.$rslast2[0]['landing'].'<br>';
    

    $ttime=$end_time-$start_time;

    $h = intval( $ttime / 3600);
 
    $ttime =  $ttime - ($h * 3600);
    
    $m = str_pad(intval( $ttime / 60),2,"0",STR_PAD_LEFT);
    $s = str_pad(intval($ttime - ($m * 60)),2,"0",STR_PAD_LEFT);
    
    $ttime="$h:$m:$s";
 
$etatime = '0:07:00';

    // $SQLcrud = "UPDATE flight_touch_details SET total_time=? WHERE flight_touch_details_id=?";
    // $db->query($SQLcrud);
    // $db->bind(1,$ttime);
    // $db->bind(2,htmlentities($rslast2[1]['flight_touch_details_id']));
    // $db->execute();
    
    $SQLcrud = "UPDATE flight_touch_details SET total_time=?, eta=? WHERE flight_touch_details_id=?";
    $db->query($SQLcrud);
    $db->bind(1, $ttime);
    $db->bind(2, $etatime);
    $db->bind(3, htmlentities($rslast2[1]['flight_touch_details_id']));
    $db->execute();

    $GoTo = "flight_plan_update_touch.php?recordID=".$flight_sched_id;
    header(sprintf("Location: %s", $GoTo));

    $db->close();
?>

