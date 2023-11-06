<?php require_once('../connections/pdoconnect.php'); 

$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$action = $_POST['action'];
$var_flight_date = $_POST['var_flight_date'];


$return_list=array ();
$rsaircraft_array = "";
$rsstudent_array ="";
$rsinstructor_array = "";
$error="";

$query_rs = "select * FROM `lup_basic_settings`";
$db->query($query_rs);
$rsbasic=$db->rowsingle();

if ($action=="check_time_departure"){
    $time_interval=$phu->get_time_interval('07:00',$rsbasic['end_training_time'],$rsbasic['set_flight_time_interval'], $var_flight_date);
    $error=" ";
}

//---------------------------------------------------

$query_rs = "select * FROM `lup_aircraft` WHERE `aircraft_status`=? AND date_validity>=? AND ((lup_aircraft_id NOT IN (SELECT lup_aircraft_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (lup_aircraft_id IN (SELECT lup_aircraft_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=? AND fs.flight_status='arrived')))";
$db->query($query_rs);
$db->bind(1,'active');
$db->bind(2,$var_flight_date);
$db->bind(3,$var_flight_date);
$db->bind(4,$var_flight_date);
$rsaircraft=$db->rowset();
$rsaircraft_total=$db->rowcount();

$firsttime=true; 
foreach ($rsaircraft as $rs_rsaircraft){ 
  if ($firsttime==true) { $firsttime=false;
    $rsaircraft_array=$rsaircraft_array.'<div class="form-check form-switch col-sm-12"> <input required checked class="form-check-input" type="radio" id="aid'.htmlentities($rs_rsaircraft['lup_aircraft_id']).'" name="aid" value="'.htmlentities($rs_rsaircraft['lup_aircraft_id']).'"> <label class="form-check-label" for="">'.htmlentities($rs_rsaircraft['aircraft_id']).'</label></div>';
  }else{
    $rsaircraft_array=$rsaircraft_array.'<div class="form-check form-switch col-sm-12"> <input required class="form-check-input" type="radio" id="aid'.htmlentities($rs_rsaircraft['lup_aircraft_id']).'" name="aid" value="'.htmlentities($rs_rsaircraft['lup_aircraft_id']).'"> <label class="form-check-label" for="">'.htmlentities($rs_rsaircraft['aircraft_id']).'</label></div>';
  }
} 

$query_rs = "SELECT u.id, concat(u.firstname,' ',u.middlename,' ',u.lastname,' ',u.extname) name FROM `user` u WHERE u.`group`=? AND u.`status`=?  and DATE(u.cpl)>=? 
AND ((u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (u.id IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=? AND fs.flight_status='arrived')))";
$db->query($query_rs);
$db->bind(1,'Student');
$db->bind(2,'active');
$db->bind(3,$var_flight_date);
$db->bind(4,$var_flight_date);
$db->bind(5,$var_flight_date);
$rsstudent=$db->rowset();
$rsstudent_total=$db->rowcount();



$firsttime=true; 
foreach ($rsstudent as $rs_rsstudent){ 
  if ($firsttime==true) { $firsttime=false;
    $rsstudent_array=$rsstudent_array.'<div class="form-check form-switch col-sm-12"> <input  onchange="update_seat(\'sid'.htmlentities($rs_rsstudent['id']).'\')" checked class="form-check-input sid" type="radio" id="sid'.htmlentities($rs_rsstudent['id']).'" name="sid[]" value="'.htmlentities($rs_rsstudent['id']).'"> <label class="form-check-label" for="">'.htmlentities($rs_rsstudent['name']).'</label></div>';
  }else{
    $rsstudent_array=$rsstudent_array.'<div class="form-check form-switch col-sm-12"> <input  onchange="update_seat(\'sid'.htmlentities($rs_rsstudent['id']).'\')" class="form-check-input sid" type="radio" id="sid'.htmlentities($rs_rsstudent['id']).'" name="sid[]" value="'.htmlentities($rs_rsstudent['id']).'"> <label class="form-check-label" for="">'.htmlentities($rs_rsstudent['name']).'</label></div>';
  }
} 

$query_rs = "SELECT u.id, concat(u.firstname,' ',u.middlename,' ',u.lastname,' ',u.extname) name FROM `user` u WHERE u.`group`=? AND u.`status`=?  and DATE(u.fil)>=? 
AND ((u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (u.id IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=? AND fs.flight_status='arrived'))); ";
$db->query($query_rs);
$db->bind(1,'Instructor');
$db->bind(2,'active');
$db->bind(3,$var_flight_date);
$db->bind(4,$var_flight_date);
$db->bind(5,$var_flight_date);
$rsinstructor=$db->rowset();
$rsinstructor_total=$db->rowcount();

$firsttime=true; 
foreach ($rsinstructor as $rs_rowinstructor){ 
  if ($firsttime==true) { $firsttime=false;
    $rsinstructor_array=$rsinstructor_array.'<div class="form-check form-switch col-sm-12"> <input required checked class="form-check-input" type="radio" id="iid'.htmlentities($rs_rowinstructor['id']).'" name="iid" value="'.htmlentities($rs_rowinstructor['id']).'"> <label class="form-check-label" for="">'.htmlentities($rs_rowinstructor['name']).'</label></div>';
  }else{
    $rsinstructor_array=$rsinstructor_array.'<div class="form-check form-switch col-sm-12"> <input required class="form-check-input" type="radio" id="iid'.htmlentities($rs_rowinstructor['id']).'" name="iid" value="'.htmlentities($rs_rowinstructor['id']).'"> <label class="form-check-label" for="">'.htmlentities($rs_rowinstructor['name']).'</label></div>';
  }
} 



//--------------------------------------------------

$output = array(  
    'return_list' => json_encode($time_interval),
    'aircraft_list_name' => $rsaircraft_array,
    'student_list_name' => $rsstudent_array,
    'instructor_list_name' => $rsinstructor_array,
    'error'      =>  $error

);
             
echo json_encode($output); 
?>