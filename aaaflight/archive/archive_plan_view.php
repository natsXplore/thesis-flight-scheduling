<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


$query_rs = "SELECT * FROM flight_sched_archive WHERE `flight_sched_id` = ?";
$db->query($query_rs);
$db->bind(1, htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

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

$query_rs = "select * FROM `lup_route`";
$db->query($query_rs);
$rsroute=$db->rowset();

$query_rs = "select * FROM `lup_level`";
$db->query($query_rs);
$rslevel=$db->rowset();

$query_rs = "select * FROM `lup_basic_settings`";
$db->query($query_rs);
$rsbasic=$db->rowsingle();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
<title><?php echo $app_title; ?> </title>
</head>
<?php require_once('../template/phplink.php'); ?>
<body>
     <?php require_once('../template/header.php'); ?>
     <div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form id="form1" name="form1" method="post">
        <div class="form-horizontal">
        
        <fieldset>
        <div class="row">

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
  <input disabled type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Time Departure*</strong></label>
  <input disabled type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value=<?php echo htmlentities($rs_data['time_departure']);?>>
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Estimated Time of Arrival*</strong></label>
  <input disabled type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
  <input disabled type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['departure_aerodome']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
  <input disabled type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['destination_aerodome']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Level</strong></label>
  <select disabled name="level" id="level" class="form-select" placeholder=" ">
    <?php
      foreach($rslevel as $rs_rowlevel) {  
      ?>
        <option value="<?php echo $rs_rowlevel['level']?>" <?php if (!(strcmp($rs_data['level'], $rs_rowlevel['level']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($rs_rowlevel['level']);?></option>
      <?php
        }
      ?>
  </select>
</div>

</div>

<br>
<div class="row">
<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Training Type</strong></label>
  <input disabled type="text" class="form-control"  name="training_type" id="training_type" placeholder=" " value="<?php echo htmlentities($rs_data['training_type']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Flight Status</strong></label>
  <input disabled type="text" class="form-control"  name="flight_status" id="flight_status" placeholder=" " value="<?php echo htmlentities($rs_data['flight_status']);?>">
</div>

<div class="form-group col-md-8 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Note</strong></label>
  <input disabled type="text" class="form-control"  name="note" id="note" placeholder=" " value="">
</div>
</div>

<div class="form-group col-md-8 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Note</strong></label>
  <input disabled type="text" class="form-control"  name="note" id="note" placeholder=" " value="">
</div>
</div>
<br>

<div class="row">

<div class="form-group col-md-4 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
    <div class="col-sm-12 row">
    <?php foreach ($rsaircraft as $rs_rowaircraft){ 
      if ($phu->get_flight_sched_aircraft_archive(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1){
      ?>
      <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_aircraft('<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>');" class="form-check-input" type="radio" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>"  <?php if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowaircraft['aircraft_id']);?></label></div>
    <?php } }?>
    </div>
</div>


  <div class="form-group col-md-4 col-sm-12">
      <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
      <div class="col-sm-12 row">
      <?php foreach ($rsstudent as $rs_rowstudent){ 
        if ($phu->get_flight_sched_user_archive(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1){
        ?>
        <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_student('<?php echo 's'.htmlentities($rs_rowstudent['id']);?>');" class="form-check-input" type="checkbox" value="<?php echo htmlentities($rs_rowstudent['id']);?>" id="sid" name="sid" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
      <?php } }?>
      </div>
  </div>


<div class="form-group col-md-4 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
    <div class="col-sm-12 row">
    <?php foreach ($rsinstructor as $rs_rowinstructor){ 
      if ($phu->get_flight_sched_user_archive(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1) {?>
      <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_instructor('<?php echo htmlentities($rs_rowinstructor['id']);?>');" class="form-check-input" type="radio" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['id']);?>" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?></label></div>
    <?php } }?>
    </div>
</div>
    
</div>
<br>
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
           <a href="archive_plan_list.php" class="btn btn-outline-primary hidelink"><span class="bi-x-octagon"></span> Close</a>
          </div>
        </div>
            
        </fieldset>
    </div>
    <input type="hidden" name="POSTcheck" value="form1">
    <input type="hidden" name="id" id="id" value="<?php echo $rs_data['flight_sched_id']; ?>"> 
    <input type="hidden" name="flight_sched_slug" id="flight_sched_slug" value="<?php echo $rs_data['flight_sched_slug']; ?>"> 
</form> 
    
 <!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>	

</body>
</html>
<?php ob_flush(); 
$db->close();
?>
