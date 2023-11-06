<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


if (isset($_GET['txtSearch']))
{ $xtxtsearch=htmlentities($_GET['txtSearch']);}
else
{ $xtxtsearch="";}

$query_rs = "select * FROM `user_group` where `group` LIKE ? order by `group`";
$db->query($query_rs);
$db->bind(1,"%".$xtxtsearch."%");
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
<body onload="window.print();">
<?php require_once('../template/header_print.php'); ?>
<br>
    
<table id="tablelist" class="display table table-bordered table-hover table-responsive" >
        <thead >
            <tr class="alert-primary">
                <th>Group</th>
        </thead>
        <tbody>

<?php foreach ($rs as $rs_data){ ?>
    <tr>
    <td><?php echo htmlentities($rs_data['group']); ?></td>
    </tr>
<?php } ?>
</tbody>
</table>
<?php require_once('../template/footer.php'); ?>

</body>

</html>
<?php ob_flush(); 
$db->close();
?>
