<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Интернет-магазин</title>
</head>
<meta charset="utf-8">
<body>
	<table border="1" width="100%" height="100%" cellspacing="0"
	cellpadding="0" align="center">
		<!--Верхняя строка - шапка сайта-->
		<tr>
			<td height="50" colspan="3" bgcolor="brown"
				<?php
				require('verh.css');
				?>
			</td>
		</tr>
		
		<!--Центральная часть сайта, состоящая из трех колонок-->
		<tr>
			<td width="20%" valign="top" align="left" bgcolor="yellow"
				<?php
				require('left.css');
				?>
			</td>
			
			<td>
			<?php
				require('postavschik_bd.php');
				?>
			</td>
			
			<td width="20%" valign="top" align="right" bgcolor="yellow"
				<?php
				require('right.css');
				?>
			</td>
			
		</tr>
</body>
</html>