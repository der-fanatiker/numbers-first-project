<?php
	//Блок обьявления функций
	//Функция для создания фильтра, на основе выбранных параметров
	$page = 0;
	//Сделать так, чтобы этот параметр можно было задать
	$rowsOnPage = 20;
	function makeFilter($mysqliObj,$tableName,$selectOpt,$formName,$selectName,$rows)
	{
		$numParams = func_num_args();
		//параметров 6
		$paramsStandart = true;
		switch($selectOpt)
		{
			case "none":
			{
				$select = "SELECT ";
				break;
			}
			case "DISTINCT":
			{
				$select = "SELECT DISTINCT ";
				break;
			}
			default:
			{
				echo "Error selectOpt";
				exit();
				break;
			}
		}
		switch($numParams)
		{
			case "6":
			{
				$sql = $select.$rows." FROM ".$tableName;
				break;
			}	
			case "7":
			{
				//параметров 7
				$paramsStandart = false;
				$params = func_get_args();
				$sql = $select.$params['5'].",".$params['6']." FROM ".$tableName;
				break;
			}
			default:
			{
				//Некорректное число параметров, и надо завершить выполнение скрипта
				echo "Error params";
				exit();
				break;
			}
		}
		//echo $sql;
		$result = $mysqliObj->query($sql);
		echo "<form name='".$formName."'><select name='".$selectName."' onChange='filterSelect(\"".$formName."\")'><option selected>Фильтр</option>";
		while($row = $result->fetch_assoc())
		{
			if($paramsStandart == true)
			{
				//Количество параметров - стандартное
				echo "<option value='".$row[$rows]."'>".$row[$rows]."</option>";
			}
			else
			{
				//Количество параметров не стандартное
				echo "<option value='".$row[$params['5']]."'>".$row[$params['6']]."</option>";
			}
		}
		echo "<input type='hidden' name='filter' value='".$selectName."'><input type='hidden' name='action' value='users_show'><input type='hidden' name='module' value='users'></select></form>";
	}
	//Функция для создания постраничного вывода
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
	<style type='text/css'>		  
			body
			{
				font-family:Arial, Helvetica, sans-serif;
			}
		   .head
		   {
		   	background-image:url("images/bg.jpg");
			background-repeat:repeat-x;
		   }
		   .headmiddle
		   {
		   	background-image:url("images/headmiddle.jpg");
			background-repeat:repeat-x;
		   }
		   .headp
		   {
			font-size: 125%;
			
		   }
		   a
		   {
			text-decoration:none;
		   }
		   a.menu
		   {
			display:block;
		   }
		   a.menu:hover
		   {
			background-color:#ffccff;
			border:1px dashed gray;
		   }
		   li
		   {
			list-style:none;
		   }
		   .info
		   {
			border:1px solid gray;
			
		   }
		   th
		   {
			border:1px solid gray;
			background-color:#e6e3e3;
		   }
		   .cells
		   {
		   border:1px solid gray;
		   background-color:white;
		   }
		   .cell
		   {
			 border:1px solid gray;
		   }
		   a.navigation:hover
		   {
			color:red;
		   }
	</style>
<script language='JavaScript' type='text/javascript'>
<!--
	function filterSelect(formname)
	{
		document[formname].submit();
	}
	function onLoad()
            {
				iedetect = true;
				elem = document.getElementById("high");
				children = elem.getElementsByTagName("tr");
				if(elem)
				{
					if(document.addEventListener)
					{
						function overColor()
						{	
							this.style.backgroundColor = "#ffff99";
						}
						function outColor()
						{
							this.style.backgroundColor = "white";
						}
						for(i=0;i<children.length; i++)
						{
							children[i].addEventListener("mouseover",overColor, true);
							children[i].addEventListener("mouseout",outColor, true);
						}
						
					}
					else
					{
						for(i=0;i<children.length; i++)
						{
							children[i].onmouseover = function(){this.style.backgroundColor = "#ffff99";}
							children[i].onmouseout = function(){this.style.backgroundColor = "white";}
						}
					}
				}
            }
-->
</script>
</head>
<body onLoad='onLoad()'>

<table width='100%' border='0' cellspacing='1' cellpadding='0'>
 		<tr>
		 <td colspan='4'>
		  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
		   <tr>
		    <td class='head' colspan=5>
		 	 <h2><font color='white'><?=$_SESSION['legal3']['name']?>(<?=$_SESSION['legal3']['org']?>)<br>Номера для юридического лица 3</font></h2>
		    </td>
		   </tr>
		   <tr>
		    <td class='headmiddle' width='5%'>
		    </td>
		    <td class='headmiddle' width='30%'>
		     <div style='display:block;background-color:white;'>
		    	&nbsp;&nbsp;Администраторская часть
		     </div>
		    </td>
		    <td class='headmiddle' width='50%'>
		    </td>
		    <td width='10%' class='headmiddle'>
			<a href='index.php?action=logout'>
		 	 <div style="display:block;background-color:white;">
			 &nbsp;&nbsp;Выход
			 </div>
			 </a>
		    </td>
		    <td width='5%' class='headmiddle'>
		    </td>
		   </tr>
	     </table>
		</td>
	   </tr>
	   <tr>
	   <td colspan='3'></td>
	   </tr>
		<tr>
		 <td valign='top' width='20%'style='background-color:white;border:1px solid gray'>
		  <table width='100%' >
		    <tr>
			 <td style='font-size:125%;'>
			  Меню
			 </td>
			</tr>
			<tr>
			  <td>
			  <table width='100%'>
			   <tr>
			     <td width='100%'>
                    <a class='menu' href="http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=users&action=users_show">
                        -&nbsp;Управление пользователями
                    </a>
                 </td>
                </tr>
                <tr>
                 <td>
                    <a  class='menu' href="http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=reserve">
                    -&nbsp;Пользователь Резерв
                        </a>
                 </td>
                </tr>
                <tr>
                 <td>
                    +&nbsp;Настройки приложения
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=setup'>
                        &nbsp;&nbsp;-&nbsp;Документы<br>
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=setup&action=waybill'>
                        &nbsp;&nbsp;-&nbsp;Договор<br>
                    </a>
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=reserve&action=daily'>
                                    &nbsp;&nbsp;-&nbsp;Резервирование номеров на документы<br>
                    </a>
                 </td>
                </tr>
                <tr>
                 <td>
                    +&nbsp;Получение номера
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=number&action=document'>
                        &nbsp;&nbsp;-&nbsp;Документ<br>
                    </a>
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=number&action=waybill'>
                        &nbsp;&nbsp;-&nbsp;Договор<br>
                    </a>
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=future'>
                        &nbsp;&nbsp;-&nbsp;Резерв<br>
                    </a>
			     </td>
                </tr>
                <tr>
                 <td>
                    +&nbsp;Отчет по всем номерам
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=report'>
                        &nbsp;&nbsp;-&nbsp;Документы<br>
                    </a>
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=report&action=waybill'>
                        &nbsp;&nbsp;-&nbsp;Договор<br>
                    </a>
                    <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=future&action=show'>
                        &nbsp;&nbsp;-&nbsp;Резерв<br>
                    </a>
                 </td>
                </tr>
                <tr>
                    <td>
                        +&nbsp;Архив номеров
                        <?php #22 июля добавил?>
                        <a class='menu' href='http://<?=$_SERVER["HTTP_HOST"]?>/legal3/admin.php?module=archive'>
                            &nbsp;&nbsp;-&nbsp;Поиск<br>
                        </a>
                        <?php #22 июля добавил?>
                    </td>
                </tr>
			  </table>
			  </td>
	        </tr>
		  </table>
	   </td>
	   <td valign='top'>
	   &nbsp;
	   </td>
	  <td valign='top' width='80%'>	<table width='100%' border='1' align='center' cellpadding='3' cellspacing='3'>
	 <table width='100%' class='info' id='high'> 
	 <tr>
	  <th>
	   Ф.И.О
	  </th>
	  <th>
	   Логин
	  </th>
	  <th>
	   Организация
	  </th>
	  <th>
	   Ранг
	  </th>
	  <th>
	   Статус
	  </th>
	 </tr>
	 <?php
	  echo "<tr><td  class='cells'>";
	  makeFilter($portal->link,"users ORDER BY name ASC","none","form1","name","id","name");
	  echo "</td>";
	  echo "<td  class='cells'>";
	  makeFilter($portal->link,"users ORDER BY login ASC","none","form2","login","id","login");
	  echo "</td>";
	  echo "<td  class='cells'>";
	  makeFilter($portal->link,"users ORDER BY organization ASC","DISTINCT","form3","org","organization");
	  echo "</td>";
	  echo "<td class='cells'>";
	  //
	  makeFilter($portal->link,"users ORDER BY status ASC","DISTINCT","form4","status","status");
	  echo "</td><td class='cells'>&nbsp;&nbsp;&nbsp;</td></tr>";
	  if(!isset($_GET['filter']))
	  {
		 //Загружаем все записи и делаем для них постраничный вывод
			$resultRows = $portal->link->query("SELECT COUNT(id) AS nid FROM users");
			$row = $resultRows->fetch_assoc();
			$pages = ceil($row['nid']/$rowsOnPage);
			if(!isset($_GET['page']))
			{
				//Загружаем первую страницу
				$sql = "SELECT * FROM users ORDER BY name LIMIT 0,".$rowsOnPage;
			}
			else
			{
				//Загружаем выбранную страницу
				$sql = "SELECT * FROM users ORDER BY name LIMIT ".$_GET['page']*$rowsOnPage.",".$rowsOnPage;
			}
			$result = $portal->link->query($sql);
			//Это для вывода результата
			if($result->num_rows != '0')
			   {
				while($row = $result->fetch_assoc())
				{
					if($row['name']=='rezerv')
					{
						continue;
					}
					echo "<tr><td  class='cell'>".$row['name']."</td><td class='cell'>".$row['login']."</td><td class='cell'>".$row['organization']."</td>";
					switch($row['status'])
					{
						case "0":
						{
							echo "<td class='cell'>Пользователь</td>";
							break;
						}
						case "1":
						{	
							echo "<td class='cell'>Админ</td>";
							break;
						}
						default:
						{
							echo "<td class='cell'>Неопределен</td>";
							break;
						}
					}
					switch($row['blocked'])
					{
						case "0":
						{
							echo "<td class='cell'>Активен</td>";
							break;
						}
						case "1":
						{
							echo "<td class='cell'>Заблокирован</td>";
							break;
						}
						default:
						{
							echo "<td class='cell'>Неопределен</td>";
							break;
						}
					}
					echo "<td  class='cell'><a href='admin.php?module=users&action=user_edit&id=".$row['id']."'><img src='images/edit.png' style='border:0px solid white' title='Редактировать'></a></td><td  class='cell'><a href='admin.php?module=users&action=user_delete&id=".$row['id']."' onClick='return confirm(\"Вы уверены, что хотите заблокировать пользователя\")'><img src='images/delete.png' style='border:0px solid white' title='Заблокировать'></a></td><td  class='cell'><a href='admin.php?module=users&action=user_rep&id=".$row['id']."'><img src='images/report.png' style='border:0px solid white' title='Отчет'></a></td></tr>";
				}
				echo "<tr><td colspan='5'  class='cell'><center><a href='admin.php?module=users&action=user_new'>Новый</a></center></td></tr>";
				echo "<table width='100%'><tr><td class='cell'>";
				if(!isset($_GET['page']))
				{
					$currentPage = 0;
				}
				else	
				{
					$currentPage = $_GET['page'];
				}
				for($i=0;$i<$pages;$i++)
				{
					if($i == $currentPage)
					{	
						echo $i."&nbsp;&nbsp;|&nbsp;&nbsp;";
						continue;
					}
					echo "<a href='admin.php?module=users&page=".$i."&action=users_show'>".$i."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
				}
				echo "</td></tr></table>";
			}
	    }
		else
		{
			//Загружаем записи, заданные фильтром и делаем постраничный вывод
			switch($_GET['filter'])
			{
				case "name":
				{
					$sql = "SELECT * FROM users WHERE id='".$_GET['name']."'";
					break;
				}
				case "login":
				{
					$sql = "SELECT * FROM users WHERE id='".$_GET['login']."'";
					break;
				}
				case "org":
				{
					$sql = "SELECT * FROM users WHERE organization='".$_GET['org']."'";
					break;
				}
				case "status":
				{
					$sql = "SELECT * FROM users WHERE status='".$_GET['status']."'";
					break;
				}
				default:
				{
					echo "Неккоректный параметр фильтра, выключаюсь!";
					exit();
					break;
				}
			}
			$result = $portal->link->query($sql);
			while($row = $result->fetch_assoc())
			{
				if($row['name']=='rezerv')
				{
					continue;
				}
				echo "<tr><td class='cell'>".$row['name']."</td><td  class='cell'>".$row['login']."</td><td class='cell'>".$row['organization']."</td>";
				switch($row['status'])
				{
					case "0":
					{
						echo "<td class='cell'>Пользователь</td>";
						break;
					}
					case "1":
					{	
						echo "<td class='cell'>Админ</td>";
						break;
					}
					default:
					{
						echo "<td  class='cell'>Неопределен</td>";
						break;
					}
				}
				switch($row['blocked'])
				{
					case "0":
					{
						echo "<td class='cell'>Активен</td>";
						break;
					}
					case "1":
					{	
						echo "<td class='cell'>Заблокирован</td>";
						break;
					}
					default:
					{
						echo "<td  class='cell'>Неопределен</td>";
						break;
					}
				}
				echo "<td  class='cell'><a href='admin.php?module=users&action=user_edit&id=".$row['id']."'><img src='images/edit.png' title='Редактировать'></a></td><td class='cell'><a href='admin.php?module=users&action=user_delete&id=".$row['id']."' onClick='return confirm(\"Вы уверены, что хотите заблокировать пользователя\")'><img src='images/delete.png' title='Заблокировать'></a></td><td class='cell'><a href='admin.php?module=users&action=user_rep&id=".$row['id']."'><img src='images/report.png' title='Отчет'></a></td></tr>";
			}
			echo "</table>";
		}
	    ?>
		</td>
	  </tr>
   </tbody>
 </table>
</body>
</html>