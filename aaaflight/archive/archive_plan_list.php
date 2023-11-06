<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    if ($_SESSION['MM_UserGroup']=="Administrator"){
        $query_rs = "select * FROM flight_sched_archive fs WHERE flight_date>=? AND flight_date<=? ORDER BY flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $db->bind(1,$_POST['from']);
        $db->bind(2,$_POST['to']);
        $rs=$db->rowset();
    }else {
        $query_rs = "select * FROM flight_sched_archive fs RIGHT JOIN flight_sched_passenger fsp ON fsp.flight_sched_id=fs.flight_sched_id WHERE flight_date>=? AND flight_date<=? AND fsp.user_id=? ORDER BY fs.flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $db->bind(1, $_SESSION['MM_ID']);
        $db->bind(2,$_POST['from']);
        $db->bind(3,$_POST['to']);
        $rs=$db->rowset();
    }
} else {
    if ($_SESSION['MM_UserGroup']=="Administrator"){
        $query_rs = "select * FROM flight_sched_archive fs WHERE flight_date>=CURRENT_DATE() AND flight_date<=DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)  ORDER BY flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $rs=$db->rowset();
    }else {
        $query_rs = "select * FROM flight_sched_archive fs RIGHT JOIN flight_sched_passenger fsp ON fsp.flight_sched_id=fs.flight_sched_id WHERE fs.flight_date>=CURRENT_DATE() AND flight_date<=DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)  AND fsp.user_id=? ORDER BY fs.flight_date ASC, STR_TO_DATE(fs.time_departure,'%T') ASC";
        $db->query($query_rs);
        $db->bind(1, $_SESSION['MM_ID']);
        $rs=$db->rowset();
    }
}




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
            <input required type="date" class="form-control col-form-label"  name="to" id="to" placeholder=" " value="<?php if (isset($_POST['to'])) echo $_POST['to'];  else echo date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'));?>" >
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
            <th>Aircraft ID</th>
            <th>Aircraft Validity</th>
            <th>Flight Date</th>
            <th>Departure Time</th>
            <th>Estimated Time of Arrival</th>
            <th>Pilot in Command</th>
            <th>Route</th>
            
            </th>
               <th data-sortable="false"  width="110px">
                ACTION
            </th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($rs as $rs_data){ 

      $query_rs = "SELECT * FROM `flight_sched_passenger_archive` fsp INNER JOIN user u ON u.id=fsp.user_id INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE `flight_sched_id`=? AND u.group=?";
      $db->query($query_rs);
      $db->bind(1,$rs_data['flight_sched_id']);
      $db->bind(2,'Instructor');
      $rscount_instructor=$db->rowsingle();
      $rscount_instructor_total=$db->rowcount();
?>
    <tr >
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['aircraft_id']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['date_validity']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['flight_date']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['time_departure']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['etime_arrival']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['firstname']).' '.htmlentities($rscount_instructor['lastname']).' '.htmlentities($rscount_instructor['extname']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['route_begin']).'-'.htmlentities($rs_data['route_end']); ?></td>
    <td  class="align-middle" width="110px">
        <div class="btn-group" role="group" align="center">
            <a href="archive_plan_view.php?recordID=<?php echo $rs_data['flight_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="View"><span class="bi-eye"></span></a>
            
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
            <div class="modal-body"><div id="printArea"><?php require('archive_plan_print.php'); ?></div></div>
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
