<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    $query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
    $db->query($query_rs);
    $db->bind(1, $_POST['flight_sched_id']);
    $rs= $db->rowsingle();

    $query_rs = "select * FROM `flight_touch_details` WHERE `flight_sched_id`=? ORDER BY flight_touch_details_id DESC LIMIT 1";
    $db->query($query_rs);
    $db->bind(1,$_POST['flight_sched_id']);
    $rslast=$db->rowsingle();

    //echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    //echo date("G:i:s",strtotime($_POST['take_off']));

    $SQLcrud = "UPDATE flight_touch_details SET takeoff=?, user_id=? WHERE flight_touch_details_id=?";
    $db->query($SQLcrud);
    $db->bind(1,date("G:i:s",strtotime($_POST['take_off'])));
    $db->bind(2,$_POST['user_id']);
    $db->bind(3,htmlentities($rslast['flight_touch_details_id']));
    $db->execute();

    $SQLcrud = "INSERT flight_touch_details (`flight_sched_id`, flight_date, landing,takeoff) VALUES (?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,$_POST['flight_sched_id']);
    $db->bind(2,htmlentities($rs['flight_date']));
    $db->bind(3,'');
    $db->bind(4,'');
    $db->execute();

    $GoTo = "flight_plan_update_touch.php?recordID=".$_POST['flight_sched_id'];
    header(sprintf("Location: %s", $GoTo));

}

$flight_sched_id=$_GET['recordID'];

$query_rs = "select * FROM `flight_touch_details` WHERE `flight_sched_id`=? ORDER BY flight_touch_details_id DESC LIMIT 1";
$db->query($query_rs);
$db->bind(1,$flight_sched_id);
$rslast=$db->rowsingle();

$query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
$db->query($query_rs);
$db->bind(1, htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();
$rs_data_total=$db->rowcount();

$query_rs = "select * FROM `user` WHERE `group`=?";
$db->query($query_rs);
$db->bind(1,'Student');
$rsstudent=$db->rowset();
$rsstudent_total=$db->rowcount();

$query_rs = "select * FROM `user` WHERE `group`=? and `status`=?";
$db->query($query_rs);
$db->bind(1,'Instructor');
$db->bind(2,'active');
$rsinstructor=$db->rowset();

$query_rs = "select * FROM `lup_aircraft` WHERE `aircraft_status`=?";
$db->query($query_rs);
$db->bind(1,'active');
$rsaircraft=$db->rowset();

?>

<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>

</head>
   
<?php require_once('../template/phplink.php'); ?>

<body>
<?php require_once('../template/header.php'); ?> 
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="POST" name="form1" id="form1" onsubmit="return validateForm();" enctype="multipart/form-data">

<div class="row">

<div class="form-group col-md-2 col-sm-12">
<label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
  <input readonly type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
</div>


<div class="form-group col-md-2 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Training Type</strong></label>
  <input readonly type="text" class="form-control"  name="training_type" id="training_type" placeholder=" " value="<?php echo htmlentities($rs_data['training_type']);?>">
</div>


<div class="form-group col-md-2 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
    <div class="col-sm-12 row">
    <?php foreach ($rsaircraft as $rs_rowaircraft){ 
      if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1){
      ?>
      <input readonly class="form-control" type="text" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['aircraft_id']);?>">  <label class="form-check-label" for=""></label>
    <?php } }?>
    </div>
</div>

<div class="form-group col-md-6 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
    <div class="col-sm-12 row">
    <?php foreach ($rsinstructor as $rs_rowinstructor){ 
        
      if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1) {?>
      <input readonly class="form-control" type="text" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?>"> <label class="form-check-label" for=""></label>
    <?php } }?>

    </div>
</div>

</div>
<br>
<div class="row">

    <div class="form-group col-md-4 col-sm-12">
        <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
        <div class="col-sm-12 row">
            <?php foreach ($rsstudent as $rs_rowstudent){ 
                if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1){ ?>
                    <div class="form-check form-switch col-sm-12"><input required  checked="checked" class="form-check-input" type="radio" name="user_id" id="user_id" value="<?php echo htmlentities($rs_rowstudent['id']);?>"> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
            <?php } } ?>
        </div>
    </div>

    <div class="form-group col-md-2 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Take Off Time*</strong></label>
    <input required type="text" class="form-control"  name="take_off" id="take_off" placeholder=" " value="<?php echo date('G:i:s'); ?>">
    </div>

</div>
    
<br>

<div class="form-group">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <button type="submit" class="btn btn-outline-primary" form="form1" id="save"><span class="bi-save"></span> Save</button>
          <a href="flight_plan_update_touch.php?recordID=<?php echo htmlentities($rs_data['flight_sched_id']);?>" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Close</a> 
    </div>
    </div>
</div>

<input type="hidden" name="POSTcheck" value="form1">
<input type="hidden" name="flight_sched_id" id="flight_sched_id" value="<?php echo htmlentities($rs_data['flight_sched_id']);?>">
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
