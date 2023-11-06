<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

  $query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
  $db->query($query_rs);
  $db->bind(1, htmlentities($_POST['id']));
  $rs_data_check = $db->rowsingle();
  
    $SQLcrud = "INSERT INTO flight_sched_archive (`flight_date`, `time_departure`, `etime_arrival`, `departure_aerodome`, `destination_aerodome`, `route_begin`, `route_end`, `level`, `note`, flight_sched_slug, flight_sched_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($rs_data_check['flight_date']));
    $db->bind(2,htmlentities($rs_data_check['time_departure']));
    $db->bind(3,htmlentities($rs_data_check['etime_arrival']));
    $db->bind(4,htmlentities($rs_data_check['departure_aerodome']));
    $db->bind(5,htmlentities($rs_data_check['destination_aerodome']));
    $db->bind(6,htmlentities($rs_data_check['route_begin']));
    $db->bind(7,htmlentities($rs_data_check['route_end']));
    $db->bind(8,htmlentities($rs_data_check['level']));
    $db->bind(9,htmlentities($rs_data_check['note']));
    $db->bind(10,htmlentities($rs_data_check['flight_sched_slug']));
    $db->bind(11,htmlentities($_POST['id']));
    $db->execute();
    
    $query_rs = "SELECT * FROM flight_sched_passenger WHERE `flight_sched_id` = ?";
    $db->query($query_rs);
    $db->bind(1, htmlentities($_POST['id']));
    $rs_data_pass = $db->rowset();

    $ctr=1;
    foreach ($rs_data_pass as $row_rs_data_pass){ 
      
      $SQLcrud = "INSERT INTO flight_sched_passenger_archive (`user_id`, lup_aircraft_id, flight_sched_slug, flight_sched_id, flight_sched_passenger_id ) VALUES (?,?,?,?,?)";
      $db->query($SQLcrud);
      $db->bind(1,$row_rs_data_pass['user_id']);
      $db->bind(2,$row_rs_data_pass['lup_aircraft_id']);
      $db->bind(3,$row_rs_data_pass['flight_sched_slug']);
      $db->bind(4,$row_rs_data_pass['flight_sched_id']);
      $db->bind(5,$row_rs_data_pass['flight_sched_passenger_id']);
      $db->execute();

      $SQLcrud = "UPDATE user SET `flight_status`=? WHERE id=?";
      $db->query($SQLcrud);
      $db->bind(1,'');
      $db->bind(2,$row_rs_data_pass['user_id']);
      $db->execute();

      
      $SQLcrud = "INSERT INTO notif (`flight_sched_id`, `user_id`, `notification`, user_from, inspection_sched_id) VALUES (?,?,?,?,?)";
      $db->query($SQLcrud);
      $db->bind(1,$row_rs_data_pass['flight_sched_id']);
      $db->bind(2,$row_rs_data_pass['user_id']);
      $db->bind(3,'Flight Plan dated '.htmlentities($_POST['flight_date']). ' has been cancelled.');
      $db->bind(4,$_SESSION['MM_FullName']);
      $db->bind(5,$row_rs_data_pass['flight_sched_id']);
      $db->execute();
    

      if ($ctr==1){
        $SQLcrud = "UPDATE lup_aircraft SET `flight_sched_slug`=?, flight_status=? WHERE flight_sched_slug=? and lup_aircraft_id=?";
        $db->query($SQLcrud);
        $db->bind(1,'');
        $db->bind(2,'');
        $db->bind(3,htmlentities($row_rs_data_pass['flight_sched_slug']));
        $db->bind(4,htmlentities($row_rs_data_pass['lup_aircraft_id']));
        $db->execute();
        $ctr++;
      }
    }

    $SQLcrud = "INSERT INTO inspection_sched_archive SELECT * FROM inspection_sched WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

    $SQLcrud = "DELETE FROM flight_sched WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

    $SQLcrud = "DELETE FROM flight_sched_passenger WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

    $SQLcrud = "DELETE FROM inspection_sched WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

    $GoTo = "flight_plan_list.php";
    header(sprintf("Location: %s", $GoTo));
}

$query_rs = "SELECT fs.*, i.* FROM flight_sched fs INNER JOIN inspection_sched i ON i.flight_sched_id=fs.flight_sched_id WHERE fs.flight_sched_id = ?";
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
  <input readonly type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Time of Departure*</strong></label>
  <input readonly type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value=<?php echo htmlentities($rs_data['time_departure']);?>>
</div>

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Estimated Time of Arrival*</strong></label>
  <input readonly type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
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
  <input disabled type="text" class="form-control"  name="training_type" id="training_type" placeholder=" " value="<?php echo htmlentities($rs_data['training_type']);?>">
</div>

<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Flight Status</strong></label>
  <input disabled type="text" class="form-control"  name="flight_status" id="flight_status" placeholder=" " value="<?php echo htmlentities($rs_data['flight_status']);?>">
</div>

<div class="form-group col-md-8 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
  <input disabled type="text" class="form-control"  name="note" id="note" placeholder=" " value="<?php echo htmlentities($rs_data['note']);?>">
</div>
</div>


<br>

<div class="form-group col-md-8 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Inspection Status</strong></label>
  <input disabled type="text" class="form-control"  name="note" id="note" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_status']);?>">
</div>
</div>

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
          Are you sure you want to Cancel this Flight Schedule? &nbsp;<button type="submit" class="btn btn-outline-danger" form="form1"><span class="bi-trash"></span> Cancel Flight</button>
          <a href="flight_plan_list.php" class="btn btn-outline-primary hidelink"><span class="bi-x-octagon"></span> Close</a>
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
<?php } ob_flush(); 
$db->close();
?>
