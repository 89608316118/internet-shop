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
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `postavschik`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
	
    //Если переменная передана
        if (isset($_POST["Name_Organisation"])){
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red'])) {
        $sql = mysqli_query($link, "UPDATE postavschik SET `FIO_Postavschik` = '{$_POST['FIO_Postavschik']}',
		`Dolgnost` = '{$_POST['Dolgnost']}', `Stage` = '{$_POST['Stage']}', `Name_Organisation` = '{$_POST['Name_Organisation']}', 
		`Adres` = '{$_POST['Adres']}', `Telephone` = '{$_POST['Telephone']}' WHERE `ID_Postavschik`={$_GET['red']}");
      } else {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "INSERT INTO `postavschik` (`FIO_Postavschik`, `Dolgnost`,`Stage`,
		`Name_Organisation`, `Adres`, `Telephone`) VALUES ('{$_POST['FIO_Postavschik']}',
		'{$_POST['Dolgnost']}', '{$_POST['Stage']}', '{$_POST['Name_Organisation']}', 
		'{$_POST['Adres']}', '{$_POST['Telephone']}')");
      }

      //Если вставка прошла успешно
      if ($sql) {
		  $rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `postavschik`"); // количество полученных строк
		  $count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
		  echo '<p>Успешно!</p>'; 
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }

    //Если передана переменная red, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red'])) {
      $sql = mysqli_query($link, "SELECT `ID_Postavschik`, `FIO_Postavschik`, `Dolgnost`,`Stage`,
		`Name_Organisation`, `Adres`, `Telephone` FROM `postavschik` WHERE `ID_Postavschik`={$_GET['red']}");
      $postavschik = mysqli_fetch_array($sql);
    }
  ?>
  <!--Форма заполнения полей-->
  <form action="" method="post">
    <table>
      <tr>
        <td>ФИО поставщика:</td>
        <td><input type="text" name="FIO_Postavschik" size="100" value="<?= isset($_GET['red']) ? $postavschik['FIO_Postavschik'] : ''; ?>"></td>
      </tr>
      <tr>
        <td>Должность:</td>
        <td><input type="text" name="Dolgnost" size="30" value="<?= isset($_GET['red']) ? $postavschik['Dolgnost'] : ''; ?>"></td>
      </tr>
	  <tr>
        <td>Стаж работы:</td>
        <td><input type="text" name="Stage" size="3" value="<?= isset($_GET['red']) ? $postavschik['Stage'] : ''; ?>"></td>
      </tr>
	  <tr>
        <td>Наименование организации:</td>
        <td><input type="text" name="Name_Organisation" size="50" value="<?= isset($_GET['red']) ? $postavschik['Name_Organisation'] : ''; ?>"></td>
      </tr>
	  <tr>
        <td>Адрес:</td>
        <td><input type="text" name="Adres" size="100" value="<?= isset($_GET['red']) ? $postavschik['Adres'] : ''; ?>"></td>
      </tr>
	  <tr>
        <td>Телефон:</td>
        <td><input type="tel" name="Telephone" size="17" value="<?= isset($_GET['red']) ? $postavschik['Telephone'] : ''; ?>"></td>
      </tr>
      <tr>
        <td>
		<input type="submit" value="Подтвердить">
		</td>
		<td><button><a href="postavschik.php">Назад</a></button></td>
      </tr>
    </table>
	<?php require('postavka.php'); ?>
  </form>
</body>
</html>