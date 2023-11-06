<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$phu=new php_util();

$loginFormAction = $_SERVER['PHP_SELF'];


if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization ='group';
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "log-in.php";
  $MM_redirecttoReferrer = true;

  $db=new DatabaseConnect();
  
  $LoginRS_query="select * from user where `username`=? and `password`=? and `status`='active'";
  $db->query($LoginRS_query);
  $db->bind(1,$_POST['username']);
  $db->bind(2,$phu->encryptMessage($_POST['password']));
  $LoginRS=$db->rowsingle();
  $loginFoundUser=$db->rowcount();
	
  if ($loginFoundUser) {
    $loginid = $LoginRS['id'];
    $loginfullname  = $LoginRS['firstname'].' '.$LoginRS['lastname'];
    $loginStrGroup  = $LoginRS['group'];
    $loginStrDesignation = $LoginRS['designation'];
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
	      $_SESSION['MM_FullName'] = $loginfullname;
        $_SESSION['MM_Username'] = $loginUsername;
        $_SESSION['MM_UserGroup'] = $loginStrGroup;
        $_SESSION['MM_Designation'] = $loginStrDesignation;
        $_SESSION['MM_ID']=$loginid;
       
        $date=date_create(date('Y-m-d'));
        date_add($date,date_interval_create_from_date_string("30 days"));
        $expire_date= date_format($date,"Y-m-d");

        $query_rs = "select * FROM `user` WHERE `group`=? and `status`=?  and date(cpl)<?";
        $db->query($query_rs);
        $db->bind(1,'Student');
        $db->bind(2,'active');
        $db->bind(3,$expire_date);

        $rsstudent=$db->rowset();
        $rsstudent_total=$db->rowcount();

        if($rsstudent_total>0){
          foreach ($rsstudent as $rs_data_row){ 
            $to = $rs_data_row['username']; 
            $from = 'aaaflight@codedfacility.com'; 
            $fromName = 'AAA Flight Scheduling System'; 
            $subject = "License Expiration Reminder."; 
            
            $message = ""; 
            $message = $message."\n\nPlease Renew your license before ".$rs_data_row['cpl']." \n\nAdmimistrator\nAAA Flight Scheduling System"; 
            
            // Additional headers 
            $headers = 'From: '.$fromName.'<'.$from.'>'; 
            mail($to, $subject, $message, $headers);
          }
        }

        $query_rs = "select * FROM `user` WHERE `group`=? and `status`=? and date(fil)<?";
        $db->query($query_rs);
        $db->bind(1,'Instructor');
        $db->bind(2,'active');
        $db->bind(3,$expire_date);
        $rsinstructor=$db->rowset();
        $rsinstructor_total=$db->rowcount();

        if($rsinstructor_total>0){
          foreach ($rsinstructor as $rs_data_row){ 
            $to = $rs_data_row['username']; 
            $from = 'aaaflight@codedfacility.com'; 
            $fromName = 'AAA Flight Scheduling System'; 
            $subject = "License Expiration Reminder."; 
            
            $message = ""; 
            $message = $message."\n\nPlease Renew your license before ".$rs_data_row['fil']." \n\nAdmimistrator\nAAA Flight Scheduling System"; 
            
            // Additional headers 
            $headers = 'From: '.$fromName.'<'.$from.'>'; 
            mail($to, $subject, $message, $headers);
          }
        }


        $query_rs = "select * FROM `user` WHERE `group`=? and `status`=?  and date(mecl)<?";
        $db->query($query_rs);
        $db->bind(1,'Mechanic');
        $db->bind(2,'active');
        $db->bind(3,$expire_date);
        $rsmechanic=$db->rowset();
        $rsmechanic_total=$db->rowcount();

        if($rsmechanic_total>0){
          foreach ($rsmechanic as $rs_data_row){ 
            $to = $rs_data_row['username']; 
            $from = 'aaaflight@codedfacility.com'; 
            $fromName = 'AAA Flight Scheduling System'; 
            $subject = "License Expiration Reminder."; 
            
            $message = ""; 
            $message = $message."\n\nPlease Renew your license before ".$rs_data_row['mecl']." \n\nAdmimistrator\nAAA Flight Scheduling System"; 
            
            // Additional headers 
            $headers = 'From: '.$fromName.'<'.$from.'>'; 
            mail($to, $subject, $message, $headers);
          }
        }


    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
 # else {
 #   header("Location: ". $MM_redirectLoginFailed );
 # }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
<title><?php echo $app_title; ?></title>
</head>


<?php require_once('../template/phplink.php'); ?>

<script>
  //const Swal = require('sweetalert2');

  function ShowError(){
    Swal.fire('Log-In Error','User Name or Password is Incorrect!','info');
  }
</script>
<body background="<?php echo $app_login_background;?>">
<!--<body>-->

<div align="center" class="alert bg-gradient" style="background-color:Gainsboro;">
  <h3><strong>Welcome to <?php echo $hd1;?></strong></h3>
</div>
<br>
<div align="center">
  <img src="../images/logo.png" class="img-responsive" width="200px" >
</div>
<br><br>
<?php if (isset($_POST['username'])) {echo "<script>ShowError();</script>";} ?>
<div class="row">
<div class="col-md-4 col-sm-12"></div>

 

  <div class="card col-md-4">
    <div class="card-body">
      <div>
        <h5 class="card-title text-center pb-0 fs-4" ">Login to Your Account</h5>
        <p class="text-center small"></p>
      </div>
      <div class="col-12">
        <form method="POST" name="form1" id="form1" class="row g-3">                 
          <div class="input-group"> <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-badge"> </i></span><input type="text" class="form-control" id="username" name="username" placeholder="User Name"  value=""> </div>        
            <br>
            <div class="input-group"><span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-key"> </i></span> <input type="password" class="form-control" id="password" name="password" placeholder="Password" value=""></div> 
            <div><input class="form-check-input" type="checkbox" id="gridCheck1" onclick="showhidepassword()"> <label class="form-check-label" for="gridCheck1">Show Password</label></div>            
            <br>
            <div class="col-12" align="right">    
                <a href="forgot-password.php" class="btn btn-secondary "><i class="bi-life-preserver"></i> &nbsp;Forgot Password?<a>
                <button type="submit" class="btn btn-success btn-md btn-block"> <i class="bi bi-unlock-fill"> </i><span>&nbsp;Log In</span></button><br>
               
            </div>             
        </form>              
      </div>   
    <!-- End card mb-4-->
    </div>
    <!-- End card mb-4-->
  </div>

<div class="col-md-4 col-sm-12"></div>
          
<div class="col-md-12 footer" align="center">
  <div class="copyright" style="color:white;"> &copy; Copyright <strong><span><?php echo $app_copyright;?></span></strong>. All Rights Reserved</div>
  <div class="credits" style="color:white;"> <?php echo $app_footer;?></div>
</div>
</div>
</body>
<script>
function showhidepassword(){
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>
</html>
