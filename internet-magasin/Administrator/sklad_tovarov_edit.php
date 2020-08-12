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
	
    //Если переменная передана
        if (isset($_POST["Name_Organisation"])){
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red'])) {
        $sql = mysqli_query($link, "UPDATE sklad_tovarov SET `Name_Tovar` = '{$_POST['Name_Tovar']}',`Cathegory_Tovara` = '{$_POST['Cathegory_Tovara']}', 
		`Name_Organisation` = '{$_POST['Name_Organisation']}', FIO_Postavschik = '{$_POST['FIO_Postavschik']}', `Date_Postavki` = '{$_POST['Date_Postavki']}', 
		`Kolvo_Tovarov` = '{$_POST['Kolvo_Tovarov']}', `Cost_Tovara` = '{$_POST['Cost_Tovara']}', `Valuta` = '{$_POST['Valuta']}', 
		`Opisanie_Tovara` = '{$_POST['Opisanie_Tovara']}' WHERE `ID_Tovar`={$_GET['red']}");
      } 
	  else {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "INSERT INTO `sklad_tovarov` (`Name_Tovar`, `Cathegory_Tovara`, `Name_Organisation`, `FIO_Postavschik`,
		`Date_Postavki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`, `Opisanie_Tovara`) VALUES 
		('{$_POST['Name_Tovar']}', '{$_POST['Cathegory_Tovara']}', '{$_POST['Name_Organisation']}',
		(select `FIO_Postavschik` from `postavschik` where `Name_Organisation`='{$_POST['Name_Organisation']}'),
		'{$_POST['Date_Postavki']}', '{$_POST['Kolvo_Tovarov']}', '{$_POST['Cost_Tovara']}', '{$_POST['Valuta']}', '{$_POST['Opisanie_Tovara']}')");
      }

      //Если вставка прошла успешно
      if ($sql) {
		  $rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `sklad_tovarov`"); // количество полученных строк
		  $count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
		  echo '<p>Успешно!</p>'; 
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
	
	    //Если переменная передана
		if ($sklad_tovarov['Name_Organisation']==$postavschik['Name_Organisation']){
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red'])) {
        $sql = mysqli_query($link, "UPDATE sklad_tovarov, postavschik SET  sklad_tovarov.Name_Organisation = '{$_POST['Name_Organisation']}', 
		sklad_tovarov.FIO_Postavschik = postavschik.FIO_Postavschik 
		WHERE postavschik.Name_Organisation = '{$_POST['Name_Organisation']}' AND sklad_tovarov.ID_Tovar='{$_GET['red']}'");
      } 
    }

    //Если передана переменная red, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red'])) {
      $sql = mysqli_query($link, "SELECT `ID_Tovar`, `Name_Tovar`, `Cathegory_Tovara`, `Name_Organisation`, `FIO_Postavschik`,
	  `Date_Postavki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`, `Opisanie_Tovara` FROM `sklad_tovarov` WHERE `ID_Tovar`={$_GET['red']}");
      $sklad_tovarov = mysqli_fetch_array($sql);
	  $sql_postavschik = mysqli_query($link, "SELECT * FROM postavschik");
	  $postavschik=mysqli_fetch_array($sql_postavschik);
    }
  ?>
  
  <!--Форма заполнения полей-->
  <form action="" method="post">
    <table>
    <tr>
	<td>Наименование товара:</td>
    <td><input type="text" name="Name_Tovar" size="30" value="<?= isset($_GET['red']) ? $sklad_tovarov['Name_Tovar'] : ''; ?>"></td>
    </tr>
	
    <tr>
    <td>Категория товара:</td>
    <td><input list="Cathegory_Tovara" name="Cathegory_Tovara" size="50" value="<?= isset($_GET['red']) ? $sklad_tovarov['Cathegory_Tovara'] : ''; ?>">
	<!--Список выбора значения категории товаров-->
	<datalist id="Cathegory_Tovara" name="Cathegory_Tovara">
	<?php
	$sql_cathegory = mysqli_query($link, "SELECT * FROM cathegory_tovarov");
	//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Категория товаров"
	while($cathegory=mysqli_fetch_array($sql_cathegory)){
		//вывод значения списка из таблицы "Категория товаров"
		echo'<option value="'.$cathegory['Name_Cathegory'].'">';
		}
	?>
	</datalist>
	</td>
    </tr>
	
	<tr>
    <td>Наименование организации:</td>
    <td><input list="Name_Organisation" name="Name_Organisation" size="50" value="<?= isset($_GET['red']) ? $sklad_tovarov['Name_Organisation'] : ''; ?>">
	<datalist id="Name_Organisation">
	<?php
	$sql_postavschik = mysqli_query($link, "SELECT * FROM postavschik");
	//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Поставщик"
	while($postavschik=mysqli_fetch_array($sql_postavschik)){
		//вывод значения списка "Наименование организации" из таблицы "Поставщик"
		echo'<option value="'.$postavschik['Name_Organisation'].'">';}
	?>
	</datalist>
	<?php
			if ($sklad_tovarov['Name_Organisation']==$postavschik['Name_Organisation'])
			$sql = mysqli_query($link, "UPDATE `sklad_tovarov`,`postavschik`  SET  `Name_Organisation` = '{$_POST['Name_Organisation']}', 
		sklad_tovarov.FIO_Postavschik = postavschik.FIO_Postavschik WHERE sklad_tovarov.ID_Tovar={$_GET['red']}, postavschik.Name_Organisation = {$_POST['Name_Organisation']}");
	?>
	</td>
    </tr>
	
	<tr>
    <td>ФИО поставщика:</td>
    <td><input list="FIO_Postavschik" name="FIO_Postavschik" size="100" value="<?= isset($_GET['red']) ? $sklad_tovarov['FIO_Postavschik'] : ''; ?>">
	<datalist id="FIO_Postavschik">
	<?php
	$sql_postavschik = mysqli_query($link, "SELECT * FROM postavschik");
	//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Поставщик"
	while($postavschik=mysqli_fetch_array($sql_postavschik)){
		//вывод значения списка "ФИО поставщика" из таблицы "Поставщик"
		echo'<option value="'.$postavschik['FIO_Postavschik'].'">';}
	?>
	</datalist>
	</td>
    </tr>
	
	  <tr>
        <td>Дата поставки:</td>
        <td><input type="date" name="Date_Postavki" value="<?= isset($_GET['red']) ? $sklad_tovarov['Date_Postavki'] : ''; ?>"></td>
      </tr>
	  
	  <tr>
        <td>Количество товаров:</td>
		<!--Ввод количества товаров от 0 до 10000-->
        <td><input type="number" name="Kolvo_Tovarov" size="13" min="0" max="10000" value="<?= isset($_GET['red']) ? $sklad_tovarov['Kolvo_Tovarov'] : ''; ?>"></td>
      </tr>
	  
	  <tr>
        <td>Цена товара:</td>
        <td><input type="text" name="Cost_Tovara" size="13" value="<?= isset($_GET['red']) ? $sklad_tovarov['Cost_Tovara'] : ''; ?>">
		</td>
      </tr>
	  
	<tr>
    <td>Валюта:</td>
    <td><input list="Valuta" name="Valuta" size="20" value="<?= isset($_GET['red']) ? $sklad_tovarov['Valuta'] : ''; ?>">
	<datalist id="Valuta">
	<?php
	$sql_valuta = mysqli_query($link, "SELECT * FROM valuta");
	//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Валюта"
	while($valuta=mysqli_fetch_array($sql_valuta)){
		//вывод значения списка "Наименование валюты" из таблицы "Валюта"
		echo'<option value="'.$valuta['Name_Valuta'].'">';}
	?>
	</datalist>
	</td>
    </tr>
	  
	  <tr>
        <td>Описание товара:</td>
        <td><input type="text" name="Opisanie_Tovara" size="100" value="<?= isset($_GET['red']) ? $sklad_tovarov['Opisanie_Tovara'] : ''; ?>"></td>
      </tr>
	  
      <tr>
        <td>
		<input type="submit" value="Подтвердить">
		</td>
		<td><button><a href="sklad_tovarov.php">Назад</a></button></td>
      </tr>
    </table>
	<?php require('tovar_bd.php'); ?>
  </form>
</body>
</html>