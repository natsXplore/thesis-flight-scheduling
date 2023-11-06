<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php

if (!isset($_SESSION)) {
    session_start();
  }

  
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

$query_rs = "select * FROM inspection_sched i INNER JOIN flight_sched fs ON fs.flight_sched_id=i.flight_sched_id INNER JOIN lup_aircraft a  ON a.lup_aircraft_id=i.lup_aircraft_id ORDER BY i.inspection_sched_id DESC";
$db->query($query_rs);
$rs=$db->rowset();


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

<div class="table-responsive">
    
<table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
    <thead>
        <tr class="alert-info">
            <th>Aircraft ID</th>
            <th>Effective Date</th>
            <th>Flight Date</th>
            <th>Departure Time</th>
            <th>Pilot in Command</th>
            <th>Type</th>
            <th>Status</th>
            
            </th>
               <th data-sortable="false"  width="110px">
                <div class="btn-group" role="group" align="center">
                    <!--<a href="inspect_new.php" class="btn btn-outline-secondary" data-toogle="tooltip" data-placement="bottom" title="New"><span class="bi-plus-square"></span> </a>-->
                    <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span></a>

                    
                </div>
            </th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($rs as $rs_data){ 

      $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE `flight_sched_id`=? AND u.group=?";
      $db->query($query_rs);
      $db->bind(1,$rs_data['flight_sched_id']);
      $db->bind(2,'Instructor');
      $rscount_instructor=$db->rowsingle();
      $rscount_instructor_total=$db->rowcount();
?>
    <tr >
    <td class="align-middle"><?php echo htmlentities($rs_data['aircraft_id']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['effective_date']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['flight_date']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['time_departure']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['firstname']).' '.htmlentities($rscount_instructor['lastname']).' '.htmlentities($rscount_instructor['extname']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['inspection_type']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['inspection_status']); ?></td>
    <td  class="align-middle" width="110px">
        <div class="btn-group" role="group" align="center">
            <?php  if(file_exists('../images/aircraft_maintenance_sched/'.htmlentities($rs_data['inspection_sched_id']).'.pdf')) {?>
            <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPDF<?php echo htmlentities($rs_data['inspection_sched_id']);?>"><span class="bi-eye"  data-toogle="tooltip" data-placement="bottom" title="View Inspection Report"></span></a>

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

        <?php  if(file_exists('../images/aircraft_maintenance_sched_post/'.htmlentities($rs_data['inspection_sched_id']).'.pdf')) {?>
            <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPDF<?php echo htmlentities($rs_data['inspection_sched_id']);?>"><span class="bi-eye"  data-toogle="tooltip" data-placement="bottom" title="View Inspection Report"></span></a>

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
            <a href="inspect_print_one.php?recordID=<?php echo $rs_data['inspection_sched_id'];?>" target="blank" class="btn btn-outline-secondary" ><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span></a>
            <a href="inspect_update.php?recordID=<?php echo $rs_data['inspection_sched_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Update"><span class="bi-pencil-square"></span></a>
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
            <div class="modal-body"><div id="printArea"><?php require('inspect_print.php'); ?></div></div>
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
