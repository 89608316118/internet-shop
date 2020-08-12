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
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `postavschik`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице

    //Удаляем, если что
    if (isset($_GET['del'])) {
      $sql = mysqli_query($link, "DELETE FROM `postavschik` WHERE `ID_Postavschik` = {$_GET['del']}");
      if ($sql) {
        echo "<p>Поставщик удален.</p>";
		$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `postavschik`"); // количество полученных строк
		$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
  ?>
  
  <?php
  if ($count!=0){
  //Получаем данные
  $sql = mysqli_query($link, 'SELECT `ID_Postavschik`, `FIO_Postavschik`, `Dolgnost`,`Stage`,
		`Name_Organisation`, `Adres`, `Telephone` FROM `postavschik`');
  print ("<p align=center><font face=verdana><b>Данные о поставщиках</b>
<table border=1 align=center width=100% cellpadding=9>
<tr bgcolor=silver align=center>
<td>id</td>
<td>ФИО поставщика</td>
<td>Должность</td>
<td>Стаж работы</td>
<td>Наименование организации</td>
<td>Адрес</td>
<td>Телефон</td>
</tr>");
  while ($result = mysqli_fetch_array($sql)) {
	  $ID_Postavschik=$result['ID_Postavschik'];
	  $FIO_Postavschik=$result['FIO_Postavschik'];
	  $Dolgnost=$result['Dolgnost'];
	  $Stage=$result['Stage'];
	  $Name_Organisation=$result['Name_Organisation'];
	  $Adres=$result['Adres'];
	  $Telephone=$result['Telephone'];
  print ("<tr>
<td>$ID_Postavschik</td>
<td>$FIO_Postavschik</td>
<td>$Dolgnost</td>
<td>$Stage</td>
<td>$Name_Organisation</td>
<td>$Adres</td>
<td>$Telephone</td>
</tr>");
  }
  echo "Количество записей: $count";
  }
  else {
	 echo("нет записей");
	  }
print ("</table>");
?>
</body>
</html>