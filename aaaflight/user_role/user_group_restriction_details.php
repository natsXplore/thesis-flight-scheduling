<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

$query_rs = "SELECT * FROM `user_menu` WHERE `parent_id` = ? AND NOT href='#' ORDER BY id, parent_id";
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
        <div class="card-header">
        <h5 class="card-title"><strong><?php echo 'User Restriction for <strong><a href="user_group_restriction.php?id='.htmlentities($_GET['group']).'">'.htmlentities($_GET['group']).'</a></strong>';?></strong></h5>
          <h5 class="card-title"><strong><?php echo 'Link under <strong>'.htmlentities($_GET['menu']).'</strong>';?></strong></h5>
        </div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->


<table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead >
            <tr class="alert-secondary">
                <th data-sortable="false" width="60px"><a href="user_group_restriction.php?id=<?php echo htmlentities($_GET['group']); ?>" class="btn btn-outline-secondary" data-toogle="tooltip" data-placement="bottom" title="Goto User Group"><span class="bi bi-card-list" ></span></a></th>
                <th data-sortable="false" >Name</th>
                <th data-sortable="false" >Icon</th>
                <th data-sortable="false" >Order</th>
            </tr>
        </thead>
        <tbody>

          <?php foreach ($rs as $rs_data){ ?>
              <tr>
                <td  class="align-middle" width="60px"><input type="checkbox" style="vertical-align: middle; margin: 0px;" name="<?php echo htmlentities($rs_data['id']); ?>" id="<?php echo htmlentities($rs_data['id']); ?>" value="<?php echo htmlentities($rs_data['id']); ?>" 
                 onclick="sendID('user_group_restriction_updater.php','menu_id',this.value + '&group=<?php echo htmlentities($_GET['group']); ?>');"
                <?php
                  echo $phu->find_restriction(htmlentities($_GET['group']),htmlentities($rs_data['id']));
                ?>
                ></td>
                <td class="align-middle"><?php if ((strcmp(htmlentities($rs_data['id']),htmlentities($rs_data['parent_id'])))) { echo '&nbsp;&nbsp;&nbsp;<span class="fa fa-check-circle-o"></span>&nbsp;'; echo htmlentities($rs_data['name']);} else { echo '<span class="fa fa-square"></span>&nbsp;';echo htmlentities($rs_data['name']);} ?></td>
                <td class="align-middle"><?php echo '<span class="'.htmlentities($rs_data['icon']).'"></span> '.htmlentities($rs_data['icon']); ?></td>
                <td class="align-middle"><?php echo htmlentities($rs_data['order']); ?></td>
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
