<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


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

$query_rs = "select * FROM `lup_level` where `level`=?";
$db->query($query_rs);
$db->bind(1,$rs_data['level']);
$rslevel=$db->rowsingle();

$query_rs = "select * FROM `flight_touch_details` where `flight_sched_id`=? ORDER BY flight_touch_details_id ASC";
$db->query($query_rs);
$db->bind(1,$rs_data['flight_sched_id']);
$rstouch=$db->rowset();
$rstouch_total=$db->rowcount();

$query_rs = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(ftd.total_time))) 'ttime' FROM flight_touch_details ftd WHERE `flight_sched_id`=?";
$db->query($query_rs);
$db->bind(1,$rs_data['flight_sched_id']);
$rstouch_time=$db->rowsingle();

$query_rs = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(ftd.eta))) 'eta' FROM flight_touch_details ftd WHERE `flight_sched_id`=?";
$db->query($query_rs);
$db->bind(1,$rs_data['flight_sched_id']);
$rstouch_time_total=$db->rowsingle();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>

</head>
    
   
<?php require_once('../template/phplink.php'); ?>

<script>

function save_remarks(){  
  
  let var_sched_id =document.getElementById('sched_id').value;
  let var_note =document.getElementById('note').value;
    
    //Swal.fire('Information', var_flight_date,"info");
    $.ajax({  
      url:"save_remarks.php",  
      method:"POST",  
      dataType:"json",  
      data:{  
        sched_id:var_sched_id,
        note:var_note  
      },  
      success:function(data)  
      { 

      } 
    });  
}
         

</script>

<body>
<?php require_once('../template/header.php'); ?> 
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<a type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span> Print</a>
<br>
<div class="row">

  <div class="form-group col-lg-2 col-md-4 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
    <input readonly type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
  </div>

  <div class="form-group col-lg-2 col-md-4 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Time of Departure</strong></label>
    <input readonly type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value="<?php echo htmlentities($rs_data['time_departure']);?>">
  </div>

  <div class="form-group col-lg-2 col-md-4 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Time of Arrival</strong></label>
    <input readonly type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
  </div>

  <div class="form-group col-lg-3 col-md-6 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Training Type</strong></label>
    <input readonly type="text" class="form-control"  name="training_type" id="training_type" placeholder=" " value="<?php echo htmlentities($rs_data['training_type']);?>">
  </div>

  <div class="form-group col-lg-3 col-md-6 col-sm-12">
      <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
      <div class="col-sm-12">
      <?php foreach ($rsaircraft as $rs_rowaircraft){ 
        if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1){
        ?>
        <input readonly class="form-control" type="text" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['aircraft_id']);?>">  <label class="form-check-label" for=""></label>
      <?php } }?>
      </div>
  </div>
    
      <div class="form-group col-lg-4 col-md-4 col-sm-12">
        <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
        <div class="col-sm-12 ">
        <?php foreach ($rsstudent as $rs_rowstudent){ 
          if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1){
          ?>
          <input readonly id="sid" name="sid" class="form-control"  value="<?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']).'  ';?>"> <label class="form-check-label" for=""></label>
        <?php } }?>
        </div>
    </div>



  <div class="form-group col-lg-4 col-md-4 col-sm-12">
      <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
      <div class="col-sm-12">
      <?php foreach ($rsinstructor as $rs_rowinstructor){ 
        if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1) {?>
        <input readonly  class="form-control" type="text" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?>"> <label class="form-check-label" for=""></label>
      <?php } }?>
      </div>
  </div>



<div class="form-group col-lg-4 col-md-4 col-sm-12">
      <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
      <input id="note" name="note" class="form-control"  value="<?php echo htmlentities($rs_data['note']);?>"> <label class="form-check-label" for=""></label>
  </div>
</div>

<div class="row">


</div>


  <div class="form-group col-md-2 col-sm-12">
    <button onclick="save_remarks();" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Save Remarks"><span class="bi-save"></span> Save Remarks</button>
  </div>


  <input type="hidden" name="sched_id" id="sched_id" value="<?php echo $rs_data['flight_sched_id']; ?>">
<br>

<div class="table-responsive">
    <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
    <thead>
      <tr class="alert-success">
          <th colspan="4" class="alert-danger" class="align-middle">ESTIMATED TIME</th>
          <th colspan="6" class="alert-success" class="align-middle">ACTUAL TIME</th>
        </tr>

        <tr class="alert-info">
            <th class="alert-danger">Date</th>
            <th class="alert-danger">Landing</th>
            <th class="alert-danger">Take-Off</th>
            <th class="alert-danger">Total Time</th>
            
            

            <th>Date</th>
            <th>Landing</th>
            <th>Take-Off</th>
            <th>Total Time</th>
            <th data-sortable="false"  width="100px"> 
              <div align="center">
                    SET TIME
              </div>
            </th>
               <th data-sortable="false"  width="110px">
                <div class="btn-group" role="group" align="center">
                    ACTION
                </div>
            </th>
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

//   if ($estimate_takeoff=="HANGAR"){
//     $total_time_check='<strong>'.$rstouch_time["ttime"].'</strong>';
//   }
if ($estimate_takeoff == "HANGAR") {
  $total_time_check = substr($rstouch_time["ttime"], 0, 8);
}


// 
// 
// 
  $eta="";

  if ($rs_rstouch['eta']=="00:00"){
    $eta="";
  }else{
    $eta=$rs_rstouch['eta'];
  }
  
  if ($estimate_takeoff == "HANGAR") {
  $eta = substr($rstouch_time_total["eta"], 0, 8);
}


  ?>
    <tr >
    <td class="align-middle" class="alert-danger"><?php echo htmlentities($rs_rstouch['flight_date']); ?></td>
    <td class="align-middle" class="alert-danger"><?php echo htmlentities($estimate_landing); ?></td>
    <td class="align-middle" class="alert-danger"><?php echo htmlentities($estimate_takeoff); ?></td>
    <td class="align-middle" class="alert-danger"><?php echo htmlentities($eta); ?></td>
    

    <td class="align-middle"><?php echo htmlentities($rs_rstouch['flight_date']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_rstouch['landing']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_rstouch['takeoff']); ?></td>
    <td class="align-middle"><?php echo $total_time_check; ?></td>

    <td  class="align-middle" width="100px">
        <div align="center">
          <?php if ($rs_rstouch['landing']=="" && $rs_rstouch['takeoff']!="HANGAR") { ?>
          <a href="touch_landing.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-primary" data-toogle="tooltip" data-placement="bottom" title="Set Landing Time"><span class="bx bxs-plane-land"></span></a>
          <?php } else if ($rs_rstouch['takeoff']=="") { ?>
          <a href="touch_takeoff.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-primary" data-toogle="tooltip" data-placement="bottom" title="Set Take-Off Time"><span class="bx bxs-plane-take-off"></span></a>
          <?php }?>
        </div>
    </td>

    <td  class="align-middle" width="210px">
        <div class="btn-group" role="group" align="center">
            <!--<a href="touch_update.php?recordID=<?php //echo $rs_rstouch['flight_touch_details_id'];?>&sched_id=<?php //echo $rs_data["flight_sched_id"];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Update"><span class="bi-pencil-square"></span></a>-->
            <?php if ((($rs_rstouch['landing']!="HANGAR" && $rs_rstouch['landing']!="")  && $rs_rstouch['takeoff']=="" && $rstouch_total>2)) { ?>
            <a href='touch_update.php?recordID=<?php echo $rs_rstouch["flight_touch_details_id"];?>&sched_id=<?php echo $rs_data["flight_sched_id"];?>' class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="SET Flight Status to arrived"><span class="bi-person-check-fill"></span></a>
            <?php }?>
            <?php if ($rstouch_total-1==$remove_ctr) { ?>
            <a href='touch_remove.php?recordID=<?php echo $rs_rstouch["flight_touch_details_id"];?>&sched_id=<?php echo $rs_data["flight_sched_id"];?>' class="btn btn-outline-danger" data-toogle="tooltip" data-placement="bottom" title="Remove"><span class="bi-trash"></span></a>
            <?php }?>
        </div>
    </td>
    </tr>
<?php $remove_ctr++;} ?>



</tbody>
</table>
</div>


<div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">

          <a href="flight_plan_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Close</a> 
          </div>
        </div>
<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>

<?php require_once('../template/footer.php'); ?>

<div class="modal fade" id="modalDialogPrint" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Window</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"><div id="printArea"><?php require('flight_plan_update_touch_print.php'); ?></div></div>
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

</body>
</html>
<?php
$db->close();
?>
