<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();


$db=new DatabaseConnect();

$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

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


if ($rs_data['training_type']=="Touch and Go"){
  $query_rs = "select * FROM `flight_touch_details` where `flight_sched_id`=? ORDER BY flight_touch_details_id ASC";
  $db->query($query_rs);
  $db->bind(1,$rs_data['flight_sched_id']);
  $rstouch=$db->rowset();
  $rstouch_total=$db->rowcount();

  $query_rs = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(ftd.total_time))) 'ttime' FROM flight_touch_details ftd WHERE `flight_sched_id`=?";
  $db->query($query_rs);
  $db->bind(1,$rs_data['flight_sched_id']);
  $rstouch_time=$db->rowsingle();
}

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

              <a type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span> Print</a>
              <br><br>

              <div class="row">

                <div class="form-group col-md-2 col-sm-12">
                <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
                  <input disabled type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
                </div>

                <div class="form-group col-md-2 col-sm-12">
                <label class="col-sm-12 col-form-label"><strong>Time of Departure*</strong></label>
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
                <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
                  <input disabled type="text" class="form-control"  name="note" id="note" placeholder=" " value="<?php echo htmlentities($rs_data['note']);?>">
                </div>

              </div>
              <br>

              <div class="row">

                <div class="form-group col-md-4 col-sm-12">
                    <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
                    <div class="col-sm-12 ">
                    <?php foreach ($rsaircraft as $rs_rowaircraft){ 
                      if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1){
                      ?>
                      <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_aircraft('<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>');" class="form-check-input" type="radio" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>"  <?php if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowaircraft['aircraft_id']);?></label></div>
                    <?php } }?>
                    </div>
                </div>


                  <div class="form-group col-md-4 col-sm-12">
                      <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
                      <div class="col-sm-12 ">
                      <?php foreach ($rsstudent as $rs_rowstudent){ 
                        if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1){
                        ?>
                        <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_student('<?php echo 's'.htmlentities($rs_rowstudent['id']);?>');" class="form-check-input" type="checkbox" value="<?php echo htmlentities($rs_rowstudent['id']);?>" id="sid" name="sid" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
                      <?php } }?>
                      </div>
                  </div>


                <div class="form-group col-md-4 col-sm-12">
                    <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
                    <div class="col-sm-12 ">
                    <?php foreach ($rsinstructor as $rs_rowinstructor){ 
                      if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1) {?>
                      <div class="form-check form-switch col-sm-12"> <input disabled onchange="update_instructor('<?php echo htmlentities($rs_rowinstructor['id']);?>');" class="form-check-input" type="radio" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['id']);?>" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?></label></div>
                    <?php } }?>
                    </div>
                </div>
                  
              </div>
              <br>

              <?php if ($rs_data['training_type']=="Touch and Go"){ ?>

              <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
                  <thead>
                    <tr class="alert-success">
                        <th colspan="3" class="alert-danger" class="align-middle">ESTIMATED TIME</th>
                        <th colspan="6" class="alert-success" class="align-middle">ACTUAL TIME</th>
                      </tr>

                      <tr class="alert-info">
                          <th class="alert-danger">Date</th>
                          <th class="alert-danger">Landing</th>
                          <th class="alert-danger">Take-Off</th>
                          

                          <th>Date</th>
                          <th>Landing</th>
                          <th>Take-Off</th>
                          <th>Total Time</th>
                          
                      </tr>
                  </thead>
                  <tbody>

              <?php $remove_ctr=0; 

              $estimate_time=$rs_data['time_departure'];
              $estimate_landing="";
              $estimate_takeoff="";


              foreach ($rstouch as $rs_rstouch){ 

                //echo $estimate_time.'-';
                //echo strtotime($rs_data['etime_arrival']);


                //$estimate_time=strtotime(date('H:i:s'));
                if (htmlentities($rs_rstouch['landing'])=="HANGAR"){
                  $estimate_landing="HANGAR";
                            $estimate_takeoff=$estimate_time;
                  $estimate_time=$estimate_takeoff;

                }else{
                  $estimate_landing=date('H:i:s', strtotime('+7 minutes', strtotime($estimate_time)));
                  $estimate_takeoff=$estimate_landing;
                  $estimate_time=$estimate_landing;
                }

                
                if (htmlentities($rs_rstouch['takeoff'])=="HANGAR"){
                  $estimate_takeoff="HANGAR";
                }

                $total_time_check="";

                if ($rs_rstouch['total_time']=="00:00"){
                  $total_time_check="";
                }else{
                  $total_time_check=$rs_rstouch['total_time'];
                }

                // if ($estimate_takeoff=="HANGAR"){
                //   $total_time_check='<strong>'.$rstouch_time["ttime"].'</strong>';
                // }
if ($estimate_takeoff == "HANGAR") {
    $total_time_check = '<strong>' . substr($rstouch_time["ttime"], 0, 8) . '</strong>';
}



                ?>
                  <tr >
                    <td class="align-middle" class="alert-danger"><?php echo htmlentities($rs_rstouch['flight_date']); ?></td>
                    <td class="align-middle" class="alert-danger"><?php echo htmlentities($estimate_landing); ?></td>
                    <td class="align-middle" class="alert-danger"><?php echo htmlentities($estimate_takeoff); ?></td>

                    <td class="align-middle"><?php echo htmlentities($rs_rstouch['flight_date']); ?></td>
                    <td class="align-middle"><?php echo htmlentities($rs_rstouch['landing']); ?></td>
                    <td class="align-middle"><?php echo htmlentities($rs_rstouch['takeoff']); ?></td>
                    <td class="align-middle"><?php echo $total_time_check; ?></td>

                  
                    </tr>
                <?php $remove_ctr++;} ?>



                </tbody>
                </table>

                <?php } ?>



<br>

<div class="modal fade" id="modalDialogPrint" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Window</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"><div id="printArea"><?php require('flight_plan_view_print.php'); ?></div></div>
            <div class="modal-footer"> 
                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><span class="bi-x-octagon"  data-toogle="tooltip" data-placement="bottom" title="Print"></span> Close</button> 
                <button id="Print" type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span class="bi-printer"  data-toogle="tooltip" data-placement="bottom" title="Print"></span> Print</button> 
            </div>
        </div>
    </div>
</div>


<style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible;
  }
  #printSection {
    position:absolute;
    left:0;
    top:0;
  }
}
</style>

<script>
document.getElementById("Print").onclick = function () {
    printElement(document.getElementById("printArea"));
};

function printElement(elem) {
    var domClone = elem.cloneNode(true);

    var $printSection = document.getElementById("printSection");

    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }

    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
}
</script>


            

 <!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>	

<script>
        const labelData = {
                placeholder: "Hanapin...",
                noRows: "No record to display",
                info: "Showing {start} to {end} of {rows} record (Page {page} of {pages} pages)"
            }


    const dataTable = new simpleDatatables.DataTable("#tablelist", {
        searchable: true,
        fixedHeight: true,
        //perPage: 25,
        //labels: labelData,
    });

    //dataTable.columns().hide([1, 2])
    //dataTable.columns().remove([0, 2, 3, 6]);
    //dataTable.columns().order([0, 2, 1]);
    
</script>



</body>
</html>
<?php ob_flush(); 
$db->close();
?>
