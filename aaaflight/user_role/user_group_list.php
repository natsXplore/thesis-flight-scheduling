<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

$query_rs = "select * FROM `user_group` order by `group`";
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
<body onLoad="addRowHandlers('tablelist',1, 0,'../user_role/user_group_restriction.php');">
<?php require_once('../template/header.php'); ?>
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->

<table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-secondary">
                <th>Group</th>
                <th  data-sortable="false"  width="110px">
                <div class="btn-group" role="group" align="center">
                    <a href="user_group_new.php" class="btn btn-outline-secondary" data-toogle="tooltip" data-placement="bottom" title="New"><span class="bi-plus-square"></span> </a>
                </div></th>
            </tr>
        </thead>
        <tbody>

<?php foreach ($rs as $rs_data){ 
    
    $query_rs = "SELECT u.* FROM `user_restriction` u WHERE `group`=?";
    $db->query($query_rs);
    $db->bind(1, htmlentities($rs_data['group']));
    $rsgroup=$db->rowset();
    $rsgroup_total=$db->rowcount();
    ?>
    <tr >
    <td class="align-middle"><?php echo htmlentities($rs_data['group']); ?></td>
    <td  class="align-middle" width="110px">
        <div class="btn-group" role="group" align="center">
            <a href="user_group_update.php?recordID=<?php echo $rs_data['group'];?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Update"><span class="bi-pencil-square"></span></a>
                <?php  if ( $rsgroup_total==0) { ?>
            <a href='user_group_remove.php?recordID=<?php echo $rs_data["group"];?>' class="btn btn-outline-danger" data-toogle="tooltip" data-placement="bottom" title="Remove"><span class="bi-trash"></span></a>

                <?php } ?>
        </div>
    </td>
    </tr>
<?php } ?>
</tbody>
</table>
    
<script>
        const labelData = {
                placeholder: "Search...",
                noRows: "No record to display",
                info: "Showing {start} to {end} of {rows} record (Page {page} of {pages} pages)"
            }


    const dataTable = new simpleDatatables.DataTable("#tablelist", {
        searchable: true,
        fixedHeight: true,
        //labels: labelData,
    });

    //dataTable.columns().hide([1, 2])

    
</script>
<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>

</body>

</html>
<?php ob_flush(); 
$db->close();
?>
