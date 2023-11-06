
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong><?php echo strtoupper(htmlentities($_SESSION['title'])); ?></strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

<table id="tablelist_print" class="table table-bordered" width="100%">
  <thead >
    <tr class="alert-info">
      <th>#</th>
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
  <?php $ctr=0; foreach ($rs as $rs_user) { $ctr++; 
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
      <td class="align-middle"><?php echo $ctr; ?></td>
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
<?php require_once('../template/footer_print.php');?>
