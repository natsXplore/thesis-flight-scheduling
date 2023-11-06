<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  
  
  $SQLcrud = "UPDATE flight_sched SET `time_departure`=?, `etime_arrival`=?, `departure_aerodome`=?, `destination_aerodome`=?, `route_begin`=?, `route_end`=?, `level`=?, `note`=? WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['time_departure']));
    $db->bind(2,htmlentities($_POST['etime_arrival']));
    $db->bind(3,htmlentities($_POST['departure_aerodome']));
    $db->bind(4,htmlentities($_POST['destination_aerodome']));
    $db->bind(5,htmlentities($_POST['route_begin']));
    $db->bind(6,htmlentities($_POST['route_end']));
    $db->bind(7,htmlentities($_POST['level']));
    $db->bind(8,htmlentities($_POST['note']));
    $db->bind(9,htmlentities($_POST['id']));
    $db->execute();


  $filenames="../images/flight_sched/". htmlentities($_POST['id']).".pdf";

  if(isset($_FILES['image-upload1']) && $_FILES['image-upload1']['error'] == 0){
    $extension = pathinfo($_FILES['image-upload1']['name'], PATHINFO_EXTENSION);
    if(move_uploaded_file($_FILES['image-upload1']['tmp_name'], $filenames)){   }
  }

  if(file_exists('../images/flight_sched/'.htmlentities($_POST['id']).'.pdf')) {

    $query_rs = "SELECT * FROM flight_sched_passenger WHERE `flight_sched_id` = ?";
    $db->query($query_rs);
    $db->bind(1, htmlentities($_POST['id']));
    $rs_data_pass = $db->rowset();

    $ctr=1;
    foreach ($rs_data_pass as $row_rs_data_pass){ 
      
      $SQLcrud = "UPDATE user SET `flight_status`=? WHERE id=?";
      $db->query($SQLcrud);
      $db->bind(1,'');
      $db->bind(2,$row_rs_data_pass['user_id']);
      $db->execute();

      
      $SQLcrud = "INSERT INTO notif (`flight_sched_id`, `user_id`, `notification`, user_from, inspection_sched_id) VALUES (?,?,?,?,?)";
      $db->query($SQLcrud);
      $db->bind(1,$row_rs_data_pass['flight_sched_id']);
      $db->bind(2,$row_rs_data_pass['user_id']);
      $db->bind(3,'Flight Plan report '.htmlentities($_POST['flight_date']). ' has been uploaded.');
      $db->bind(4,$_SESSION['MM_FullName']);
      $db->bind(5,$row_rs_data_pass['flight_sched_id']);
      $db->execute();

      if ($ctr==1){
        $SQLcrud = "UPDATE lup_aircraft SET flight_status=? WHERE flight_sched_slug=? and lup_aircraft_id=?";
        $db->query($SQLcrud);
        $db->bind(1,'for post flight inspection');
        $db->bind(2,htmlentities($row_rs_data_pass['flight_sched_slug']));
        $db->bind(3,htmlentities($row_rs_data_pass['lup_aircraft_id']));
        $db->execute();

        $doc_ref_no=0;
        $query_rs = "select * FROM `inspection_sched` WHERE doc_ref_year=? ORDER BY inspection_sched_id DESC";
        $db->query($query_rs);
        $db->bind(1,date('Y'));
        $rsis=$db->rowsingle();
        $rsis_total=$db->rowcount();
        if ($rsis_total>0){
          $doc_ref_no=$rsis['doc_ref_no']+1;
        }else{
          $doc_ref_no=1;
        }


        $SQLcrud = "INSERT INTO inspection_sched (`flight_sched_id`, `flight_sched_slug`, `lup_aircraft_id`, `inspection_type`, `doc_ref_no`, `doc_ref_year`,  `effective_date`, inspection_status) VALUES (?,?,?,?,?,?,?,?)";
        $db->query($SQLcrud);
        $db->bind(1,$row_rs_data_pass['flight_sched_id']);
        $db->bind(2,htmlentities($row_rs_data_pass['flight_sched_slug']));
        $db->bind(3,htmlentities($row_rs_data_pass['lup_aircraft_id']));
        $db->bind(4,'POST-FLIGHT');
        $db->bind(5,$doc_ref_no);
        $db->bind(6,date('Y'));
        $db->bind(7,$_POST['flight_date']);
        $db->bind(8,'for post flight inspection');
        $db->execute();

        $ctr++;
      }
    }

    
   

  }

  $GoTo = "flight_plan_list.php";
  header(sprintf("Location: %s", $GoTo));
}

$query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
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

<style>
select.form-select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: none !important;
  background-color: #fff;
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em;
  padding-right: 2.5em;
}
</style>
</head>
<?php require_once('../template/phplink.php'); ?>
<body>
     <?php require_once('../template/header.php'); ?>
     <div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form id="form1" name="form1" method="post" enctype="multipart/form-data">
  <div class="form-horizontal">
  
  <fieldset>
  <div class="row">

  <div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
    <input readonly required type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
  </div>

  <div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Time Departure*</strong></label>
    <input readonly required type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value=<?php echo htmlentities($rs_data['time_departure']);?>>
  </div>

  <div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Time of Arrival*</strong></label>
    <input readonly required type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
  </div>

  <div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
    <input readonly required type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['departure_aerodome']);?>">
  </div>

  <div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
    <input readonly type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['destination_aerodome']);?>">
  </div>

  <div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Level</strong></label>
    <select disabled required name="level" id="level" class="form-select" placeholder=" ">
      <?php
        foreach($rslevel as $rs_rowlevel) {  
        ?>
          <option  value="<?php echo $rs_rowlevel['level']?>" <?php if (!(strcmp($rs_data['level'], $rs_rowlevel['level']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($rs_rowlevel['level']);?></option>
        <?php
          }
        ?>
    </select>
  </div>

  </div>

  <br>
  
  <div class="form-group col-md-12 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
    <input type="text" class="form-control"  name="note" id="note" placeholder=" " value="<?php echo $rs_data['note'];?>">
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
  <div class="form-group" align="center">
    <img id="image_preview1" src="" class="img-fluid" width="100%" height="600px"/>
    <div class="mb-12">
      <label for="image-upload1" class="form-label-right">Upload Flight Report Sheet  (Type: .pdf)</label>
      <input class="form-control" type="file" id="image-upload1" name="image-upload1" onchange="preview1()" accept="application/pdf" >
                
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
              
      </div>
      </fieldset>
      <input type="hidden" name="POSTcheck" value="form1">
      <input type="hidden" name="id" id="id" value="<?php echo $rs_data['flight_sched_id']; ?>"> 
      <input type="hidden" name="flight_sched_slug" id="flight_sched_slug" value="<?php echo $rs_data['flight_sched_slug']; ?>"> 
</form> 

<script>
  function preview1() {
    image_preview1.src = URL.createObjectURL(event.target.files[0]);
  }
  function clearImage1() {
    document.getElementById('image-upload1').value = null;
    image_preview1.src = "";
  }
  </script>

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
