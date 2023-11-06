<?php

if (!isset($_SESSION)) {
    session_start();
  }

$query_rs = "SELECT n.*,u.firstname, u.middlename, u.lastname, u.extname,u.fil, u.cpl, u.mecl, u.medl, u.elp FROM `notif` n INNER JOIN user u ON u.id=n.user_id WHERE user_id=? AND `read`=?";
$db->query($query_rs);
$db->bind(1,$_SESSION['MM_ID']);
$db->bind(2,'no');
$rs_notif_userall=$db->rowset();
$rs_notif_userall_total=$db->rowcount();

    $query_rs = "SELECT n.*,u.firstname, u.middlename, u.lastname, u.extname,u.fil, u.cpl, u.mecl, u.medl, u.elp FROM `notif` n INNER JOIN user u ON u.id=n.user_id WHERE user_id=? AND `read`=? LIMIT 3";
    $db->query($query_rs);
    $db->bind(1,$_SESSION['MM_ID']);
    $db->bind(2,'no');
    $rs_notif_user=$db->rowset();
    $rs_notif_user_total=$db->rowcount();

if ($rs_notif_user_total>0){  ?>
<li class="nav-item dropdown">
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown"> <i class="bi bi-bell"></i> <span class="badge bg-primary badge-number"> <?php echo $rs_notif_user_total;?></span> </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
    <li class="dropdown-header"> You have <?php echo $rs_notif_userall_total;?> new notifications <a href="../notif/notif_list.php"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a></li>

        <?php foreach ($rs_notif_user as $row_rs_notif_users_user) { ?>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
                <h4><?php echo $row_rs_notif_users_user['user_from'];?></h4>
                <p><?php echo $row_rs_notif_users_user['notification'];?></p>
                <p><?php echo $row_rs_notif_users_user['date_created'];?></p>
            </div>
            </li>
        <?php } ?>

        <li class="dropdown-footer"> <a href="../notif/notif_list.php">Show all notifications</a></li>
    </ul>
</li>

<?php }?>