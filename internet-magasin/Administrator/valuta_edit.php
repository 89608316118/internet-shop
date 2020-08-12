<!doctype html>
<html lang="ru">
<head>
  <title>Админ-панель</title>
</head>
<body>
  <?php
    $host = 'localhost';  // Хост, у нас все локально
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
	
    //Если переменная Name_Valuta передана
    if (isset($_POST["Name_Valuta"])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red'])) {
        $sql = mysqli_query($link, "UPDATE `valuta` SET `Name_Valuta` = '{$_POST['Name_Valuta']}' WHERE `ID_Valuta`={$_GET['red']}");
      } else {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "INSERT INTO `valuta` (`Name_Valuta`) VALUES ('{$_POST['Name_Valuta']}')");
      }

      //Если вставка прошла успешно
      if ($sql) {
		  $rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `valuta`"); // количество полученных строк
		  $count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
		  echo '<p>Успешно!</p>';
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }

    //Если передана переменная red, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red'])) {
      $sql = mysqli_query($link, "SELECT `ID_Valuta`, `Name_Valuta` FROM `valuta` WHERE `ID_Valuta`={$_GET['red']}");
      $valuta = mysqli_fetch_array($sql);
    }
  ?>
  
  <form action="" method="post">
    <table>
      <tr>
        <td>Наименование валюты:</td>
        <td><input type="text" name="Name_Valuta" value="<?= isset($_GET['red']) ? $valuta['Name_Valuta'] : ''; ?>"></td>
      </tr>
      <tr>
        <td>
		<input type="submit" value="Подтвердить">
		</td>
		<td><button><a href="valuta.php">Назад</a></button></td>
      </tr>
    </table>
	<?php require('val.php'); ?>
  </form>
</body>
</html>