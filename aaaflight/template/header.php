<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>
<?php 

if (!isset($_SESSION)) {
   session_start();
 }

$phu=new php_util();
$db=new DatabaseConnect();

if (!(strcmp($phu->found_group($_SESSION['MM_UserGroup'],$menu_id),1)))
{$MM_authorizedUsers=$_SESSION['MM_UserGroup'];}

require_once('../admin/grant_checker.php'); ?>

<?php 
    $query_rs = "SELECT *  FROM `user_menu` where `type`='m' and (id=parent_id) order by `order`,`parent_id`";
    $db->query($query_rs);
    $rs_menu=$db->rowset();
?>

<!--<script>
   function update_menu_name(menu_name){
    $.ajax({
      url: "../template/change_session_menu.php",
      method:"POST",  
      dataType:"json",  
      data:{ menu_name:menu_name 
      },
      success:function(data)  
         { 
            //$('#mm_item_count').innerHTML=data.return_value;
            alert(data.active_status);

         }
   });
   }

</script>
-->

<style>
body {
  background-image: url('../images/bgdashboard.jpg');
}
</style>


      <header id="header" class="header fixed-top d-flex align-items-center">
         <div class="d-flex align-items-center justify-content-between"> <a href="#" class="logo d-flex align-items-center"> <img src="../images/logo.png" class="img-responsive" alt=""> <span class="d-none d-lg-block"><?php echo $app_title;?></span> </a> 
         <i class="bi bi-list toggle-sidebar-btn"></i></div>

         <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
               <?php require('notification.php');?>
               <li class="nav-item dropdown pe-3">
                  <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown"> 
                     <img src="
                     <?php $filename='../images/user/'.$_SESSION['MM_ID'].'.jpg';
                           if (file_exists($filename)) {
                              echo $filename;
                           } else {
                              echo $app_user_image_default;}?>" ;

                     alt="Profile" class="rounded-circle"> <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['MM_FullName'];?></span> </a>

                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                     <li class="dropdown-header">
                        <h6><?php echo $_SESSION['MM_FullName'];?></h6>
                        <span><?php echo $_SESSION['MM_Designation'];?></span>
                     </li>
                     <li>
                        <hr class="dropdown-divider">
                     </li>
                     <li> <a class="dropdown-item d-flex align-items-center" href="<?php echo $logoutAction ?>"> <i class="bi bi-box-arrow-right"></i> <span>Sign Out</span> </a></li>
                  </ul>
               </li>
            </ul>
         </nav>
      </header>

      <aside id="sidebar" class="sidebar">
         <ul class="sidebar-nav" id="sidebar-nav">
            <!--<li class="nav-item"> <a class="nav-link " href="../admin/index.php"> <i class="bi bi-grid"></i> <span>Dashboard</span> </a></li>-->
      
        <?php 
         
        foreach ($rs_menu as $row_rs_menu){ 
             $query_rssub = "SELECT * FROM `user_menu` WHERE `parent_id` = ? and `id`!=`parent_id` and `type`='m' order by `order`";
             $db->query($query_rssub);
             $db->bind(1,$row_rs_menu['id']);
             $rssub=$db->rowset();
             $totalRows_rssub=$db->rowcount();
     
             if ($totalRows_rssub==0) {
                 if (!(strcmp($phu-> found_group($_SESSION['MM_UserGroup'],$row_rs_menu['id']),1))){
       
                    echo '<li> <a class="nav-link '.$phu->get_active_menu($_SESSION['title'],htmlentities($row_rs_menu["name"])).'"  href="'.htmlentities($row_rs_menu["href"]).'"> <i class="'.htmlentities($row_rs_menu["icon"]).'"></i><span>'. htmlentities($row_rs_menu["name"]).'</span> </a>';}
                 }
             elseif ($totalRows_rssub>0){

                if (!(strcmp($phu-> found_group($_SESSION['MM_UserGroup'],$row_rs_menu['id']),1))) {
                    
                    echo '<a class="nav-link collapsed" data-bs-target="#'.htmlentities($row_rs_menu["description"]).'" data-bs-toggle="collapse" href="#"> <i class="'.htmlentities($row_rs_menu["icon"]).'"></i><span>'.htmlentities($row_rs_menu["name"]).'</span><i class="bi bi-chevron-down ms-auto"></i> </a>';
                    echo '<ul id="'.htmlentities($row_rs_menu["description"]).'" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
                    
                     foreach ($rssub as $row_rssub){  

                         if (!(strcmp($phu-> found_group($_SESSION['MM_UserGroup'],$row_rssub['id']),1))) {
                            echo '<li>  <a href="'.htmlentities($row_rssub["href"]).'" class="'.$phu->get_active_menu($_SESSION['title'],htmlentities($row_rssub["name"])).'"> <i class="'.htmlentities($row_rssub["icon"]).'"></i><span>'.htmlentities($row_rssub["name"]).'</span> </a></li>';
                         }
                     }
                    echo '</ul>';
                }
               
            }
            echo '</li>';
        }

        ?>

         </ul>
      </aside>

      <main id="main" class="main" >
         <!--<div class="pagetitle">
            <h1></h1>
            <nav>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html"></a></li>
                  <li class="breadcrumb-item active"></li>
               </ol>
            </nav>
         </div>-->

         <section class="section dashboard">
            <div class="row">
               
                  

      



