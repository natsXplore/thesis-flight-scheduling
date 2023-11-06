<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  $ttime="00:00:00";

  if ($_POST['flight_status']=="arrived"){
    $start_time=strtotime($_POST['time_departure']);
    $end_time= strtotime($_POST['etime_arrival']);
  
    $ttime=$end_time-$start_time;
    $h = intval( $ttime / 3600);
    $ttime =  $ttime - ($h * 3600);
    $m = str_pad(intval( $ttime / 60),2,"0",STR_PAD_LEFT);
    $s = str_pad(intval($ttime - ($m * 60)),2,"0",STR_PAD_LEFT);
    $ttime="$h:$m:$s";
  }

    $SQLcrud = "UPDATE flight_sched SET `flight_status`=?, time_departure=?, etime_arrival=?, total_time=?, note=? WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['flight_status']));
    $db->bind(2,htmlentities($_POST['time_departure']));
    $db->bind(3,htmlentities($_POST['etime_arrival']));
    $db->bind(4,$ttime);
    $db->bind(5,htmlentities($_POST['note']));
    $db->bind(6,htmlentities($_POST['id']));
    $db->execute();
 
  
    $GoTo = "flight_plan_list.php";
    header(sprintf("Location: %s", $GoTo));
}

$query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
$db->query($query_rs);
$db->bind(1, htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();
$rs_data_total=$db->rowcount();

if ($rs_data_total>0) {
$slug=$rs_data['flight_sched_slug'];

$query_rs = "select * FROM `user` WHERE `group`=?";
$db->query($query_rs);
$db->bind(1,'Student');
$rsstudent=$db->rowset();

$query_rs = "select * FROM `user` WHERE `group`=? and `status`=?";
$db->query($query_rs);
$db->bind(1,'Instructor');
$db->bind(2,'active');
$rsinstructor=$db->rowset();

$query_rs = "select * FROM `lup_aircraft` WHERE `aircraft_status`=?";
$db->query($query_rs);
$db->bind(1,'active');
$rsaircraft=$db->rowset();

$query_rs = "select * FROM `lup_route` where route_code=?";
$db->query($query_rs);
$db->bind(1,$rs_data['route_begin']);
$rsroute_begin=$db->rowsingle();

$query_rs = "select * FROM `lup_route` where route_code=?";
$db->query($query_rs);
$db->bind(1,$rs_data['route_end']);
$rsroute_end=$db->rowsingle();

$query_rs = "select * FROM `lup_level` where `level`=?";
$db->query($query_rs);
$db->bind(1,$rs_data['level']);
$rslevel=$db->rowsingle();


}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>

</head>
    
   
<?php require_once('../template/phplink.php'); ?>

<script>
  function change_time_status(){
    const today = new Date();

    let vflight_status=document.getElementById('flight_status');
    let vtime_departure=document.getElementById('time_departure');
    let vetime_arrival=document.getElementById('etime_arrival');

    var vf=vflight_status.value;

    if (vf.localeCompare("on-flight")==0){
      vtime_departure.value=today.getHours() + ":" + today.getMinutes()+ ":" + today.getSeconds();
    }else if(vf.localeCompare("arrived")==0){
      vetime_arrival.value=today.getHours() + ":" + today.getMinutes()+ ":" + today.getSeconds();
    }
  }

</script>

<body>
<?php require_once('../template/header.php'); ?> 
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="post" name="form1" id="form1" onsubmit="return validateForm();">

<fieldset>
        <div class="row">

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
  <input readonly type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Time of Departure*</strong></label>
  <input  type="time" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value="<?php echo htmlentities($rs_data['time_departure']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Time of Arrival*</strong></label>
  <input  type="time" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
  <input readonly type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['departure_aerodome']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
  <input readonly type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['destination_aerodome']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Level</strong></label>
  <input readonly type="text" class="form-control"  name="level" id="level" placeholder=" " value="<?php echo htmlentities($rslevel['level']);?>">
</div>

</div>

<br>
<div class="row">
<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Training Type</strong></label>
  <input readonly type="text" class="form-control"  name="training_type" id="training_type" placeholder=" " value="<?php echo htmlentities($rs_data['training_type']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Flight Status*</strong></label>
  <!--onchange="change_time_status();"-->
    <select  name="flight_status" id="flight_status" class="form-select" placeholder=" " onchange="change_time_status();" >
      <option value="" <?php if (!(strcmp($rs_data['flight_status'], ''))) {echo "selected=\"selected\"";} ?>></option>
      <option value="on-flight" <?php if (!(strcmp($rs_data['flight_status'], 'on-flight'))) {echo "selected=\"selected\"";} ?>>on-flight</option>
      <option value="arrived" <?php if (!(strcmp($rs_data['flight_status'], 'arrived'))) {echo "selected=\"selected\"";} ?>>arrived</option>
    </select>
</div>
         

<div class="form-group col-md-8 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
  <input type="text" class="form-control"  name="note" id="note" placeholder=" " value="<?php echo htmlentities($rs_data['note']);?>">
</div>
</div>


<br>

<div class="row">

<div class="form-group col-md-4 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
    <div class="col-sm-12 row">
    <?php foreach ($rsaircraft as $rs_rowaircraft){ 
      if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1){
      ?>
      <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_aircraft('<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>');" class="form-check-input" type="radio" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>"  <?php if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowaircraft['aircraft_id']);?></label></div>
    <?php } }?>
    </div>
</div>


  <div class="form-group col-md-4 col-sm-12">
      <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
      <div class="col-sm-12 row">
      <?php foreach ($rsstudent as $rs_rowstudent){ 
        if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1){
        ?>
        <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_student('<?php echo 's'.htmlentities($rs_rowstudent['id']);?>');" class="form-check-input" type="checkbox" value="<?php echo htmlentities($rs_rowstudent['id']);?>" id="sid" name="sid" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
      <?php } }?>
      </div>
  </div>


<div class="form-group col-md-4 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
    <div class="col-sm-12 row">
    <?php foreach ($rsinstructor as $rs_rowinstructor){ 
      if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1) {?>
      <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_instructor('<?php echo htmlentities($rs_rowinstructor['id']);?>');" class="form-check-input" type="radio" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['id']);?>" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?></label></div>
    <?php } }?>
    </div>
</div>
    
</div>

<br>
       
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <button type="submit" class="btn btn-outline-primary" form="form1" id="save"><span class="bi-save"></span> Save</button>
          <a href="flight_plan_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Cancel</a> 
          </div>
        </div>

    </fieldset>  
  </div>
<input type="hidden" name="POSTcheck" value="form1">
<input type="hidden" name="id" id="id" value="<?php echo $rs_data['flight_sched_id']; ?>">

</form>

<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>

</body>
</html>
<?php
$db->close();
?>
