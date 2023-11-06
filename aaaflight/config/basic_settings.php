<?php ob_start(); ?><?php require_once('../connections/pdoconnect.php'); ?>
<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    $SQLcrud = "UPDATE `lup_basic_settings` SET `set_originator`=?,set_aircraft_color_marking=?,set_time_use=?,type_of_flight=?,set_flight_time_interval=?,start_training_time=?,end_training_time=?, training_duration=? ";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['set_originator']));
    $db->bind(2,htmlentities($_POST['set_aircraft_color_marking']));
    $db->bind(3,htmlentities($_POST['set_time_use']));
    $db->bind(4,htmlentities($_POST['type_of_flight']));
    $db->bind(5,htmlentities($_POST['set_flight_time_interval']));
    $db->bind(6,htmlentities($_POST['start_training_time']));
    $db->bind(7,htmlentities($_POST['end_training_time']));
    $db->bind(8,htmlentities($_POST['training_duration']));
    
    
    $db->execute();


    $GoTo = "../admin/index.php?msg_info=basic settings has been saved!";
    header(sprintf("Location: %s", $GoTo));

}
  
$query_rs = "SELECT * FROM `lup_basic_settings`";
$db->query($query_rs);
$rs_data = $db->rowsingle();

?>
<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>
</head>
    
<?php require_once('../template/phplink.php'); ?>
<!--
<script type="text/javascript">
 $(document).ready(function () {
  $('#Date').datepicker({format: "yyyy-mm-dd",autoclose:true}); /*input ID*/

});
</script>
-->
<body>
<?php require_once('../template/header.php'); ?>
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="POST" id="form1" name="form1" onsubmit="return validateForm();">
  
    <div class="form-horizontal">
    <fieldset>    
    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
                <label >Originator</label>
                <input required class="form-control" type="text" name="set_originator" id="set_originator"  value="<?php echo htmlentities($rs_data['set_originator']);?>" size="32" placeholder=" ">
        </div>
        <div class="form-group col-md-6 col-sm-12">
                <label >Type of Flight</label>
                <input required class="form-control" type="text" name="type_of_flight" id="type_of_flight"  value="<?php echo htmlentities($rs_data['type_of_flight']);?>" size="32" placeholder=" ">
        </div>
    </div>
<br>
    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
                <label >Aircraft Color Marking</label>
                <input required class="form-control" type="text" name="set_aircraft_color_marking" id="set_aircraft_color_marking"  value="<?php echo htmlentities($rs_data['set_aircraft_color_marking']);?>" size="32" placeholder=" ">
        </div>
        <div class="form-group col-md-6 col-sm-12">
                <label >Time Use</label>
                <input required class="form-control" type="text" name="set_time_use" id="set_time_use"  value="<?php echo htmlentities($rs_data['set_time_use']);?>" size="32" placeholder=" ">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
                <label >Flight Schedule Time Interval (in minutes)</label>
                <input required class="form-control" type="text" name="set_flight_time_interval" id="set_flight_time_interval"  value="<?php echo htmlentities($rs_data['set_flight_time_interval']);?>" size="32" placeholder=" ">
        </div>

        <div class="form-group col-md-6 col-sm-12">
                <label >Flight Training Duration (in minutes)</label>
                <input required class="form-control" type="text" name="training_duration" id="training_duration"  value="<?php echo htmlentities($rs_data['training_duration']);?>" size="32" placeholder=" ">
        </div>
        
    </div>

    <br>

    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
                <label >Default Start Training Time (24H format)</label>
                <input required class="form-control" type="text" name="start_training_time" id="start_training_time"  value="<?php echo htmlentities($rs_data['start_training_time']);?>" size="32" placeholder=" ">
        </div>
        
   
        <div class="form-group col-md-6 col-sm-12">
                <label >Default Ending Training Time  (24H format)</label>
                <input required class="form-control" type="text" name="end_training_time" id="end_training_time"  value="<?php echo htmlentities($rs_data['end_training_time']);?>" size="32" placeholder=" ">
        </div>
        
    </div>

    <br>


        <div class="form-group">
          <div class="col-md-2"></div>
          <div class="col-md-10">
              <button type="submit" class="btn btn-outline-primary" form="form1"><span class="bi-save"></span> Save</button>
          <a href="../admin/index.php" class="btn btn-outline-danger"><span class="bi-x-octagon"></span> Cancel</a>
          </div>
        </div>

    </fieldset>    
    </div> 
    
  <input type="hidden" name="POSTcheck" value="form1">
  
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
