<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  
  
    $SQLcrud = "UPDATE inspection_sched SET `inspection_date`=?, `inspection_time`=?, `doc_ref_no`=?, `doc_ref_year`=?, `issue_rev_no`=?, `ac_mechanic_id`=?, `ac_inspection_id`=?, `remarks`=?, inspection_status=? WHERE inspection_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['inspection_date']));
    $db->bind(2,htmlentities($_POST['inspection_time']));
    $db->bind(3,htmlentities($_POST['doc_ref_no']));
    $db->bind(4,htmlentities($_POST['doc_ref_year']));
    $db->bind(5,htmlentities($_POST['issue_rev_no']));
    $db->bind(6,htmlentities($_POST['ac_mechanic_id']));
    $db->bind(7,htmlentities($_POST['ac_inspection_id']));
    $db->bind(8,htmlentities($_POST['remarks']));
    $db->bind(9,htmlentities($_POST['inspection_status']));
    $db->bind(10,htmlentities($_POST['id']));
    $db->execute();
   
    $query_rs = "SELECT * FROM flight_sched_passenger WHERE `flight_sched_id` = ?";
    $db->query($query_rs);
    $db->bind(1, $_POST['flight_sched_id']);
    $rs_data_notif = $db->rowset();
    $rs_data_notif_total = $db->rowcount();

    foreach ($rs_data_notif as $row_rs_data_notif){

      $query_rs = "SELECT * FROM notif WHERE `flight_sched_id` = ? AND `user_id`=? AND `notification`=? AND user_from=?";
      $db->query($query_rs);
      $db->bind(1,$_POST['flight_sched_id']);
      $db->bind(2,htmlentities($row_rs_data_notif['user_id']));
      $db->bind(3,'Flight Plan dated '.htmlentities($_POST['flight_date']).' has been Inspected for '.$_POST['inspection_type'].'.');
      $db->bind(4,$_SESSION['MM_FullName']);
      $rs_data_notif_duplicate = $db->rowset();
      $rs_data_notif_duplicate_total = $db->rowcount();

      if ($rs_data_notif_duplicate_total==0){

        $SQLcrud = "INSERT INTO notif (`flight_sched_id`, `user_id`, `notification`,user_from, inspection_sched_id) VALUES (?,?,?,?,?)";
        $db->query($SQLcrud);
        $db->bind(1,$_POST['flight_sched_id']);
        $db->bind(2,htmlentities($row_rs_data_notif['user_id']));
        $db->bind(3,'Flight Plan dated '.htmlentities($_POST['flight_date']).' has been Inspected for '.$_POST['inspection_type'].'.');
        $db->bind(4,$_SESSION['MM_FullName']);
        $db->bind(5,$_POST['id']);
        $db->execute();
      }
    }

    if ($_POST['inspection_status']=="NOT Ready for Flight"){
      
    }

    if ($_POST['inspection_type']=="PRE-FLIGHT") {
      $filenames="../images/aircraft_maintenance_sched/". htmlentities($_POST['id']).".pdf";
    }else if ($_POST['inspection_type']=="POST-FLIGHT"){
      $filenames="../images/aircraft_maintenance_sched_post/". htmlentities($_POST['id']).".pdf";
    }

    if(isset($_FILES['image-upload1']) && $_FILES['image-upload1']['error'] == 0){
      $extension = pathinfo($_FILES['image-upload1']['name'], PATHINFO_EXTENSION);
      if(move_uploaded_file($_FILES['image-upload1']['tmp_name'], $filenames)){   }
    }

    $GoTo = "inspect_list.php";
    header(sprintf("Location: %s", $GoTo));
}

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

<body>
<?php require_once('../template/header.php'); ?> 
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="post" name="form1" id="form1" enctype="multipart/form-data">

     <div class="form-horizontal">
        <fieldset>
        <div class="row">

        <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Inspection Type*</strong></label>
            <input readonly type="text" class="form-control"  name="inspection_type" id="inspection_type" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_type']);?>">
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Inspection Date*</strong></label>
            <input required type="date" class="form-control"  name="inspection_date" id="inspection_date" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_date']);?>">
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Inspection Time*</strong></label>
            <input required type="time" class="form-control"  name="inspection_time" id="inspection_time" placeholder=" " value="<?php echo htmlentities($rs_data['inspection_time']);?>">
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Doc Ref No*</strong></label>
            <input readonly type="text" class="form-control"  name="doc_ref_no" id="doc_ref_no" placeholder=" " value="<?php echo htmlentities($rs_data['doc_ref_no']);?>">
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Doc Ref Year*</strong></label>
            <input readonly type="text" class="form-control"  name="doc_ref_year" id="doc_ref_year" placeholder=" " value="<?php echo htmlentities($rs_data['doc_ref_year']);?>">
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Issue Rev No*</strong></label>
            <input readonly type="text" class="form-control"  name="issue_rev_no" id="issue_rev_no" placeholder=" " value="<?php echo htmlentities($rs_data['issue_rev_no']);?>">
          </div>
                <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Validity*</strong></label>
            <input readonly type="text" class="form-control"  name="aircraft_validity" id="aircraft_validity" placeholder=" " value="<?php echo htmlentities($rs_data['date_validity']);?>">
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
            <input readonly type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
          </div>
          </div>
        <br>
        <div class="row">

          <div class="form-group col-md-6 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Time Departure*</strong></label>
            <input readonly type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value="<?php echo htmlentities($rs_data['time_departure']);?>">
          </div>

          <div class="form-group col-md-6 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong><?php if(str_contains($rs_data ['inspection_type'],"POST")) {echo ""; } else {echo "Estimated";}?> Time of Arrival*</strong></label>
            <input readonly type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
          </div>

          <div class="form-group col-md-6 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
            <input readonly type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['departure_aerodome']);?>">
          </div>

          <div class="form-group col-md-6 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
            <input readonly type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['destination_aerodome']);?>">
          </div>

      </div>
        <br>
        <div class="row">

          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Mechanic*</strong></label>
            <select required name="ac_mechanic_id" id="ac_mechanic_id" class="form-select" placeholder=" ">
            <?php
              foreach($rs_data_mech as $row_rs_data_mech) {  
              ?>
                <option value="<?php echo $row_rs_data_mech['id']?>" <?php if (!(strcmp($rs_data['ac_mechanic_id'], $row_rs_data_mech['id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rs_data_mech['firstname']).' '.htmlentities($row_rs_data_mech['lastname']).' '.htmlentities($row_rs_data_mech['extname']);?></option>
              <?php
                }
              ?>
            </select>
          </div>

        
          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Inspector*</strong></label>
            <select required name="ac_inspection_id" id="ac_inspection_id" class="form-select" placeholder=" ">
            <?php
              foreach($rs_data_mech_inspect as $row_rs_data_mech_inspect) {  
              ?>
                <option value="<?php echo $row_rs_data_mech_inspect['id']?>" <?php if (!(strcmp($rs_data['ac_mechanic_id'], $row_rs_data_mech_inspect['id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rs_data_mech_inspect['firstname']).' '.htmlentities($row_rs_data_mech_inspect['lastname']).' '.htmlentities($row_rs_data_mech_inspect['extname']);;?></option>
              <?php
                }
              ?>
            </select>
          </div>
          
        
          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Aircraft Status*</strong></label>
            <select required name="inspection_status" id="inspection_status" class="form-select" placeholder=" ">for post flight inspection
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
        <br>
        <?php  if(file_exists('../images/aircraft_maintenance_sched/'.htmlentities($rs_data['inspection_sched_id']).'.pdf') && $rs_data['inspection_type']=="PRE-FLIGHT") {?>
            <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPDF<?php echo htmlentities($rs_data['inspection_sched_id']);?>"><span class="bi-eye"  data-toogle="tooltip" data-placement="bottom" title="View Inspection Report"></span>&nbsp;View Inspection Report</a>

            <div class="modal fade" id="modalDialogPDF<?php echo htmlentities($rs_data['inspection_sched_id']);?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inspection Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" height="100%"><div id="PDFArea"> 
                            <div align="center">
                                <object data="<?php echo '../images/aircraft_maintenance_sched/'.htmlentities($rs_data['inspection_sched_id']).'.pdf';?>"  width="100%"  height="700px"> </object>
                            </div>  
                        </div></div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><span class="bi-x-octagon"  data-toogle="tooltip" data-placement="bottom" title="Print"></span> Close</button> 
                            
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php  if(file_exists('../images/aircraft_maintenance_sched_post/'.htmlentities($rs_data['inspection_sched_id']).'.pdf') && $rs_data['inspection_type']=="POST-FLIGHT") {?>
            <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPDF<?php echo htmlentities($rs_data['inspection_sched_id']);?>"><span class="bi-eye"  data-toogle="tooltip" data-placement="bottom" title="View Inspection Report"></span>&nbsp;View Inspection Report</a>

            <div class="modal fade" id="modalDialogPDF<?php echo htmlentities($rs_data['inspection_sched_id']);?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inspection Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" height="100%"><div id="PDFArea"> 
                            <div align="center">
                                <object data="<?php echo '../images/aircraft_maintenance_sched_post/'.htmlentities($rs_data['inspection_sched_id']).'.pdf';?>"  width="100%"  height="700px"> </object>
                            </div>  
                        </div></div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><span class="bi-x-octagon"  data-toogle="tooltip" data-placement="bottom" title="Print"></span> Close</button> 
                            
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>


        <div class="form-group" align="center">
         <img id="image_preview1" src="" class="img-fluid" width="100%" height="600px"/>
            <div class="mb-12">
                <label for="image-upload1" class="form-label-right">Upload Inspection Sheet  (Type: .pdf)</label>
                <input class="form-control" type="file" id="image-upload1" name="image-upload1" onchange="preview1()" accept="application/pdf" >
                
            </div> 
       </div>
        <br>
       
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <button type="submit" class="btn btn-outline-primary" form="form1" id="save"><span class="bi-save"></span> Save</button>
          <a href="inspect_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Cancel</a> 
          </div>
        </div>
       
       
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
<?php require_once('../template/footer.php'); ?>

</body>
</html>
<?php
$db->close();
?>
