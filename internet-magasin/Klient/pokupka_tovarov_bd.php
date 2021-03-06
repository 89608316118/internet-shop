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
	
    //Удаляем, если что
    if (isset($_GET['del'])) {
	//Если переменная передана
    $sql = mysqli_query($link, "DELETE FROM `pokupka_tovarov` WHERE `ID_Klient` = {$_GET['del']}");
    if ($sql) {
    echo "<p>Товар удален.</p>";
	$rows = mysqli_query($link, "SELECT COUNT(*) as count FROM `pokupka_tovarov`"); // количество полученных строк
	$count = mysqli_fetch_row($rows)[0]; // количество строк в таблице
    } else {
    echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
    }
	//Выводим данные в файл
    if (isset($_GET['print'])) {
	$sql = mysqli_query($link, 'SELECT `ID_Klient`, `FIO_Klienta`, `Adres`, `Name_Tovar`, `Date_Pokupki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta` FROM `pokupka_tovarov`');
	$sql_sklad_tovarov = mysqli_query($link, "SELECT `ID_Tovar`, `Name_Tovar`, `Cathegory_Tovara`, `Name_Organisation`, `FIO_Postavschik`,
	`Date_Postavki`, `Kolvo_Tovarov`, `Cost_Tovara`, `Valuta`, `Opisanie_Tovara` FROM `sklad_tovarov`");
	//выбираем часовой пояс Самары
	date_default_timezone_set('Europe/Samara');
	//настраиваем формат даты: H - часы, 24-часовой формат, первый ноль. i - минуты "00"-"59". j - текущий день, 2 цифры без 0 в начале. F - Полное название месяца.
	$date=date('H:i, j F');
	//сначала очищаем файл
	file_put_contents('pokupka.txt', '');
	//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Покупка товаров"
	while ($result = mysqli_fetch_array($sql)) 
	{//присваивание переменных для каждых полей (столбцов) базы данных
	$ID_Klient=$result['ID_Klient'];
	$FIO_Klienta=$result['FIO_Klienta'];
	$Adres=$result['Adres'];
	$Name_Tovar=$result['Name_Tovar'];
	$Date_Pokupki=$result['Date_Pokupki'];
	$Kolvo_Tovarov=$result['Kolvo_Tovarov'];
	$Cost_Tovara=$result['Cost_Tovara'];
	$Itogo=$result['Cost_Tovara']*$result['Kolvo_Tovarov'];
	$Valuta=$result['Valuta'];
$output="\n"."Дата ".$date.", номер товара: ".$ID_Klient.", ФИО клиента: ".$FIO_Klienta.", адрес: ".$Adres.", наименование товара: ".$Name_Tovar.", 
дата покупки товара: ".$Date_Pokupki.", количество товаров: ".$Kolvo_Tovarov.", цена товара: ".$Cost_Tovara.", итого: ".$Itogo.", валюта: ".$Valuta."\n";
	//'a+'	Открывает файл для чтения и записи; помещает указатель в конец файла. Если файл не существует - пытается его создать. 
	$fp=fopen("pokupka.txt", 'a+');//fopen — Открывает файл или URL
	fwrite($fp, $output);}
	fclose($fp);
	echo "<p>Данные распечатаны в файле.</p>";
    }
	if (isset($_GET['add'])) {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "UPDATE pokupka_tovarov, sklad_tovarov SET pokupka_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}', 
		pokupka_tovarov.Kolvo_Tovarov=sklad_tovarov.Kolvo_Tovarov
		WHERE sklad_tovarov.Name_Tovar = '{$_POST['Name_Tovar']}' AND pokupka_tovarov.ID_Klient='{$_GET['add']}' AND
		sklad_tovarov.Kolvo_Tovarov<pokupka_tovarov.Kolvo_Tovarov");
		}
//название заголовка над таблицей
print ("<br><br><p align=center><font face=verdana><b>Данные о купленных товарах</b>");
//если количество записей не равно 0
if ($count!=0){
//Получаем данные для полей
$sql = mysqli_query($link, 'SELECT `ID_Klient`, `FIO_Klienta`, `Adres`, `Name_Tovar`, `Date_Pokupki`, 
`Kolvo_Tovarov`, `Cost_Tovara`, `Valuta` FROM `pokupka_tovarov`');
//Строка названия полей
print ("<table border=1 align=center width=100% cellpadding=9>
<tr bgcolor=silver align=center>
<td>id</td>
<td>ФИО клиента</td>
<td>Адрес клиента</td>
<td>Наименование товара</td>
<td>Дата покупки товара</td>
<td>Количество товаров</td>
<td>Цена товара</td>
<td>Итого</td>
<td>Валюта</td>
<td>Отмена покупки товара</td>
<td>Изменение покупки товара</td>
</tr>");
//пока идет обработка ряда запроса, возвращая массив построчно для записи БД "Покупка товаров"
while ($result = mysqli_fetch_array($sql)) {
	  //присваивание переменных для каждых полей (столбцов) базы данных
	  $ID_Klient=$result['ID_Klient'];
	  $FIO_Klienta=$result['FIO_Klienta'];
	  $Adres=$result['Adres'];
	  $Name_Tovar=$result['Name_Tovar'];
	  $Date_Pokupki=$result['Date_Pokupki'];
	  $Kolvo_Tovarov=$result['Kolvo_Tovarov'];
	  $Cost_Tovara=$result['Cost_Tovara'];
	  $Itogo=$result['Cost_Tovara']*$result['Kolvo_Tovarov'];
	  $Valuta=$result['Valuta'];
//вывод значения для каждых полей БД в каждой строке
print ("<tr>
<td>$ID_Klient</td>
<td>$FIO_Klienta</td>
<td>$Adres</td>
<td>$Name_Tovar</td>
<td>$Date_Pokupki</td>
<td>$Kolvo_Tovarov</td>
<td>$Cost_Tovara</td>
<td>$Itogo</td>
<td>$Valuta</td>
<td><a href='?del=$ID_Klient'>Отменить покупку товара</a></td>
<td><a href='pokupka_tovarov_edit.php?red=$ID_Klient'>Изменить покупку товара</a>
</td></tr>");
} //конец цикла while
echo "Количество записей: $count";
} //конец условия
  else { //иначе если количество записей равно 0
	 echo("<br>нет записей о покупке товаров"); //то нет записей
	  } //конец оператора "иначе"
print ("</table>"); //конец таблицы
?>
<form>
<table>
<tr>
<td height="50" width="100%" colspan="2" align="center"></td>
</tr>
<tr>
<td width="50%"><p><a align="center" href="pokupka_tovarov_edit.php?add">Купить новый товар</a></p></td>
<td width="50%"><a align="center" href='?print=$ID_Klient'>Распечать данные о товарах в файл</a></td>
</tr>
</table>
</form>
</body>
</html>