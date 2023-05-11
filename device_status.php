<?php
include("db.php");

echo "";

function add_command($link, $id) {
    if($_GET['Rele'] == '1') {
        $status = '1';
    } 
    else $status = '0';
    $date_today = date("Y-m-d H:i:s");
    $query = "INSERT device_action SET DEVICE_ID = '$id', COMMAND = '$status', DATE_TIME='$date_today'";
    $result = mysqli_query($link, $query);

}


if(isset($_GET["ID"])){ //Если запрос от устройства содержит идентификатор
$query = "SELECT * FROM device_table WHERE DEVICE_ID='".$_GET['ID']."'";
$result = mysqli_query($link, $query);
if(mysqli_num_rows($result) == 1){ //Если найдено устройство с таким ID в БД

if(isset($_GET['Rele'])) { //Если устройство передало новое состояние реле
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT OUT_STATE FROM out_state_table WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($link, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE out_state_table SET OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($link, $query);
} else { //Если данных для такого устройства нет - добавляем
$query = "INSERT out_state_table SET DEVICE_ID='".$_GET['ID']."', OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today'"; //Записать данные
$result = mysqli_query($link, $query);
}
add_command($link, $_GET["ID"]);

}


if(isset($_GET['Term'])) { //Если устройство передало новое значение температуры
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT TEMPERATURE FROM temperature_table WHERE DEVICE_ID='".$_GET['ID']."'";
$result = mysqli_query($link, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE temperature_table SET TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($link, $query);
} else { //Если данных для этого устройства нет - добавляем
$query = "INSERT temperature_table SET DEVICE_ID='".$_GET['ID']."', TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today'"; //Записать данные
$result = mysqli_query($link, $query);
}

}



//Достаём из БД текущую команду управления реле
$query = "SELECT COMMAND FROM device_action WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($link, $query);
if(mysqli_num_rows($result) !== 1){ //Если в таблице есть данные для этого устройства
$Arr = mysqli_fetch_array($result);
$Command = $Arr['COMMAND'];
}

//Отвечаем на запрос текущей командой
if($Command != -1) //Есть данные для этого устройства
{
echo "COMMAND $Command EOC";
}
else
{
echo "COMMAND ? EOC";
}
}
}

?>