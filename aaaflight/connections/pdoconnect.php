<?php 
       
        if (!isset($_SESSION)) {
          session_start();
        }
		
		date_default_timezone_set("Asia/Taipei");

		$hd0="";
        $hd1="Flight Scheduling System for AAA";
        $hd2="Iba, Zambales";
        $hd3="<i class='bi-mailbox2'></i> aaaflight@gmail.com | <i class='bi-telephone-fill'></i> (047) 123-4567) | (047) 321-7654";
		$hd4="<i class='ri-chrome-fill'></i> http://www.aaaflight.com";
        $hd5="";

		$app_title="AAA Flight";
        $app_sub_title=$hd1;
		$app_address_contact=$hd2.' '.$hd3;
        $app_footer="Developed by Jonathan Abiva, Lesly Ann, Roselle Dela Cruz, Patrick Mark Labrador, Kaila Mae Macadaan . ver. 1.0.0.0";
        $app_copyright=date('Y');
        $app_header_footer_background="slategray";
        $app_home_image="../images/front.png";
		$app_user_image_default="../images/logo.png";
		$app_login_background="../images/front.png";
		$tagline="your database driven template";
		$font_size_print="12px";

		$notifacation=true;
		error_reporting(E_ALL);

		require_once('../lib/phplib/phpdotenv/autoload.php');
		//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
		$dotenv = Dotenv\Dotenv::createImmutable('../../aaaflight_env/');
		$dotenv->safeLoad();


		class DatabaseConnect{

			private $host      = null;
			private $user      = null;
			private $pass      = null;
			private $dbname    = null;
			private $port      = null;

			

			private $dbh;
			private $error;
		
			private $stmt;
		
			public function __construct(){
				
				$this->host      = $_ENV['host'];
				$this->user      = $_ENV['user'];
				$this->pass      = $_ENV['pass'];
				$this->dbname    = $_ENV['dbname'];
				$this->port      = $_ENV['port'];

					// Set DSN
					$dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname.';port='.$this->port.';charset=utf8';
					// Set options
					$options = array(
						PDO::ATTR_PERSISTENT    => true,
						PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
					);
					// Create a new PDO instanace
					try{
						$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
					}
					// Catch any errors
					catch(PDOException $e){
						echo '<span style="color:red;">Cannot connect to your database server or the system is temporarily down.</span>';
					}
				}
			
			public function query($query){
					$this->stmt = $this->dbh->prepare($query);
				}
					
			public function execute(){
					return $this->stmt->execute();
				}
			
			public function bind($param, $value, $type = null){
				if (is_null($type)) {
					switch (true) {
						case is_int($value):
							$type = PDO::PARAM_INT;
							break;
						case is_bool($value):
							$type = PDO::PARAM_BOOL;
							break;
						case is_null($value):
							$type = PDO::PARAM_NULL;
							break;
						default:
							$type = PDO::PARAM_STR;
					}
				}
				$this->stmt->bindValue($param, $value, $type);
				}

			public function rowset(){
					$this->execute();
					return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
				}
			
			public function rowsingle(){
					$this->execute();
					return $this->stmt->fetch(PDO::FETCH_ASSOC);
				}
			
			public function rowcount(){
					return $this->stmt->rowCount();
				}
			
			public function lastinsertid(){
					return $this->dbh->lastInsertId();
				}
			
			
			public function begintransaction(){
					return $this->dbh->beginTransaction();
				}
			
			public function endtransaction(){
					return $this->dbh->commit();
				}
			
			public function canceltransaction(){
					return $this->dbh->rollBack();
				}
			
			public function debugdumpparams(){
					return $this->stmt->debugDumpParams();
				}
			
			public function close(){
				 try{
					$this->dbh=null;
				}  catch(PDOException $e){
						echo 'Cannot close your database connection.';
					}
				}
		}



class php_util{
	
    public function get_time_interval($starttime,$endtime,$duration,$selected_date)
    	{
    		//$starttime = '9:00';  // your start time
    		//$endtime = '21:00';  // End time
    		//$duration = '30';  // split by 30 mins
    
		if (date('Y-m-d')==$selected_date)
		{

			$m=date('i');
			if ($m<=10){
				$m='10';
				$starttime=date('H:'.$m);
			}elseif ($m<=20){
				$m='20';
				$starttime=date('H:'.$m);
			}
			elseif ($m<=30){
				$m='30';
				$starttime=date('H:'.$m);
			}elseif ($m<=40){
				$m='40';
				$starttime=date('H:'.$m);
			}elseif ($m<=50){
				$m='50';
				$starttime=date('H:'.$m);
			}elseif ($m<=59){
				$m='00';
				$starttime=date('H:'.$m);
			}
		}
		
    		$array_of_time = array ();
    		$start_time    = strtotime ($starttime); //change to strtotime
    		$end_time      = strtotime ($endtime); //change to strtotime
    
    		$add_mins  = $duration * 60;
    
    		while ($start_time <= $end_time) // loop between time
    		{
    			$sql_string="SELECT * FROM flight_sched  WHERE DATE(flight_date)=? AND TIME_FORMAT(time_departure,'%H:%i')=?";
    			$temp_db=new DatabaseConnect();
    			$temp_db->query($sql_string);
    			$temp_db->bind(1,$selected_date);
    			$temp_db->bind(2,date ("H:i", $start_time));
    			$temp_rs=$temp_db->rowsingle();
    			$found=$temp_db->rowcount();
    			$temp_db->close();
    	
    			if ($found==0){
    			$array_of_time[] = date ("h:i:s A", $start_time);
    			}  
    			$start_time += $add_mins; // to check endtime
    		} 
    		return $array_of_time;
    	}
    	
	public function get_survival_equipment($str_lup_aircraft_id, $str_emergency_radio) { 
        $sql_string="SELECT * FROM lup_aircraft_emergency_radio  WHERE lup_aircraft_id=? AND emergency_radio=?";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$str_lup_aircraft_id);
		$temp_db->bind(2,$str_emergency_radio);
  		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found==1)
  			{return 1;}
  		else
  			{return 0;}
    } 

	public function get_flight_sched_aircraft($str_flight_sched_id, $str_lup_aircraft_id) { 
        $sql_string="SELECT * FROM flight_sched_passenger  WHERE flight_sched_id=? AND lup_aircraft_id=? LIMIT 1";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$str_flight_sched_id);
		$temp_db->bind(2,$str_lup_aircraft_id);
		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found==1)
  			{return 1;}
  		else
  			{return 0;}
    } 

    public function get_aircraft_id($str_flight_sched_id) { 
            $sql_string="SELECT a.* FROM flight_sched_passenger f INNER JOIN lup_aircraft a ON a.lup_aircraft_id=f.lup_aircraft_id WHERE f.flight_sched_id=? LIMIT 1";
    
    		$temp_db=new DatabaseConnect();
    		$temp_db->query($sql_string);
    		$temp_db->bind(1,$str_flight_sched_id);
    		$temp_rs=$temp_db->rowsingle();
      		$found=$temp_db->rowcount();
      		$temp_db->close();
    
      		if ($found==1)
      			{return $temp_rs['aircraft_id'];}
      		else
      			{return $str_flight_sched_id.' '.$str_lup_aircraft_id;}
        } 


	public function get_flight_sched_user($flight_sched_id, $user_id) { 
        $sql_string="SELECT * FROM flight_sched_passenger  WHERE user_id=? AND flight_sched_id=?";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$user_id);
		$temp_db->bind(2,$flight_sched_id);
  		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found==1)
  			{return 1;}
  		else
  			{return 0;}
    } 

	public function get_flight_sched_aircraft_archive($str_flight_sched_id, $str_lup_aircraft_id) { 
        $sql_string="SELECT * FROM flight_sched_passenger_archive  WHERE flight_sched_id=? AND lup_aircraft_id=? LIMIT 1";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$str_flight_sched_id);
		$temp_db->bind(2,$str_lup_aircraft_id);
		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found==1)
  			{return 1;}
  		else
  			{return 0;}
    } 

	public function get_flight_sched_user_archive($flight_sched_id, $user_id) { 
        $sql_string="SELECT * FROM flight_sched_passenger_archive  WHERE user_id=? AND flight_sched_id=?";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$user_id);
		$temp_db->bind(2,$flight_sched_id);
  		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found==1)
  			{return 1;}
  		else
  			{return 0;}
    } 

	public function get_emergency_radio($str_lup_aircraft_id, $str_survival_equipment) { 
        $sql_string="SELECT * FROM lup_aircraft_survival_equipment  WHERE lup_aircraft_id=? AND survival_equipment=?";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$str_lup_aircraft_id);
		$temp_db->bind(2,$str_survival_equipment);
  		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found==1)
  			{return 1;}
  		else
  			{return 0;}
    } 

    public function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) { 
        (is_dir("$dir/$file") && !is_link($dir)) ? delTree("$dir/$file") : unlink("$dir/$file"); 
        } 
        return rmdir($dir); 
    } 
    
	public function encryptMessage($msgToEncrypt){

		$paddedMessage = sodium_pad($msgToEncrypt, $_ENV['blocksize']);
		$encryptedMessage = sodium_crypto_secretbox($paddedMessage, $_ENV['nonce'], $_ENV['key']);
		$encryptedMessage=base64_encode($encryptedMessage);
		return $encryptedMessage;
		#return 'key='.$key.' / nonce='.$nonce.' / blocksize='.$blockSize;
	}

	public function decryptMessage($msgToDecrypt){
		
		$encryptedMessage=base64_decode($msgToDecrypt);
		$decryptedPaddedMessage = sodium_crypto_secretbox_open($encryptedMessage, $_ENV['nonce'], $_ENV['key']);
		$decryptedMessage = sodium_unpad($decryptedPaddedMessage, $_ENV['blocksize']);
		return $decryptedMessage;
	}

	public function found_group($group,$menu_id){
	$sql_string="select r.id from `user_restriction` as `r`  where r.`group`=? and r.menu_id=?";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,$group);
		$temp_db->bind(2,$menu_id);
  		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found>0)
  			{return 1;}
  		else
  			{return 0;}
	}

	public function get_menu_id($filename){
	$sql_string="select u.id,u.name from user_menu u where u.`href` LIKE ?";

		$temp_db=new DatabaseConnect();
		$temp_db->query($sql_string);
		$temp_db->bind(1,"%".$filename);
  		$temp_rs=$temp_db->rowsingle();
  		$found=$temp_db->rowcount();
  		$temp_db->close();

  		if ($found>0)
  			{	$_SESSION['title']=$temp_rs['name'];
  				return $temp_rs['id'];}
  		else
  			{return 0;}
	}

	public function get_designation_restriction($designation){
		$sql_string="select * from user WHERE designation=?";
	
			$temp_db=new DatabaseConnect();
			$temp_db->query($sql_string);
			$temp_db->bind(1,$designation);
			  $temp_rs=$temp_db->rowsingle();
			  $found=$temp_db->rowcount();
			  $temp_db->close();
	
			  if ($found>0)
				  {	return 0;}
			  else
				  {return 1;}
		}

	public function get_active_menu($session_menu_name, $click_menu_name){
		if (!(strcmp($session_menu_name, $click_menu_name))){
			return 'active';
		}
	}

	public function find_restriction($group,$menu_id){
	  $temp_db=new DatabaseConnect();
      $query_check = "SELECT * FROM user_restriction WHERE `group`=? and menu_id=?";  
      $temp_db->query($query_check);
      $temp_db->bind(1,$group);
      $temp_db->bind(2,$menu_id);
      $rscheck = $temp_db->rowsingle();
      $totalRows_rscheck = $temp_db->rowcount();
      $temp_db->close();
      if ($totalRows_rscheck>0)
      	return "checked";
  	}
	public function wordlimit($source,$maxlength){
	    if (strlen($source)>$maxlength){
	        $stringCut=substr($source,0,$maxlength);
	     	$source=substr($stringCut,0,strrpos($stringCut, ' '));
	     	if (empty($source)){
	     	$source=$stringCut;	
	     	}
	    }
	    
	    return $source;  
	    //usage: 
	    //$objVar=new php_util();
	    //$o=$objVar->wordlimit("any_string_that_you_want_to_limit",20);
		//echo $o;
	}

	public function formula_reader($result_variable,$formula)
	{
		// convert the string $result_variable to a global variable
		global ${$result_variable};
		// adds $ symbol in front of $result_variable and
		// = after $result_variable so if for example $result_variable contains "f"  
		//then it will become $f=
		$v="\$".$result_variable."=";
		// concatenate $f= with $formula if for example $formula is equal to 
		// "($present-$previous)*$kwh;" then it becomes  
		// $f="($present-$previous)*$kwh;"
		eval($v.$formula);
	}
	//usage: 
	//$var1=5;
	//$var2=7;
	//$objVar=new php_util();
	//$objVar->formula_reader("var","$var1+$var2;");
	//echo $var;

	function recursiveRemoveDirectory($directory)
	{
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) { 
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}
	
	
function numberTowords($num)
{ 
$ones = array( 
	0 => "", 
	1 => "one", 
	2 => "two", 
	3 => "three", 
	4 => "four", 
	5 => "five", 
	6 => "six", 
	7 => "seven", 
	8 => "eight", 
	9 => "nine", 
	10 => "ten", 
	11 => "eleven", 
	12 => "twelve", 
	13 => "thirteen", 
	14 => "fourteen", 
	15 => "fifteen", 
	16 => "sixteen", 
	17 => "seventeen", 
	18 => "eighteen", 
	19 => "nineteen" 
); 
$tens = array( 
	1 => "ten",
	2 => "twenty", 
	3 => "thirty", 
	4 => "forty", 
	5 => "fifty", 
	6 => "sixty", 
	7 => "seventy", 
	8 => "eighty", 
	9 => "ninety" 
); 
$hundreds = array( 
	"hundred", 
	"thousand", 
	"million", 
	"billion", 
	"trillion", 
	"quadrillion" 
); //limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){ 
	if($i < 20){ 
		$rettxt .= $ones[$i]; 
	}elseif($i < 100){ 
		$rettxt .= $tens[substr($i,0,1)]; 
		$rettxt .= " ".$ones[substr($i,1,1)]; 
	}else{ 
		$rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
		$rettxt .= " ".$tens[substr($i,1,1)]; 
		$rettxt .= " ".$ones[substr($i,2,1)]; 
	} 
	if($key > 0){ 
		$rettxt .= " ".$hundreds[$key]." "; 
	} 
} 

if($decnum > 0){ 
	$rettxt .= " and "; 
	if($decnum < 20){ 
		$rettxt .= $ones[$decnum]; 
	}elseif($decnum < 100){ 
		$rettxt .= $tens[substr($decnum,0,1)]; 
		$rettxt .= " ".$ones[substr($decnum,1,1)]; 
	} 
} 
return $rettxt; 
} 
 

}

