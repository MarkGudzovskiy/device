<?php
//Настройки подключения к БД
$db_host = 'std-mysql.ist.mospolytech.ru';
$db_user = 'std_2017_iot'; //имя пользователя совпадает с именем БД
$db_password = 'Artem080303'; //пароль, указанный при создании БД
$database = 'std_2017_iot'; //имя БД, которое было указано при создании
$conn = mysqli_connect($db_host, $db_user, $db_password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "";

function add_command($link, $id) {
    if($_GET['Rele'] == '1') {
        $status = 'rele_on';
    } 
    else $status = 'rele_off';
    $date_today = date("Y-m-d H:i:s");
    $query = "INSERT device_action SET DEVICE_ID = '$id', COMMAND = '$status', DATE_TIME='$date_today'";
    $result = mysqli_query($link, $query);

}

function change_temp($link, $id) {
    if(isset($_GET['TERM'])) {
        $status = 'temp_change';
    } 
    $date_today = date("Y-m-d H:i:s");
    $query = "INSERT device_action SET DEVICE_ID = '$id', COMMAND = '$status', DATE_TIME='$date_today'";
    $result = mysqli_query($link, $query);
}

if(isset($_GET["id"])){ //Если запрос от устройства содержит идентификатор
$query = "SELECT * FROM device_table WHERE DEVICE_ID='".$_GET['id']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 1){ //Если найдено устройство с таким ID в БД

if(isset($_GET['Rele'])) { //Если устройство передало новое состояние реле
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT OUT_STATE FROM out_state_table WHERE DEVICE_ID = '".$_GET['id']."'";
$result = mysqli_query($conn, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE out_state_table SET OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
} else { //Если данных для такого устройства нет - добавляем
$query = "INSERT out_state_table SET DEVICE_ID='".$_GET['ID']."', OUT_STATE='".$_GET['Rele']."', DATE_TIME='$date_today'"; //Записать данные
$result = mysqli_query($conn, $query);
}
add_command($conn, $_GET["id"]);

}


if(isset($_GET['Term'])) { //Если устройство передало новое значение температуры
//проверяем есть ли в БД предыдущее значение этого параметра
$query = "SELECT TEMPERATURE FROM temperature_table WHERE DEVICE_ID='".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
$date_today = date("Y-m-d H:i:s"); //текущее время
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства - обновляем
$query = "UPDATE temperature_table SET TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today' WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
} else { //Если данных для этого устройства нет - добавляем
$query = "INSERT temperature_table SET DEVICE_ID='".$_GET['ID']."', TEMPERATURE='".$_GET['Term']."', DATE_TIME='$date_today'"; //Записать данные
$result = mysqli_query($conn, $query);
}
change_temp($conn, $_GET["id"]);
}



//Достаём из БД текущую команду управления реле
$query = "SELECT COMMAND FROM command_table WHERE DEVICE_ID = '".$_GET['ID']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 1){ //Если в таблице есть данные для этого устройства
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