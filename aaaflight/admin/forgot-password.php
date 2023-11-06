<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$phu=new php_util();

$msg="";
if (isset($_POST['username'])) {

  $db=new DatabaseConnect();

  $LoginRS_query="SELECT * FROM user WHERE `username`=?";
  $db->query($LoginRS_query);
  $db->bind(1,$_POST['username']);
  $LoginRS=$db->rowsingle();
  $loginFoundUser=$db->rowcount();

  $msg=" Please make sure to Enter your Correct Email.";

  if ($loginFoundUser>0) {

    $to = $_POST['username']; 
    $from = 'aaaflight@codedfacility.com'; 
    $fromName = 'AAA Flight Scheduling System'; 
    
    $subject = "Requested Password"; 
    
    $message = "Password : ".$phu->decryptMessage($LoginRS['password']); 
    $message = $message."\n\nPlease secure your password at all times. \n\nAdmimistrator\nAAA Flight Scheduling System"; 
    
    // Additional headers 
    $headers = 'From: '.$fromName.'<'.$from.'>'; 
    
    // Send email 
    if(mail($to, $subject, $message, $headers)){ 
        $msg= 'Email has sent successfully.'; 
    }else{ 
        $msg='Email sending failed.'; 
    }

  
  }
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
<!--<body background= <?php //echo $bkname;?>>-->
<body background='..\images\bglogin.jpg'>
<div align="center" style="background-color: teal;padding: 5px 5px 5px 5px;color: white;">
  <h3><strong>Welcome to <?php echo $app_title; ?></strong></h3>
</div>
<br>
<div align="center" >
  <img src="../images/logo.png" class="img-responsive" width="200px" >
</div>

<main>
  <section class="section register min-vh-10 d-flex flex-column align-items-center justify-content-center py-6">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
          <div class="d-flex justify-content-center py-4">
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Enter your Email and we will send you your Password</h5>
                  <p class="text-center small"><?php if (strcmp($msg,"")){echo "<div class='alert alert-danger bg-danger text-light border-0 alert-dismissible fade show' role='alert' ><i class='bi-mailbox2'> </i>".$msg."</div>";} ?></p>
                </div>
                <div class="col-12">
                <form method="POST" name="form1" id="form1" class="row g-3">
                    <div class="input-group"> <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-badge"> </i></span><input type="text" class="form-control" id="username" name="username" placeholder="UserName/Email"> </div>
                    <br>
                    
                    <div class="col-12" ><button type="submit" class="btn btn-primary btn-md btn-block"> <i class="bi-mailbox2"> </i><span>&nbsp;Send</span></button>
                    <a href="log-in.php" class="btn btn-secondary btn-md btn-block"> <i class="bi bi-unlock-fill"> </i><span>&nbsp;Log-In Window</span></a><br></div><br></div>
                    <input type="hidden" id="recordID" name="recordID" value="">
                </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

</body>

</html>
