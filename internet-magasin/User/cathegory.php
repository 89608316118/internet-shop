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
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `cathegory_tovarov`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице

    //Удаляем запись, если что
    if (isset($_GET['del'])) {
      $sql = mysqli_query($link, "DELETE FROM `cathegory_tovarov` WHERE `ID_Cathegory` = {$_GET['del']}");
      if ($sql) {
        echo "<p>Категория товара удален.</p>";
		$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `cathegory_tovarov`"); // количество полученных строк
		$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
  ?>
  
  <?php
  if ($count!=0){
  //Получаем данные
  $sql = mysqli_query($link, 'SELECT `ID_Cathegory`, `Name_Cathegory` FROM `cathegory_tovarov`');
  print ("<p align=center><font face=verdana><b>Данные о категории товаров</b>
<table border=1 align=left width=100% cellpadding=5>
<tr bgcolor=silver>
<td>id</td>
<td>Наименование категории товара</td>
</tr>");
  while ($result = mysqli_fetch_array($sql)) {
	  $ID_Cathegory=$result['ID_Cathegory'];
	  $Name_Cathegory=$result['Name_Cathegory'];
  print ("<tr>
<td>$ID_Cathegory</td>
<td>$Name_Cathegory</td>
</tr>");
  }
  echo "Количество записей: $count";
  }
  else {
	  echo("нет записей");
	  }
print ("</table>");
?>
<form>
<p><a href="sklad_tovarov.php">Назад к складу товаров</a></p>
</form>
</body>
</html>