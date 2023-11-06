<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php

$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$query_rs = "SELECT u.* FROM `user` u order by u.`lastname`, firstname, middlename";
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

<div class="row"><br></div>
<div class="table-responsive">
    <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-info">
                <th>Full Name</th>
                <th>Designation</th>
                <th>User Group</th>
                <th>Status</th>
                <th data-sortable="false" width="110px">
                    <div class="btn-group" role="group" align="center">
                        <a href="user_new.php" class="btn btn-outline-secondary hidelink"><span class="bi-person-plus-fill"  data-toogle="tooltip" data-placement="bottom" title="New"></span> </a>
                        <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalDialogPrint"><span class="bi-printer-fill"  data-toogle="tooltip" data-placement="bottom" title="Create Printable Form"></span></a>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>

<?php foreach ($rs as $rs_user){ 
    $query_rs = "SELECT u.* FROM `user_restriction` u WHERE `group`=?";
    $db->query($query_rs);
    $db->bind(1, htmlentities($rs_user['group']));
    $rsgroup=$db->rowset();
    $rsgroup_total=$db->rowcount();

    ?>
    <tr>
    <td class="align-middle"><?php echo htmlentities($rs_user['firstname']).' '.htmlentities($rs_user['lastname']).' '.htmlentities($rs_user['extname']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['designation']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['group']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_user['status']); ?></td>
    <td  class="align-middle" width="110px">
        <div class="btn-group" role="group" align="center">
            <a href='user_update.php?recordID=<?php echo htmlentities($rs_user["id"]);?>' class="btn btn-outline-success" class="btn btn-primary " data-toogle="tooltip" data-placement="bottom" title="Update"><span class="bi-person-check-fill" ></span></a>
            <?php  if ($rsgroup_total==0) { ?>
            <a href='user_remove.php?recordID=<?php echo htmlentities($rs_user["id"]);?>' class="btn btn-outline-danger" data-toogle="tooltip"  data-placement="bottom" title="Remove"><span class="bi-person-dash-fill" ></span></a>
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
            <div class="modal-body"><div id="printArea"><?php require('user_print.php'); ?></div></div>
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
