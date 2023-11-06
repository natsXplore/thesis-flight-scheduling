<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
    $phu=new php_util();
    $db=new DatabaseConnect();

    $flight_sched_id=$_GET['sched_id'];
    $flight_touch_details_id=$_GET['recordID'];
    
    $SQLcrud = "UPDATE flight_sched SET flight_status='arrived' WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,  $flight_sched_id);
    $db->execute();
    
    $SQLcrud = "UPDATE flight_touch_details SET takeoff='HANGAR' WHERE flight_touch_details_id=?";
    $db->query($SQLcrud);
    $db->bind(1, $flight_touch_details_id);
    $db->execute();

    $GoTo = "flight_plan_update_touch.php?recordID=".$flight_sched_id;
    header(sprintf("Location: %s", $GoTo));

    $db->close();
?>

