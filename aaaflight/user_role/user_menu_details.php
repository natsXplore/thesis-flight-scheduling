<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();



$query_rs = "select * FROM `user_menu` where `parent_id` = ? AND NOT href='#' ORDER BY `parent_id`,`id`";
$db->query($query_rs);
$db->bind(1,htmlentities($_GET['id']));
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
        <div class="card-header"><h5 class="card-title"><strong><?php echo 'Link under <strong>'.htmlentities($_GET['menu']).'</strong>';?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-secondary">
               
                <th >Name</th>
                <th data-sortable="false">Icon</th>
                <th >Order</th>
                <th  data-sortable="false" width="110px">
                    <div class="btn-group" role="group" align="center">
                    <a href="user_menu_new.php" class="btn btn-secondary" data-toogle="tooltip" data-placement="bottom" title="New"><span class="bi-plus-square"></span> </a>
                    <a href="user_menu_list.php" class="btn btn-secondary" data-toogle="tooltip" data-placement="bottom" title="Menu List"><span class="bi-menu-button-wide"></span> </a>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>

<?php foreach ($rs as $rs_data){ ?>
    <tr>
    
    <td class="align-middle"><?php if ((strcmp(htmlentities($rs_data['id']),htmlentities($rs_data['parent_id'])))) { echo '&nbsp;&nbsp;&nbsp;<span class="fa fa-check-circle-o"></span>&nbsp;'; echo '<a href="user_menu_details.php?id='.htmlentities($rs_data['id']).'&menu='.htmlentities($rs_data['name']).'">'.htmlentities($rs_data['name']).'</a>';} else { echo '<span class="fa fa-square"></span>&nbsp;';echo htmlentities($rs_data['name']);} ?></td>
    <td class="align-middle"><?php echo '<span class="'.htmlentities($rs_data['icon']).'"></span> '.htmlentities($rs_data['icon']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['order']); ?></td>
    <td  class="align-middle" width="110px">
        <div class="btn-group" role="group" align="center">
                
            <a href="user_menu_update.php?recordID=<?php echo htmlentities($rs_data['id']);?>" class="btn btn-outline-success" data-toogle="tooltip" data-placement="bottom" title="Update"><span class="bi-pencil-square"></span></a>
            <a href='user_menu_remove.php?recordID=<?php echo htmlentities($rs_data["id"]);?>' class="btn btn-outline-danger" data-toogle="tooltip" data-placement="bottom" title="Remove"><span class="bi-trash"></span></a>

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
        //perPage: 25,
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
