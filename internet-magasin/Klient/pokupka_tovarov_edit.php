<!doctype html>
<html lang="ru">
<head>
  <title>Админ-панель</title>
</head>
<body>
  <?php
   require('tovar_bd.php');
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
	
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `pokupka_tovarov`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
	
    //Если переменная передана
        if (isset($_POST["Name_Tovar"])){
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red'])) {
        $sql = mysqli_query($link, "UPDATE pokupka_tovarov SET `FIO_Klienta` = '{$_POST['FIO_Klienta']}',`Adres` = '{$_POST['Adres']}', 
		`Name_Tovar` = '{$_POST['Name_Tovar']}', `Date_Pokupki` = '{$_POST['Date_Pokupki']}', `Kolvo_Tovarov` = '{$_POST['Kolvo_Tovarov']}'
		WHERE `ID_Klient`={$_GET['red']}");
      } 
	  if (isset($_GET['add'])) {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "INSERT INTO `pokupka_tovarov` (`FIO_Klienta`, `Adres`, `Name_Tovar`, `Date_Pokupki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`) 
		VALUES ('{$_POST['FIO_Klienta']}', '{$_POST['Adres']}', '{$_POST['Name_Tovar']}', '{$_POST['Date_Pokupki']}', '{$_POST['Kolvo_Tovarov']}',
		(select `Cost_Tovara` from `sklad_tovarov` where `Name_Tovar`='{$_POST['Name_Tovar']}'), 
		(select `Valuta` from `sklad_tovarov` where `Name_Tovar`='{$_POST['Name_Tovar']}'))");
		if ($sql){
		//обновление таблицы "Покупка товаров" и "Склад товаров", если количество товаров на складе меньше количества купленных товаров
		//то количество купленных товаров равно нулю
		$sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		pokupka_tovarov.Kolvo_Tovarov=0 WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' 
		AND sklad_tovarov.Kolvo_Tovarov<pokupka_tovarov.Kolvo_Tovarov AND pokupka_tovarov.ID_Klient=(SELECT MAX(ID_Klient) from pokupka_tovarov)");
		//обновление таблицы "Покупка товаров" и "Склад товаров", если количество товаров на складе больше или равно количеству купленных товаров
		//то вычитается количество товаров на складе с количеством купленных товаров
		$sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		sklad_tovarov.Kolvo_Tovarov=sklad_tovarov.Kolvo_Tovarov - pokupka_tovarov.Kolvo_Tovarov WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' 
		AND sklad_tovarov.Kolvo_Tovarov>=pokupka_tovarov.Kolvo_Tovarov AND pokupka_tovarov.ID_Klient=(SELECT MAX(ID_Klient) from pokupka_tovarov)");
		}
      }

      //Если вставка прошла успешно
      if ($sql) {
		  $rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `pokupka_tovarov`"); // количество полученных строк
		  $count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
		  echo '<p>Успешно!</p>'; 
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
	
	//Если наименование товара при покупке равно наименованию товара на складе
	if ($pokupka_tovarov['Name_Tovar']==$sklad_tovarov['Name_Tovar']){
		//то присваиваем значение для поля "Наименование товара" при покупке, равному значению поля "Наименование товара" на складе
        $sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET pokupka_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		pokupka_tovarov.Cost_Tovara = sklad_tovarov.Cost_Tovara, pokupka_tovarov.Valuta = sklad_tovarov.Valuta 
		WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' AND pokupka_tovarov.ID_Klient='{$_GET['red']}'");
    }
	
	//Если переменная передана
    	$sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET pokupka_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		sklad_tovarov.Kolvo_Tovarov=sklad_tovarov.Kolvo_Tovarov - pokupka_tovarov.Kolvo_Tovarov 
		WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' AND pokupka_tovarov.ID_Klient='{$_GET['red']}' AND
		sklad_tovarov.Kolvo_Tovarov>=pokupka_tovarov.Kolvo_Tovarov");
		
		$sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET pokupka_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		pokupka_tovarov.Kolvo_Tovarov=sklad_tovarov.Kolvo_Tovarov
		WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' AND pokupka_tovarov.ID_Klient='{$_GET['red']}' AND
		sklad_tovarov.Kolvo_Tovarov<pokupka_tovarov.Kolvo_Tovarov");
	
    //Если передана переменная red, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red'])) {
      $sql = mysqli_query($link, "SELECT `ID_Klient`, `FIO_Klienta`, `Adres`, `Name_Tovar`, `Date_Pokupki`, 
`Kolvo_Tovarov`, `Cost_Tovara`, `Valuta` FROM pokupka_tovarov WHERE `ID_Klient`={$_GET['red']}");
      $pokupka_tovarov = mysqli_fetch_array($sql);
	  $Kolvo_Tovarov=$pokupka_tovarov['Kolvo_Tovarov'];
	  $sql_sklad_tovarov = mysqli_query($link, "SELECT `ID_Tovar`, `Name_Tovar`, `Cathegory_Tovara`, `Name_Organisation`, `FIO_Postavschik`,
	  `Date_Postavki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`, `Opisanie_Tovara` FROM `sklad_tovarov`");
	  $sklad_tovarov=mysqli_fetch_array($sql_sklad_tovarov);
    }
  ?>
  
  <!--Форма заполнения полей-->
  <form action="" method="post">
    <table>
	<br>
    <tr>
	<td>ФИО клиента:</td>
    <td><input type="text" name="FIO_Klienta" size="100" value="<?= isset($_GET['red']) ? $pokupka_tovarov['FIO_Klienta'] : ''; ?>"></td>
    </tr>
	
	<tr>
	<td>Адрес:</td>
    <td><input type="text" name="Adres" size="100" value="<?= isset($_GET['red']) ? $pokupka_tovarov['Adres'] : ''; ?>"></td>
    </tr>
	
	<tr>
    <td>Наименование товара:</td>
    <td><input list="Name_Tovar" name="Name_Tovar" size="30" value="<?= isset($_GET['red']) ? $pokupka_tovarov['Name_Tovar'] : ''; ?>">
	<datalist id="Name_Tovar">
	<?php
	$sql_sklad_tovarov = mysqli_query($link, "SELECT * FROM sklad_tovarov");
	//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Склад товаров"
	while($sklad_tovarov=mysqli_fetch_array($sql_sklad_tovarov)){
		//вывод значения списка "Наименование организации" из таблицы "Склад товаров"
		echo'<option value="'.$sklad_tovarov['Name_Tovar'].'">';}
	?>
	</datalist>
	</td>
    </tr>
	  
	<tr>
    <td>Дата покупки:</td>
    <td><input type="date" name="Date_Pokupki" value="<?= isset($_GET['red']) ? $pokupka_tovarov['Date_Pokupki'] : ''; ?>"></td>
    </tr>
	
	<tr>
    <td>Количество товаров:</td>
    <td><input type="number" name="Kolvo_Tovarov" size="13" min="0" max="10000"  
	value="<?= isset($_GET['red']) ? $pokupka_tovarov['Kolvo_Tovarov'] : ''; ?>">
	
	</td>
    </tr>
	
    <tr>
    <td>
	<input type="submit" value="Подтвердить">
	</td>
	
	<td><button><a href="pokupka_tovarov.php">Назад</a></button></td>
    </tr>
    </table>
	<?php require('pokupka.php'); ?>
  </form>
</body>
</html>