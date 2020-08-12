<!doctype html>
<html lang="ru">
<head>
  <title>Админ-панель</title>
</head>
<body>
  <?php
    $host = 'localhost';  // Хост локальный
    $user = 'mysql';    // Имя созданного вами пользователя
    $pass = 'mysql'; // Установленный вами пароль пользователю
    $db_name = 'internet-magasin';   // Имя базы данных
    $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой

    // Если соединение установить не удалось, то ошибка
    if (!$link) {
      echo 'Нет соединения с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `sklad_tovarov`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
	
	 //Если передана переменная red, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red'])) {
      $sql = mysqli_query($link, "SELECT `ID_Klient`, `FIO_Klienta`, `Adres`, `Name_Tovar`, `Date_Pokupki`, 
`Kolvo_Tovarov`, `Cost_Tovara`, `Valuta` FROM pokupka_tovarov WHERE `ID_Klient`={$_GET['red']}");
      $pokupka_tovarov = mysqli_fetch_array($sql);
	  $Kolvo_Tovarov=$pokupka_tovarov['Kolvo_Tovarov'];
	  $sql_sklad_tovarov = mysqli_query($link, "SELECT `ID_Tovar`, `Name_Tovar`, `Cathegory_Tovara`, `Name_Organisation`, `FIO_Postavschik`,
	  `Date_Postavki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`, `Opisanie_Tovara` FROM `sklad_tovarov`");
	  //$sklad_tovarov=mysqli_fetch_array($sql_sklad_tovarov);
	//if ($sql_sklad_tovarov){
	$sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET pokupka_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		sklad_tovarov.Kolvo_Tovarov=sklad_tovarov.Kolvo_Tovarov + pokupka_tovarov.Kolvo_Tovarov 
		WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' AND pokupka_tovarov.ID_Klient='{$_GET['red']}' AND
		sklad_tovarov.Kolvo_Tovarov>=pokupka_tovarov.Kolvo_Tovarov");
    //}
	}
  ?>
  
<?php
//если количество записей не равно 0
if ($count!=0){
//Получаем данные для полей
$sql = mysqli_query($link, 'SELECT `ID_Tovar`, `Name_Tovar`, `Cathegory_Tovara`, `Name_Organisation`, `FIO_Postavschik`,
		`Date_Postavki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`, `Opisanie_Tovara` FROM `sklad_tovarov`');
//Строка названия полей
print ("<p align=center><font face=verdana><b>Список товаров на складе</b>
<table border=1 align=center width=100% cellpadding=9>
<tr bgcolor=silver align=center>
<td>id</td>
<td>Наименование товара</td>
<td>Категория товара</td>
<td>Наименование организации</td>
<td>ФИО поставщика</td>
<td>Дата поставки</td>
<td>Количество товаров</td>
<td>Цена товара</td>
<td>Валюта</td>
<td>Описание товара</td>
</tr>");
//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Склад товаров"
  while ($result = mysqli_fetch_array($sql)) {
	  //присваивание переменных для каждых полей (столбцов) базы данных
	  $ID_Tovar=$result['ID_Tovar'];
	  $Name_Tovar=$result['Name_Tovar'];
	  $Cathegory_Tovara=$result['Cathegory_Tovara'];
	  $Name_Organisation=$result['Name_Organisation'];
	  $FIO_Postavschik=$result['FIO_Postavschik'];
	  $Date_Postavki=$result['Date_Postavki'];
	  $Kolvo_Tovarov=$result['Kolvo_Tovarov'];
	  $Cost_Tovara=$result['Cost_Tovara'];
	  $Valuta=$result['Valuta'];
	  $Opisanie_Tovara=$result['Opisanie_Tovara'];
//вывод значения для каждых полей БД в каждой строке
print ("<tr>
<td>$ID_Tovar</td>
<td>$Name_Tovar</td>
<td>$Cathegory_Tovara</td>
<td>$Name_Organisation</td>
<td>$FIO_Postavschik</td>
<td>$Date_Postavki</td>
<td>$Kolvo_Tovarov</td>
<td>$Cost_Tovara</td>
<td>$Valuta</td>
<td>$Opisanie_Tovara</td>
</tr>");
  } //конец цикла while
  echo "Количество записей: $count";
  } //конец условия
  else { //иначе если количество записей равно 0
	 echo("нет записей"); //то нет записей
	  } //конец оператора "иначе"
print ("</table>"); //конец таблицы
?>
<form>
<table>
<td>Дополнительные данные о:</td>
<td><p><a href="cathegory.php">категории товаров</a>,</p></td>
<td><p><a href="val.php">валюте</a>.</p></td>
</table>
</form>
</body>
</html>