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
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `valuta`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице

    //Удаляем запись, если что
    if (isset($_GET['del'])) {
      $sql = mysqli_query($link, "DELETE FROM `valuta` WHERE `ID_Valuta` = {$_GET['del']}");
      if ($sql) {
        echo "<p>Валюта удалена.</p>";
		$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `valuta`"); // количество полученных строк
		$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
  ?>
  
  <?php
  if ($count!=0){
  //Получаем данные
  $sql = mysqli_query($link, 'SELECT `ID_Valuta`, `Name_Valuta` FROM `valuta`');
  print ("<p align=center><font face=verdana><b>Данные о валютах</b>
<table border=1 align=left width=100% cellpadding=5>
<tr bgcolor=silver>
<td>id</td>
<td>Наименование валюты</td>
</tr>");
  while ($result = mysqli_fetch_array($sql)) {
	  $ID_Valuta=$result['ID_Valuta'];
	  $Name_Valuta=$result['Name_Valuta'];
  print ("<tr>
<td>$ID_Valuta</td>
<td>$Name_Valuta</td>
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