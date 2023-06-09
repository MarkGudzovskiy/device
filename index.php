<?php

include("db.php");

function update_rele($link, $id) {
    if(isset($_POST['button_on'])) {
        $status = 1;
    } 
    else $status = 0;
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE out_state_table SET OUT_STATE = '$status', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'";
    $result = mysqli_query($link, $query);
    if (mysqli_affected_rows($link) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT out_state_table SET DEVICE_ID = '$id', OUT_STATE='$status', DATE_TIME='$date_today'";
        $result = mysqli_query($link, $query);
    }
}
function add_command($link, $id) {
    if(isset($_POST['button_on'])) {
        $status = '1';
    } 
    else $status = '0';
    $date_today = date("Y-m-d H:i:s");
    $query = "INSERT device_action SET DEVICE_ID = '$id', COMMAND = '$status', DATE_TIME='$date_today'";
    $result = mysqli_query($link, $query);
}
if (isset($_POST['button_on'])) {
    $id = $_POST['button_on'];
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE device_action SET COMMAND='1', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'";
    $result = mysqli_query($link, $query);
    if (mysqli_affected_rows($link) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT device_action SET DEVICE_ID='$id', COMMAND='1', DATE_TIME='$date_today'";
        $result = mysqli_query($link, $query);
    }
    update_rele($link, $id);
    add_command($link, $id);
}

if (isset($_POST['button_off'])) {
    $id = $_POST['button_off'];
    $date_today = date("Y-m-d H:i:s");
    $query = "UPDATE device_action SET COMMAND='0', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'";
    $result = mysqli_query($link, $query);
    if (mysqli_affected_rows($link) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
    { //вставляем в таблицу строчку с данными о команде для устройства
        $query = "INSERT device_action SET DEVICE_ID='$id', COMMAND='0', DATE_TIME='$date_today'";
        $result = mysqli_query($link, $query);
    }
    update_rele($link, $id);
    add_command($link, $id);
}

if (isset($_POST['add'])) {
    $login = $_POST["name"];
    $password = password_hash('$password', PASSWORD_DEFAULT);
    $query = "INSERT device_table SET DEVICE_LOGIN = '$login', DEVICE_PASSWORD='$password', NAME='$login'";
    $result = mysqli_query($link, $query);
}
//-----------------------------------------------------------------------

echo '
<!DOCTYPE HTML>
<html id="App_interface">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MyApp</title>
<script src="UpdateScript.js"> </script>
</head>
<body>
<table> ';

$query = "SELECT COUNT(*) AS 'total' FROM device_table";
$result = mysqli_query($link, $query);
$total = mysqli_fetch_assoc($result);
for($i = 0; $i < $total['total']; $i++) {

    $id = $i + 1;
//-----------------Получаем из БД все данные об устройстве-------------------
$query = "SELECT * FROM device_table WHERE DEVICE_ID = '$id'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о имени для этого устройства
    $Arr = mysqli_fetch_array($result);
    $device_name = $Arr['NAME'];
} else { //Если в БД нет данных о имени для этого устройства
    $device_name = '?';
}

$query = "SELECT * FROM temperature_table WHERE DEVICE_ID = '$id'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о температуре для этого устройства
    $Arr = mysqli_fetch_array($result);
    $temperature = $Arr['TEMPERATURE'];
    $temperature_dt = $Arr['DATE_TIME'];
} else { //Если в БД нет данных о температуре для этого устройства
    $temperature = '?';
    $temperature_dt = '?';
}

$query = "SELECT * FROM out_state_table WHERE DEVICE_ID = '$id'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о реле для этого устройства
    $Arr = mysqli_fetch_array($result);
    $out_state = $Arr['OUT_STATE'];
    $out_state_dt = $Arr['DATE_TIME'];
} else { //Если в БД нет данных о реле для этого устройства
    $out_state = '?';
    $out_state_dt = '?';
}

$query = "SELECT * FROM out_state_table WHERE DEVICE_ID = '$id'";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о реле для этого устройства
    $Arr = mysqli_fetch_array($result);
    $action = $Arr['flag'];
} else { //Если в БД нет данных о реле для этого устройства
    $action = '?';  
}
if($action ==  1){
    $warning = 'Частое обращение';
} else {$warning = '';}
//----------------------------------------------------------------------------------------



include("interface.php");
}

echo '
<form name="add" method="post">
    <h2>Добавить девайс</h2>
    <p>Введите название:</p>
  <p><input type="text" name="name" size="40"></p>
  <p>Введите пароль:</p>
  <p><input type="password" name="password" maxlength="25" size="40" name="password"></p>
  <button formmethod=POST name=add value="1">Добавить</button> 
</form>
</body>
</html>';
