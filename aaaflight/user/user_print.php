
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong>User List</strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

<table id="tablelist_print" class="table table-bordered" width="100%">
  <thead >
    <tr class="alert-info">
      <th>#</th>
      <th>Full Name</th>
      <th>Designation</th>
      <th>User Group</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php $ctr=0; foreach ($rs as $rs_user) { $ctr++; ?>
    <tr>
      <td class="align-middle"><?php echo $ctr; ?>.</td>
      <td class="align-middle"><?php echo htmlentities($rs_user['firstname']).' '.htmlentities($rs_user['lastname']).' '.htmlentities($rs_user['extname']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_user['designation']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_user['group']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_user['status']); ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
