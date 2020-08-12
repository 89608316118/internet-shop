<?php
	//если переменная логин установлена
	if(isset($_POST['login'])) $login = $_POST['login'];
	//если переменная логин и пароль установлена
	if (isset($_POST['password'])) $password = $_POST['password'];
	//если логин - администратор и пароль - 98765
	if (($login=="Администратор") && ($password=="98765")){
		//то осуществляется переход на сайт с ограничениями администратора
		header('location: https://localhost/internet-magasin/Administrator/main.php');
	}
	//если пароль не введен неверно
	elseif (($login=="Администратор") && ($password!="98765")) {
		//то выводим сообщение пользователю
		echo('<p align="center" style="font-size: 30px"><font color="red">Вы неверно ввели логин или пароль</p>');
	}
	//если логин - пользователь и пароль - 43210
	if (($login=="Пользователь") && ($password=="43210")){
		//то осуществляется переход на сайт с ограничениями пользователя
		header('location: https://localhost/internet-magasin/User/main.php');
	}
	//если пароль не введен неверно
	elseif (($login=="Пользователь") && ($password!="43210")) {
		//то выводим сообщение пользователю
		echo('<p align="center" style="font-size: 30px"><font color="red">Вы неверно ввели логин или пароль</p>');
	}
	//если логин - пользователь
	if ($login=="Покупатель"){
		//то осуществляется переход на сайт с ограничениями пользователя
		header('location: https://localhost/internet-magasin/Klient/main.php');
	}
?>
<html>
<head>
<meta charset="utf-8">
<title>Интернет-магазин</title>
</head>
<meta charset="utf-8">
<body>
	<form action="" method="post">
		<table border="0" cellspacing="10"
		cellpadding="10" align="center" valign="center">
		<!--Серая строка для авторизации пользователя-->
		<tr>
			<td height="50" colspan="2" bgcolor="#ccc" align="center">
			<p width="50%" style="font-size: 50px">Авторизация пользователя</p></td>
		</tr>
		<!--Выбор списка пользователей под логином-->
		<tr><td width="50%" valign="center" height="20" align="right">
		<p style="font-size: 40">Логин</p></td>
		<td width="50%" height="20" size="20" valign="center" align="left">
		<p style="font-size: 5px">
		<input type="text" name="login" list="login" style="width:100%;height:50;font-size:25"> 
		<datalist id="login">
			<option value="Администратор">
			<option value="Пользователь">
			<option value="Покупатель">
		</datalist>
		<br><br>
		</p>
		</td>
		</tr>
		<!--Ввод пароля-->
		<tr><td align="right" size="10">
		<p style="font-size: 40">Пароль</p></td>
		<td size="10" valign="center" align="left">
		<input type="password" name="password" style="width:100%;height:50;font-size:25"></td>
		</tr>
		<!--<input type="submit" value="Перейти" onclick=" location.href='https://localhost/internet-magasin/main.php'  ">
		<input type="reset" value="Очистить форму">-->
		<br>
		<br>
		<!--Кнопка перехода в перехода на сайт-->
		<tr><td align="right"><input type="submit" style="width:100%;height:50;font-size:30" value="Перейти"></td>
		<!--Кнопка очистки логина и пароля-->
		<td align="left"><input type="reset" style="width:100%;height:50;font-size:30" value="Очистить форму"></td></tr>
		<!--Кнопка выхода из авторизации-->
		<!--<tr>
			<td height="50" colspan="2" align="center">
			<input type="button" id="btnClose" value="Выход" style="width:100%;height:50;font-size:30" onclick="self.close()"</td>
		</tr>-->
		</table>
	</form>
</body>
</html>