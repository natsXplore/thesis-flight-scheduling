<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$db=new DatabaseConnect();

$flight_touch_details_id=$_GET['recordID'];

$flight_sched_id=$_GET['sched_id'];

$query_rs = "select * FROM `flight_touch_details` WHERE `flight_touch_details_id`=?";
$db->query($query_rs);
$db->bind(1,$flight_touch_details_id);
$rs=$db->rowsingle();

if ($rs['landing']=="HANGAR"){
    $query_rs = "UPDATE `flight_touch_details` SET takeoff='', total_time='00:00', user_id='0' WHERE `flight_touch_details_id`=?";
    $db->query($query_rs);
    $db->bind(1,$flight_touch_details_id);
    $db->execute();

    $query_rs = "DELETE FROM `flight_touch_details` WHERE `flight_sched_id`=? AND landing !=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_id);
    $db->bind(2,'HANGAR');
    $db->execute();

}else if (($rs['takeoff']!="" && $rs['landing']!="")){
    $query_rs = "UPDATE `flight_touch_details` SET takeoff='', total_time='00:00', user_id='0' WHERE `flight_touch_details_id`=?";
    $db->query($query_rs);
    $db->bind(1,$flight_touch_details_id);
    $db->execute();

}else if (($rs['takeoff']=="" && $rs['landing']!="")){
    $query_rs = "DELETE FROM `flight_touch_details` WHERE `flight_touch_details_id`=? AND landing !=?";
    $db->query($query_rs);
    $db->bind(1,$flight_touch_details_id);
    $db->bind(2,'HANGAR');
    $db->execute();

    $query_rs = "select * FROM `flight_touch_details`  ORDER BY flight_touch_details_id DESC LIMIT 1";
    $db->query($query_rs);
    $rscheck=$db->rowsingle();

    $query_rs = "UPDATE `flight_touch_details` SET total_time='00:00', takeoff='', user_id='0' WHERE `flight_touch_details_id`=?";
    $db->query($query_rs);
    $db->bind(1,$rscheck['flight_touch_details_id']);
    $db->execute();
    
}else if (($rs['takeoff']=="" && $rs['landing']=="")){
    $query_rs = "DELETE FROM `flight_touch_details` WHERE `flight_touch_details_id`=? AND landing !=?";
    $db->query($query_rs);
    $db->bind(1,$flight_touch_details_id);
    $db->bind(2,'HANGAR');
    $db->execute();

    $query_rs = "select * FROM `flight_touch_details` ORDER BY flight_touch_details_id DESC LIMIT 1";
    $db->query($query_rs);
    $rscheck=$db->rowsingle();

    $query_rs = "UPDATE `flight_touch_details` SET total_time='00:00', takeoff='', user_id='0'  WHERE `flight_touch_details_id`=?";
    $db->query($query_rs);
    $db->bind(1,$rscheck['flight_touch_details_id']);
    $db->execute();
    
}


$GoTo = "flight_plan_update_touch.php?recordID=".$flight_sched_id;
header(sprintf("Location: %s", $GoTo));


$db->close();
?>
