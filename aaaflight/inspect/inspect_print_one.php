<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();


$db=new DatabaseConnect();



$query_rs = "SELECT * FROM inspection_sched i INNER JOIN flight_sched fs ON fs.flight_sched_id=i.flight_sched_id INNER JOIN lup_aircraft a  ON a.lup_aircraft_id=i.lup_aircraft_id  WHERE i.`inspection_sched_id` = ?";
$db->query($query_rs);
$db->bind(1, htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

$query_rs = "SELECT * FROM user WHERE `group`=? AND `designation`=? AND date(mecl)>=? and `status`='active'";
$db->query($query_rs);
$db->bind(1, 'Mechanic');
$db->bind(2, 'Aircraft Mechanic');
$db->bind(3, date('Y-m-d'));
$rs_data_mech = $db->rowset();

$query_rs = "SELECT * FROM user WHERE `group`=? AND `designation`=? AND date(mecl)>=?  and `status`='active'";
$db->query($query_rs);
$db->bind(1, 'Mechanic');
$db->bind(2, 'Aircraft Inspector');
$db->bind(3, date('Y-m-d'));
$rs_data_mech_inspect = $db->rowset();

$query_rs = "SELECT * FROM flight_sched WHERE `flight_sched_id` = ?";
$db->query($query_rs);
$db->bind(1, $rs_data['flight_sched_id']);
$rs_data_flight_sched = $db->rowsingle();
$rs_data_flight_sched_total = $db->rowcount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $app_title; ?>  </title>
</head>

<?php require_once('../template/phplink.php'); ?>
<style>
  
 
    @page {
        margin: 0.25cm;
    }


  body {
font: 10pt;
}
</style>

<body onload="window.print();">
<?php require_once('../template/header_print.php'); ?> 
<div class="card">
        <div class="card-header"><h3 class="card-title">Inspection Report</h3></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->


<form>

     <div class="form-horizontal">
        <fieldset>
        <div class="row">

        <div class="form-group col-md-2 col-sm-2">
          <label class="col-sm-12 col-form-label"><strong>Inspection Type*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="inspection_type" id="inspection_type" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_type']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-2">
          <label class="col-sm-12 col-form-label"><strong>Inspection Date*</strong></label>
            <input style="font-size:11px;"readonly required type="text" class="form-control"  name="inspection_date" id="inspection_date" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_date']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-2">
          <label class="col-sm-12 col-form-label"><strong>Inspection Time*</strong></label>
            <input style="font-size:11px;"readonly required type="time" class="form-control"  name="inspection_time" id="inspection_time" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_time']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-2">
          <label class="col-sm-12 col-form-label"><strong>Doc Ref No*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="doc_ref_no" id="doc_ref_no" placeholder=" " value="<?php echo htmlentities($rs_data['doc_ref_no']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-2">
          <label class="col-sm-12 col-form-label"><strong>Doc Ref Year*</strong></label>
            <input style="font-size:11px;"  readonly type="text" class="form-control"  name="doc_ref_year" id="doc_ref_year" placeholder=" " value="<?php echo htmlentities($rs_data['doc_ref_year']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-2">
          <label class="col-sm-12 col-form-label"><strong>Issue Rev No*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="issue_rev_no" id="issue_rev_no" placeholder=" " value="<?php echo htmlentities($rs_data['issue_rev_no']);?>">
          </div>
        
          </div>
        <br>
        <div class="row">

        <div class="form-group col-md-2 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Validity*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="aircraft_validity" id="aircraft_validity" placeholder=" " value="<?php echo htmlentities($rs_data['date_validity']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
            <input style="font-size:11px;" readonly type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Time Departure*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value="<?php echo htmlentities($rs_data['time_departure']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong><?php if(str_contains($rs_data ['inspection_type'],"POST")) {echo ""; } else {echo "Estimated";}?> Time of Arrival*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['departure_aerodome']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
            <input style="font-size:11px;" readonly type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['destination_aerodome']);?>">
          </div>

      </div>
        <br>
        <div class="row">

          <div class="form-group col-md-4 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Mechanic*</strong></label>
            <select style="font-size:11px;"disabled required name="ac_mechanic_id" id="ac_mechanic_id" class="form-select" placeholder=" ">
            <?php
              foreach($rs_data_mech as $row_rs_data_mech) {  
              ?>
                <option value="<?php echo $row_rs_data_mech['id']?>" <?php if (!(strcmp($rs_data['ac_mechanic_id'], $row_rs_data_mech['id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rs_data_mech['firstname']).' '.htmlentities($row_rs_data_mech['lastname']).' '.htmlentities($row_rs_data_mech['extname']);?></option>
              <?php
                }
              ?>
            </select>
          </div>

        
          <div class="form-group col-md-4 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Inspector*</strong></label>
            <select style="font-size:11px;"disabled required name="ac_inspection_id" id="ac_inspection_id" class="form-select" placeholder=" ">
            <?php
              foreach($rs_data_mech_inspect as $row_rs_data_mech_inspect) {  
              ?>
                <option value="<?php echo $row_rs_data_mech_inspect['id']?>" <?php if (!(strcmp($rs_data['ac_mechanic_id'], $row_rs_data_mech_inspect['id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rs_data_mech_inspect['firstname']).' '.htmlentities($row_rs_data_mech_inspect['lastname']).' '.htmlentities($row_rs_data_mech_inspect['extname']);;?></option>
              <?php
                }
              ?>
            </select>
          </div>
          
        
          <div class="form-group col-md-4 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Status*</strong></label>
            <select style="font-size:11px;"disabled required name="inspection_status" id="inspection_status" class="form-select" placeholder=" ">for post flight inspection
              <option value="for inspection" <?php if (!(strcmp($rs_data['inspection_status'], 'for inspection'))) {echo "selected=\"selected\"";} ?>>for inspection</option>
              <option value="for post flight inspection" <?php if (!(strcmp($rs_data['inspection_status'], 'for post flight inspection'))) {echo "selected=\"selected\"";} ?>>for post flight inspection</option>
              <option value="Ready for Flight" <?php if (!(strcmp($rs_data['inspection_status'], 'Ready for Flight'))) {echo "selected=\"selected\"";} ?>>Ready for Flight</option>
              <option value="NOT Ready for Flight" <?php if (!(strcmp($rs_data['inspection_status'], 'NOT Ready for Flight'))) {echo "selected=\"selected\"";} ?>>NOT Ready for Flight</option>
            </select>
          </div>
          </div>
        
        </div>
       <br>
        <!--<div class="form-group col-md-12 col-sm-12">-->
        <!--  <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>-->
        <!--    <input type="text" class="form-control"  name="remarks" id="remarks" placeholder=" " value="">-->
        <!--</div>-->
        <!--<br>-->
    </fieldset>  
  </div>
<input type="hidden" name="POSTcheck" value="form1">
<input type="hidden" name="inspection_type" id="inspection_type" value="<?php echo $rs_data['inspection_type']; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $rs_data['inspection_sched_id']; ?>">
<input type="hidden" name="flight_sched_id" id="flight_sched_id" value="<?php echo $rs_data_flight_sched ['flight_sched_id']; ?>">

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
<?php require_once('../template/footer_print.php'); ?>

</body>
</html>
<?php
$db->close();
?>
