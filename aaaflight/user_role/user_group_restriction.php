<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

$query_rs = "SELECT *  FROM `user_menu` where `type`='m' and (id=parent_id) order by `order`,`parent_id`";
$db->query($query_rs);
$rs=$db->rowset();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $app_title; ?>  </title>
</head>
<?php require_once('../template/phplink.php'); ?>
<body>
<?php require_once('../template/header.php'); ?>
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo 'User Restriction for <strong>'.htmlentities($_GET['id']).'</strong>';?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->

<table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-secondary">
                <th data-sortable="false"  width="30px"><a href='user_group_list.php' class="btn btn-outline-secondary" data-toogle="tooltip" data-placement="bottom" title="Goto User Group"><span class="bi bi-card-list" ></span></a></th>
                <th data-sortable="false" >Name</th>
                <th data-sortable="false" >Icon</th>
                <th data-sortable="false" >Order</th>
            </tr>
        </thead>
        <tbody>
<span id="results"></span>
<?php foreach ($rs as $rs_data){ ?>
    <tr>
    <td class="align-middle"><input type="checkbox" style="vertical-align: middle; margin: 0px;" name="<?php echo htmlentities($rs_data['id']); ?>" id="<?php echo htmlentities($rs_data['id']); ?>" value="<?php echo htmlentities($rs_data['id']); ?>" 
     onclick="sendID('user_group_restriction_updater.php','menu_id',this.value + '&group=<?php echo htmlentities($_GET['id']); ?>');"
    <?php
      echo $phu->find_restriction(htmlentities($_GET['id']),htmlentities($rs_data['id']));
    ?>
    ></td>
    <td class="align-middle"><?php echo '&nbsp;&nbsp;&nbsp;<span class="fa fa-check-circle-o"></span>&nbsp;'; echo '<a href="user_group_restriction_details.php?id='.htmlentities($rs_data['id']).'&menu='.htmlentities($rs_data['name']).'&group='.htmlentities($_GET['id']).'">'.htmlentities($rs_data['name']).'</a>'; ?></td>
    <td class="align-middle"><?php echo '<span class="'.htmlentities($rs_data['icon']).'"></span> '.htmlentities($rs_data['icon']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['order']); ?></td>
    </tr>

<?php

  $query_rssub = "SELECT * FROM `user_menu` WHERE `parent_id` = ? and `id`!=`parent_id` and `type`='m' order by `order`,id";
  $db->query($query_rssub);
  $db->bind(1,htmlentities($rs_data['id']));
  $rssub=$db->rowset();
  $totalRows_rssub=$db->rowcount();
?>
  <?php if ($totalRows_rssub>0) { foreach ($rssub as $rssub_data){ ?>

   <tr>
    <td data-sortable="false" class="align-middle"><input type="checkbox" style="vertical-align: middle; margin: 0px;" name="<?php echo htmlentities($rssub_data['id']); ?>" id="<?php echo htmlentities($rssub_data['id']); ?>" value="<?php echo htmlentities($rssub_data['id']); ?>" 
     onclick="sendID('user_group_restriction_updater.php','menu_id',this.value + '&group=<?php echo htmlentities($_GET['id']); ?>');"
    <?php
      echo $phu->find_restriction(htmlentities($_GET['id']),htmlentities($rssub_data['id']));
    ?>
    ></td>
    <td data-sortable="false" class="align-middle"><?php if ((strcmp(htmlentities($rssub_data['id']),htmlentities($rssub_data['parent_id'])))) { echo '&nbsp;&nbsp;&nbsp;<span class="fa fa-check-circle-o"></span>&nbsp;'; echo '<a href="user_group_restriction_details.php?id='.htmlentities($rssub_data['id']).'&menu='.htmlentities($rssub_data['name']).'&group='.$_GET['id'].'">'.htmlentities($rssub_data['name']).'</a>';} else { echo '<span class="fa fa-square"></span>&nbsp;';echo htmlentities($rssub_data['name']);} ?></td>
    <td data-sortable="false" class="align-middle"><?php echo '<span class="'.htmlentities($rssub_data['icon']).'"></span> '.htmlentities($rssub_data['icon']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['order']); ?></td>
    </tr>
<?php } }?>

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
        perPage: 25,
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
