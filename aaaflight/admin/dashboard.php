<?php 
$query_rs = "select * FROM `flight_sched` WHERE flight_date=?";
$db->query($query_rs);
$db->bind(1,date('Y-m-d'));
$rs_fs=$db->rowset();
$rs_fs_total=$db->rowcount();

$query_rs = "select * FROM `notif` WHERE `read`=?";
$db->query($query_rs);
$db->bind(1,'no');
$rs_notif=$db->rowset();
$rs_notif_total=$db->rowcount();

$query_rs = "select * FROM `notif`";
$db->query($query_rs);
$rs_notifall=$db->rowset();
$rs_notifall_total=$db->rowcount();

$query_rs = "select * FROM `inspection_sched` WHERE effective_date=? AND inspection_type='PRE-FLIGHT'";
$db->query($query_rs);
$db->bind(1,date('Y-m-d'));
$rs_is=$db->rowset();
$rs_is_total=$db->rowcount();

$query_rs = "SELECT u.* FROM `user` u WHERE `group`='Student' ORDER BY u.`lastname`, firstname, middlename";
$db->query($query_rs);
$rs_student=$db->rowset();
$rs_student_total=$db->rowcount();

$query_rs = "SELECT u.* FROM `user` u WHERE `group`='Instructor' ORDER BY u.`lastname`, firstname, middlename";
$db->query($query_rs);
$rs_instructor=$db->rowset();
$rs_instructor_total=$db->rowcount();

$query_rs = "select * FROM `lup_aircraft`";
$db->query($query_rs);
$rs_aircraft=$db->rowset();
$rs_aircraft_total=$db->rowcount();
?>
<?php 
if (isset($_GET['msg_info'])){
    if ((strcmp(htmlentities($_GET['msg_info']),""))) {?>
        <div class="alert alert-info alert-dismissible fade show" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> <i class="bi bi-info-circle me-1"></i> <?php echo htmlentities($_GET['msg_info']); ?> </div>
<?php }} ?>

<style>
  @font-face{
  src: url(Orbitron.ttf);
  font-family: Orbitron;
  }

.clock {

    color: black;
    font-size: 60px;
    font-family: Orbitron;
    letter-spacing: 7px;
}

  /* CSS for responsive iframe */
  .iframe-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
  }

  .iframe-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
</style>

<script>
  function showTime(){
    var date = new Date();
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    var session = "AM";
    
    if(h == 0){
        h = 12;
    }
    
    if(h > 12){
        h = h - 12;
        session = "PM";
    }
    
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    
    var time = h + ":" + m + ":" + s + " " + session;
    document.getElementById("MyClockDisplay").innerText = time;
    document.getElementById("MyClockDisplay").textContent = time;
    
    setTimeout(showTime, 1000);
    
}

showTime();
</script>

<div class="col-xxl-3 col-xl-12">
    <div class="card info-card blue-card">
        <div class="filter">
           
           
        </div>
        <div class="card-body">
            <h5 class="card-title">Flight Schedule <span>| <?php echo date('Y-m-d')?></span></h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="ri ri-flight-takeoff-fill"></i></div>
                <div class="ps-3">
                    <h6><?php echo $rs_fs_total;?></h6>
                    <span class="text-danger small pt-1 fw-bold"><?php echo $rs_is_total;?></span> <span class="text-muted small pt-2 ps-1">Aircraft for PRE-FLIGHT Inspection</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-3 col-xl-12">
    <div class="card info-card red-card">
        <div class="filter">
        </div>
        <div class="card-body">
            <h5 class="card-title">Notification <span>| <?php echo date('Y-m-d')?></span></h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bx bx-notification"></i></div>
                <div class="ps-3">
                    <h6><?php echo $rs_notifall_total;?></h6>
                    <span class="text-danger small pt-1 fw-bold"><?php echo $rs_notif_total;?></span> <span class="text-muted small pt-2 ps-1">unread notification</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-3 col-xl-12">
    <div class="card info-card green-card">
       
        <div class="card-body">
            <h5 class="card-title">Instructor <span>| </span></h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="ri ri-user-2-fill"></i></div>
                <div class="ps-3">
                    <h6><?php echo $rs_instructor_total;?></h6>
                    <span class="text-danger small pt-1 fw-bold">100%</span> <span class="text-muted small pt-2 ps-1">well trained instructor</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-3 col-xl-12">
    <div class="card info-card orange-card">
        
    <?php 
                            
            $apiKey = "e8063c2154424d8351103d6881a6d090";
            $lat = "15.326093";
            $lon = "119.968928";
            $googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=".$lon."&lang=en&units=metric&APPID=" .$apiKey;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $data = json_decode($response);
            $currentTime = time();
            ?>
   
        <div class="card-body">
            <h5 class="card-title">Weather <span>| IBA, ZAMBALES</span></h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <img class="weather-icon" src="https://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"></div>
                <div class="ps-3">
                    <span class="text-danger small pt-1 fw-bold"> Temp: <?php echo floor($data->main->temp); ?>°C &nbsp; </span>*
                    <span class="text-success small pt-1 fw-bold"> Humidity:</strong> <?php echo $data->main->humidity; ?> %</span>* 
                    <span class="text-info small pt-1 fw-bold"> Wind:</strong> <?php echo $data->wind->speed; ?>km/h</span> *
                    <span class="text-primary small pt-1 fw-bold"> Wind Degree:</strong> <?php echo $data->wind->deg; ?>°</span> *
                    <span class="text-danger small pt-1 fw-bold"> Wind Gusts:</strong> <?php echo $data->wind->gust; ?>km/h</span> 
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>

<div class="col-xxl-12 col-xl-12">
    <div class="card card ">
        <div class="card-header"><h5 class="card-title"><strong>Weather Map</strong></h5></div>
            <!--<div class="card-body">-->

            <!--<iframe width="1500" height="800" src="https://embed.windy.com/embed2.html?lat=15.326093&lon=119.968928&detailLat=15.326093&detailLon=119.968928&width=1500&height=800&zoom=20&level=surface&overlay=wind&product=ecmwf&menu=&message=true&marker=true&calendar=now&pressure=true&type=map&location=coordinates&detail=true&metricWind=default&metricTemp=default&radarRange=-1" frameborder="0"></iframe>-->
            <!--</div>-->
<div class="card-body">
  <div class="iframe-container">
    <iframe src="https://embed.windy.com/embed2.html?lat=15.326093&lon=119.968928&detailLat=15.326093&detailLon=119.968928&width=1500&height=800&zoom=20&level=surface&overlay=wind&product=ecmwf&menu=&message=true&marker=true&calendar=now&pressure=true&type=map&location=coordinates&detail=true&metricWind=default&metricTemp=default&radarRange=-1" frameborder="0"></iframe>
  </div>
</div>

    </div>
    <div class="card-footer"></div>
    
</div>




</div>
