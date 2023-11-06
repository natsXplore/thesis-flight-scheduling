<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php

$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$query_rs = "SELECT u.* FROM `user` u WHERE `group`='Student' ORDER BY u.`lastname`, firstname, middlename";
$db->query($query_rs);
$rs1=$db->rowset();

foreach ($rs1 as $rs_user){ 

    $query_rs = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_time))) 'tgo_hours' FROM flight_touch_details WHERE user_id=?";
    $db->query($query_rs);
    $db->bind(1, $rs_user['id']);
    $rstgo=$db->rowsingle();
    $rstgo_total=$db->rowcount();

    $query_rs = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(fs.total_time))) 'cc_hours' FROM flight_sched fs INNER JOIN flight_sched_passenger fsp ON fsp.flight_sched_id=fs.flight_sched_id WHERE fsp.user_id=?";
    $db->query($query_rs);
    $db->bind(1, $rs_user['id']);
    $rscc=$db->rowsingle();
    $rscc_total=$db->rowcount();

    $tgo="00:00:00";
    $cc="00:00:00";

    if ($rstgo_total>0 && $rstgo['tgo_hours']!=NULL){
       $tgo=$rstgo['tgo_hours'];
    }

    if ($rscc_total>0  && $rscc['cc_hours']!=NULL){
        $cc=$rscc['cc_hours'];
    }
    

    $SQLcrud = "UPDATE user SET tgo_hours=?, cc_hours=? WHERE id=?";
    $db->query($SQLcrud);
    $db->bind(1,$tgo);
    $db->bind(2,$cc);
    $db->bind(3,$rs_user['id']);
    $db->execute();

}



#$query_rs = "SELECT u.*, format(u.required_hours- (((TIME_TO_SEC(tgo_hours)+TIME_TO_SEC(cc_hours))/60)/60),0) 'bal' FROM `user` u WHERE `group`='Student' ORDER BY u.`lastname`, firstname, middlename";
$query_rs = "SELECT u.*,replace(SEC_TO_TIME((u.required_hours*60*60)- (((TIME_TO_SEC(tgo_hours)+TIME_TO_SEC(cc_hours))))),'.00','') 'bal' FROM `user` u WHERE `group`='Student' ORDER BY u.`lastname`, firstname, middlename";
$db->query($query_rs);
$rs=$db->rowset();


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
<a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span> Print</a>
<div class="row"><br></div>
<div class="table-responsive">
    <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-info">
                <th>Full Name</th>
                <th>Designation</th>
                <th>Required Hours</th>
                <th>Touch and Go Hours</th>
                <th>Cross Country Hours</th>
                <th>Remaining Hours</th>
                <th>Status</th>
               
            </tr>
        </thead>
        <tbody>

<?php foreach ($rs as $rs_user){ 
    $query_rs = "SELECT u.* FROM `user_restriction` u WHERE `group`=?";
    $db->query($query_rs);
    $db->bind(1, htmlentities($rs_user['group']));
    $rsgroup=$db->rowset();
    $rsgroup_total=$db->rowcount();
   

    $bal=0;
    if ($rs_user['bal']>0){
        $b=explode(":",$rs_user['bal']);
        $bal=$b[0].' Hours '.$b[1].' Minutes and '.$b[2].' Seconds';

    }

    ?>
    <tr>
    <td class="align-middle"><?php echo htmlentities($rs_user['firstname']).' '.htmlentities($rs_user['lastname']).' '.htmlentities($rs_user['extname']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['designation']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['required_hours']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['tgo_hours']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['cc_hours']); ?></td>
    <td class="align-middle"><?php echo htmlentities($bal); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['status']); ?></td>
   
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
            <div class="modal-body"><div id="printArea"><?php require('student_print.php'); ?></div></div>
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
