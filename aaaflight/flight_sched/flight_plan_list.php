<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php

 
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    if ($_SESSION['MM_UserGroup']=="Administrator"){
        $query_rs = "select * FROM flight_sched fs WHERE flight_date>=? AND flight_date<=? ORDER BY flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $db->bind(1,$_POST['from']);
        $db->bind(2,$_POST['to']);
        $rs=$db->rowset();
    }else {
        $query_rs = "select * FROM flight_sched fs RIGHT JOIN flight_sched_passenger fsp ON fsp.flight_sched_id=fs.flight_sched_id WHERE fs.flight_date>=? AND fs.flight_date<=? AND fsp.user_id=? ORDER BY fs.flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $db->bind(1,$_POST['from']);
        $db->bind(2,$_POST['to']);
        $db->bind(3, $_SESSION['MM_ID']);
        $rs=$db->rowset();
    }
} else {
    if ($_SESSION['MM_UserGroup']=="Administrator"){
        $query_rs = "select * FROM flight_sched fs WHERE flight_date>=CURRENT_DATE() AND flight_date<=DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)  ORDER BY flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $rs=$db->rowset();
    }else {
        $query_rs = "select * FROM flight_sched fs RIGHT JOIN flight_sched_passenger fsp ON fsp.flight_sched_id=fs.flight_sched_id WHERE fs.flight_date>=CURRENT_DATE() AND flight_date<=DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)  AND fsp.user_id=? ORDER BY fs.flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $db->bind(1, $_SESSION['MM_ID']);
        $rs=$db->rowset();
    }
}


$SQLcrud = "UPDATE `user` SET flight_status='' WHERE  id IN (SELECT user_id FROM flight_sched_passenger WHERE flight_sched_id IS NULL OR flight_sched_id='')";
$db->query($SQLcrud);
$db->execute();

$SQLcrud = "UPDATE `lup_aircraft` SET flight_status='', flight_sched_slug='' WHERE lup_aircraft_id IN (SELECT lup_aircraft_id FROM flight_sched_passenger WHERE flight_sched_id IS NULL OR flight_sched_id='')";
$db->query($SQLcrud);
$db->execute();


$SQLcrud = "DELETE FROM flight_sched_passenger WHERE flight_sched_id IS NULL OR flight_sched_id=''";
$db->query($SQLcrud);
$db->execute();



?>
<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>
</head>
<?php require_once('../template/phplink.php'); ?>
<body >
<?php require_once('../template/header.php'); ?>

<div class="card">
<div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="post" name="form1" id="form1">
    <div class="form-horizontal">
    <fieldset>
    <div class="row">
        <div class="form-group  col-md-3 col-sm-12">
                <div class="form-floating ">
                <input required type="date" class="form-control col-form-label"  name="from" id="from" placeholder=" " value="<?php if (isset($_POST['from'])) echo $_POST['from']; else echo date('Y-m-d');?>" >
                <label >Flight Date</label>
                </div>
            </div>

        <div class="form-group  col-md-3 col-sm-12">
        <div class="form-floating ">
            <input required type="date" class="form-control col-form-label"  name="to" id="to" placeholder=" " value="<?php if (isset($_POST['to'])) echo $_POST['to']; else echo date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'));?>" >
            <label >Flight Date</label>
        </div>
        </div>

        <div class="form-group  col-md-3 col-sm-12">
        <div class="form-floating ">
            <button type="submit" class="btn btn-primary" form="form1"><span class="bi-filter"></span> Filter</button>
            <a type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span> Print</a>
            <label> </label>
        </div>
        </div>

    </div>
    
    <input type="hidden" name="POSTcheck" value="form1">
    </fieldset>  
  </div>
</form>
<br>

<div class="table-responsive">
    <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
    <thead>
        <tr class="alert-info">
            <th>Aircraft ID / Validity</th>
            <th>Flight Date</th>
            <th>Departure - Arrival</th>
            <th>Pilot in Command</th>
            <th>Route</th>
            <th>Type</th>
            <th>Aircraft Status</th>
            <th>Flight Status</th>
            
            </th>
               <th data-sortable="false"  width="110px">
                <div class="btn-group" role="group" align="center">
                <?php if ($_SESSION['MM_UserGroup']=="Administrator"){ ?>
                    <a href="flight_plan_new.php" class="btn btn-outline-secondary" data-toogle="tooltip" data-placement="bottom" title="New"><span class="bi-plus-square"></span> </a>
                    <?php } ?>   
                        <!--<a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span></a>-->
                    
                </div>
            </th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($rs as $rs_data){ 

      $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE `flight_sched_id`=? AND u.group=?";
      $db->query($query_rs);
      $db->bind(1,$rs_data['flight_sched_id']);
      $db->bind(2,'Instructor');
      $rscount_instructor=$db->rowsingle();
      $rscount_instructor_total=$db->rowcount();

      $istatus="";
      $query_rs = "SELECT * FROM `inspection_sched` WHERE `flight_sched_id`=?";
      $db->query($query_rs);
      $db->bind(1,$rs_data['flight_sched_id']);
      $rsinspect=$db->rowsingle();
      $rsinspect_total=$db->rowcount();
      if ($rsinspect_total>0){
        $istatus=$rsinspect['inspection_status'];
      }

      $query_rs = "SELECT * FROM `inspection_sched` WHERE `flight_sched_id`=? AND inspection_type='PRE-FLIGHT'";
      $db->query($query_rs);
      $db->bind(1,$rs_data['flight_sched_id']);
      $rsinspect_pre=$db->rowsingle();
      $rsinspect_total=$db->rowcount();

      
?>
    <tr >
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['aircraft_id']).'<br>'.htmlentities($rscount_instructor['date_validity']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['flight_date']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['time_departure']).' - '.htmlentities($rs_data['etime_arrival']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['firstname']).' '.htmlentities($rscount_instructor['lastname']).' '.htmlentities($rscount_instructor['extname']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['departure_aerodome']).'-'.htmlentities($rs_data['destination_aerodome']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['training_type']); ?></td>
    <td class="align-middle" <?php if ($istatus=="NOT Ready for Flight") echo "style='background-color:red;'"?>><?php echo $istatus; ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['flight_status']); ?></td>
    <td  class="align-middle" width="150px">
        <div class="btn-group" role="group" align="center">
            <a href="flight_plan_view.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="View Attachment"><span class="bi-eye"></span></a>
            <a href="flight_plan_view_detail_print.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Print Report"><span class="bi-printer-fill"></span></a>
            <?php  if ($_SESSION['MM_UserGroup']=="Administrator" && $rs_data['training_type']=="Cross Country" && $istatus=="Ready for Flight") { ?>
                <a href="flight_plan_update.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Update Flight Status"><span class="bi-pencil-square"></span></a>                
            <?php } if ($_SESSION['MM_UserGroup']=="Administrator" && $rs_data['training_type']=="Touch and Go"  && $istatus=="Ready for Flight") { ?>
                <a href="flight_plan_update_touch.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Update Flight Status"><span class="bi-pencil-square"></span></a>                
            <?php } if ($_SESSION['MM_UserGroup']=="Administrator" || $_SESSION['MM_UserGroup']=="Instructor"){ 
                if ($rsinspect_total>0) { if( file_exists('../images/aircraft_maintenance_sched/'.htmlentities($rsinspect_pre['inspection_sched_id']).'.pdf')) {?>
                <a href="flight_plan_upload_report.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Upload Flight Report"><span class="ri-file-upload-line"></span></a>
            <?php } } } if (($_SESSION['MM_UserGroup']=="Administrator" && htmlentities($rs_data['flight_date'])>date('Y-m-d')) || ($_SESSION['MM_UserGroup']=="Administrator" )){ ?>
                <a href='flight_plan_remove.php?recordID=<?php echo $rs_data["flight_sched_id"];?>' class="btn btn-outline-danger" data-toogle="tooltip" data-placement="bottom" title="Cancel Flight"><span class="bi-trash"></span></a>
            <?php } ?>
        </div>
    </td>
    </tr>
<?php } ?>
</tbody>
</table>
</div>
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


<div class="modal fade" id="modalDialogPrint" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Window</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"><div id="printArea"><?php require('flight_plan_print.php'); ?></div></div>
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
<?php ob_flush(); 
$db->close();
?>
