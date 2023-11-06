<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$cTime=date('G:i:s');

$flight_sched_id=$_GET['recordID'];

$query_rs = "select * FROM `user` WHERE `group`=?";
$db->query($query_rs);
$db->bind(1,'Student');
$rsstudent=$db->rowset();
$rsstudent_total=$db->rowcount();
$user_id_temp="";
//$user_id_name="";

foreach ($rsstudent as $rs_rowstudent){ 
    if ($phu->get_flight_sched_user(htmlentities($flight_sched_id),htmlentities($rs_rowstudent['id']))==1){
      $user_id_temp=htmlentities($rs_rowstudent['id']);
      //$user_id_name=htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);
    }
}

//echo $user_id_name.' '.$user_id_temp;

    $query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
    $db->query($query_rs);
    $db->bind(1, $flight_sched_id);
    $rs= $db->rowsingle();

    $query_rs = "select * FROM `flight_touch_details` WHERE `flight_sched_id`=? ORDER BY flight_touch_details_id DESC LIMIT 1";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_id);
    $rslast=$db->rowsingle();

    //echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    //echo date("G:i:s",strtotime($_POST['take_off']));

    $SQLcrud = "UPDATE flight_touch_details SET takeoff=?, user_id=? WHERE flight_touch_details_id=?";
    $db->query($SQLcrud);
    $db->bind(1,date("G:i:s",strtotime($cTime)));
    $db->bind(2,$user_id_temp);
    $db->bind(3,htmlentities($rslast['flight_touch_details_id']));
    $db->execute();

    $SQLcrud = "INSERT flight_touch_details (`flight_sched_id`, flight_date, landing,takeoff) VALUES (?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,$flight_sched_id);
    $db->bind(2,htmlentities($rs['flight_date']));
    $db->bind(3,'');
    $db->bind(4,'');
    $db->execute();

    
    $GoTo = "flight_plan_update_touch.php?recordID=".$flight_sched_id;
    header(sprintf("Location: %s", $GoTo));


?>