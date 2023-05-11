<?php

include("db.php");

//------Проверяем данные, полученные от пользователя---------------------
echo '
<!DOCTYPE HTML>
<html id="App_interface">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MyApp</title>
<script src="UpdateScript.js"> </script>
</head>
<body>
<table style="text-align: center"> ';
if(isset($_GET["id"])){ //Если запрос от устройства содержит идентификатор
    $query = "SELECT * FROM device_action WHERE DEVICE_ID='".$_GET['id']."'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($result)) {
        echo '
<tr>
<td></td>
<td>DEVICE_ID</td>
<td>COMMAND</td>
<td>DATE</td>
</tr>
        <tr>
        <td width=100px> История действий
        </td>
        <td width=40px>' . $row['DEVICE_ID'] . '
        </td>
        <td width=150px>' . $row['COMMAND'] . '
        </td>
        <td width=150px>' . $row['DATE_TIME'] . '
        </td>
        </tr>
        ';
    }
}


echo '
</body>
</html>';