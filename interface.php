<?php
//-------Формируем интерфейс приложения для браузера---------------------
echo '
<tr>
<td width=100px> Устройство:
</td>
<td width=40px><a href="/device_action.php?id='.$id.'">'. $device_name . '</a>
</td>
</tr>
</table>

<table border=1>
<tr>
<td width=100px> Tемпература
</td>
<td width=40px>' . $temperature . '°С
</td>
<td width=150px>' . $temperature_dt . '
</td>
</tr>
<tr>
<td width=100px> Реле
</td>
<td width=40px>' . $out_state . '
</td>
<td width=150px> ' . $out_state_dt . '
</td>
</tr>
<td width=100px> Поведение
</td>
<td width=150px>' . $warning . '

</tr>
</table>

<form method="post">
<button formmethod=POST name=button_on value='.$id.'>Включить реле</button>
</form>
<form>
<button formmethod=POST name=button_off value='.$id.'>Выключить реле</button>
</form>
';
//----------------------------------------------------------------------
?>