
<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php

$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$query_rs = "SELECT n.*,u.firstname, u.middlename, u.lastname, u.extname,u.fil, u.cpl, u.mecl, u.medl, u.elp  FROM `notif` n INNER JOIN user u ON u.id=n.user_id WHERE user_id=? AND `read`=? order by n.date_modified DESC";
$db->query($query_rs);
$db->bind(1,$_SESSION['MM_ID']);
$db->bind(2,'yes');
$rsyes=$db->rowset();
$rsyes_total=$db->rowcount();

$query_rs = "SELECT n.*,u.firstname, u.middlename, u.lastname, u.extname,u.fil, u.cpl, u.mecl, u.medl, u.elp FROM `notif` n INNER JOIN user u ON u.id=n.user_id WHERE user_id=? AND `read`=? order by n.date_modified DESC";
$db->query($query_rs);
$db->bind(1,$_SESSION['MM_ID']);
$db->bind(2,'no');
$rs=$db->rowset();
$rs_total=$db->rowcount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <title><?php echo $app_title; ?>  </title>
</head>
<?php require_once('../template/phplink.php'); ?>
<body>

<?php require_once('../template/header.php'); ?>

<div class="card">
<div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->

<div class="row"><br></div>
<table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-info">
                <th>Notification</th>
                <th>Date Posted</th>
                <th>Status</th> 
                <th><a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span> Print</a></th> 
            </tr>
        </thead>
        <tbody>

<?php foreach ($rs as $rs_data){ 
    if ($rs_data['read']=='no'){$notif_status="UnRead";} else {$notif_status="Read";}

    ?>
    <tr>
    <td class="align-middle"><?php echo htmlentities($rs_data['notification']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['date_created']); ?></td>
    <td class="align-middle"><?php echo $notif_status; ?></td>
    <td class="align-middle">
        <a href="notif_update.php?recordID=<?php echo $rs_data['notif_id'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Mark as Read"><span class="bi bi-file-earmark-check"></span></a>
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
    </td>
    </tr>
<?php } ?>
</tbody>
</table>

<?php if ($rsyes_total>0){ ?>
<h5 class="card-title"></h5>
<div class="accordion" id="accordionExample">

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne"> <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"> <h5>Archive</h5> </button></h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

            <div class="accordion-body"> 
            <table id="tablelist2" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-info">
                <th>Notification</th>
                <th>From</th>
                <th>Date Posted</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($rsyes as $rsyes_data){ 

            ?>
            <tr>
                <td class="align-middle"><?php echo htmlentities($rsyes_data['notification']); ?></td>
                <td class="align-middle"><?php echo htmlentities($rsyes_data['user_from']); ?></td>
                <td class="align-middle"><?php echo htmlentities($rsyes_data['date_created']); ?></td>
                <td>
                    <?php  if(file_exists('../images/aircraft_maintenance_sched/'.htmlentities($rsyes_data['inspection_sched_id']).'.pdf')) {?>
                    <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPDF<?php echo htmlentities($rsyes_data['inspection_sched_id']);?>"><span class="bi-eye"  data-toogle="tooltip" data-placement="bottom" title="View Inspection Report"></span></a>

                    <div class="modal fade" id="modalDialogPDF<?php echo htmlentities($rsyes_data['inspection_sched_id']);?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Inspection Report</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" height="100%"><div id="PDFArea"> 
                                    <div align="center">
                                        <object data="<?php echo '../images/aircraft_maintenance_sched/'.htmlentities($rsyes_data['inspection_sched_id']).'.pdf';?>"  width="100%"  height="700px"> </object>
                                    </div>  
                                </div></div>
                                <div class="modal-footer"> 
                                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal"><span class="bi-x-octagon"  data-toogle="tooltip" data-placement="bottom" title="Print"></span> Close</button> 
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        </table>


    </div>



</div>

<?php } ?>
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

    const dataTable = new simpleDatatables.DataTable("#tablelist2", {
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
            <div class="modal-body"><div id="printArea"><?php require('notif_print.php'); ?></div></div>
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
