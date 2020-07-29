<?php

class admin
{
    public $link;
    const hostname = "numbers-database-legal-1";
    const username = "root";
    const password = "docker";
    const dbname = "db_numbers";
    //Количество записей на странице
    const page = 20;
    //Здесь будет храниться id пользователя
    private $id;
    public $where;
    public $href;
    public $page;
    public $limit;
    public $action;
    public $date;
    public $module;
    public $type;
    public $score;
    private $daily_numbers_count = 5;
    private $max_daily_numbers = 30;
    #public $month = array();

    //Добавить константу rezerv, чтобы больше нельзя было зарегить пользователя с таким уникальным именем

    function __construct()
    {
        $this->link = new mysqli(self::hostname, self::username, self::password, self::dbname);
        $this->link->query("SET NAMES cp1251");
        if (mysqli_connect_errno()) {
            printf("Ошибка подключения: %s\n" . mysqli_connect_error());
            exit();
        }
    }

    public function userForm($id = '0')
    {
        if ($id == '0') {
            //Показываем новую форму, создания пользователя
            ?>
            <table border='0' class='info' width='100%'>
                <tr>
                    <td>
                        <table border='0'>

                            <form action='admin.php?module=users&action=user_create' method='POST' name='auth'
                                  onSubmit='return checkInfo();'>
                                <tr>
                                    <td>Ф.И.О</td>
                                    <td><input type='text' name='name' size='50'></td>
                                </tr>
                                <tr>
                                    <td>Организация</td>
                                    <td><input type='text' name='organization' size='50'></td>
                                <tr>
                                <tr>
                                    <td>Логин</td>
                                    <td><input type='text' name='login' size='50'></td>
                                </tr>
                                <tr>
                                    <td>Пароль</td>
                                    <td><input type='text' name='password' size='50'></td>
                                </tr>
                                <tr>
                                    <td>Статус</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><input type='radio' name='status' value='0'>Пользователь</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><input type='radio' name='status' value='1'>Администратор</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><input type='submit' value='Создать пользователя'></td>
                                    <td></td>
                                </tr>
                            </form>
                        </table>
                    </td>
                </tr>
            </table>
            <?
        } else {
            //Выводим все данные о пользователе с возможностью правки
            $result = $this->link->query("SELECT * FROM users WHERE id='" . $id . "'");
            $row = $result->fetch_assoc();
            ?>
            <table class='info' width='100%'>
                <tr>
                    <td>
                        <table border='0'>
                            <form action="admin.php?module=users&action=user_save" method='POST'
                                  onSubmit='return checkInfo();' name='auth'>
                                <tr>
                                    <td>Ф.И.О</td>
                                    <td><input type='text' name='name' size='50' value='<?= $row['name'] ?>'></td>
                                </tr>
                                <tr>
                                    <td>Организация</td>
                                    <td><input type='text' name='organization' size='50'
                                               value='<?= $row['organization'] ?>'></td>
                                </tr>
                                <tr>
                                    <td>Логин</td>
                                    <td><input type='text' name='login' size='50' value='<?= $row['login'] ?>'></td>
                                </tr>
                                <tr>
                                    <td>Пароль</td>
                                    <td><input type='text' name='password' size='50' value='<?= $row['password'] ?>'>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ранг</td>
                                    <td></td>
                                </tr>
                                <?
                                if ($row['status'] == '0') {
                                    echo "<tr><td><input type='radio' name='status' value='0' checked>Пользователь</td><td></td></tr><tr><td><input type='radio' name='status' value='1'>Администратор</td><td></td></tr>";
                                } else {
                                    echo "<tr><td><input type='radio' name='status' value='0'>Пользователь</td><td></td></tr><tr><td><input type='radio' name='status' value='1' checked>Администратор</td><td></td></tr>";
                                }
                                echo "<tr><td>Статус<td><td></td></tr>";
                                if ($row['blocked'] == '0') {
                                    //Пользователь активен
                                    echo "<tr><td><input type='radio' name='blocked' value='0' checked>Активен</td><td></td></tr><tr><td><input type='radio' name='blocked' value='1'>Блокирован</td><td></td></tr>";
                                } else {
                                    //Пользователь блокирован
                                    echo "<tr><td><input type='radio' name='blocked' value='1' checked>Блокирован</td><td></td></tr><tr><td><input type='radio' name='blocked' value='0'>Активен</td><td></td></tr>";
                                }
                                ?>
                                <tr>
                                    <td><input type='submit' name='action' value='user_save'>
                                        <input type='hidden' name='id' value='<?= $id ?>'>
                            </form>
                        </table>
                    </td>
                </tr>
            </table>
            <?
        }
    }

    public function userErrorForm()
    {
        echo "<font color='red'>При регистрации пользователя, вы указали логин, который уже используется!!!</>";
        ?>
        <table class='info' width='100%'>
            <tr>
                <td>
                    <table border='0'>
                        <form action='admin.php?module=users&action=user_create' method='POST' name='auth'
                              onSubmit='return checkInfo();'>
                            <tr>
                                <td>Ф.И.О</td>
                                <td><input type='text' name='name' size='50'></td>
                            </tr>
                            <tr>
                                <td>Организация</td>
                                <td><input type='text' name='organization' size='50'></td>
                            <tr>
                            <tr>
                                <td>Логин</td>
                                <td><input type='text' name='login' size='50'></td>
                            </tr>
                            <tr>
                                <td>Пароль</td>
                                <td><input type='text' name='password' size='50'></td>
                            </tr>
                            <tr>
                                <td>Статус</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type='radio' name='status' value='0'>Пользователь</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type='radio' name='status' value='1'>Администратор</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input type='submit' value='Создать пользователя'></td>
                                <td></td>
                            </tr>
                        </form>
                    </table>
                </td>
            </tr>
        </table>
        <?
    }

    public function userErrorFormEdit($id)
    {
        $result = $this->link->query("SELECT * FROM users WHERE id='" . $id . "'");
        $row = $result->fetch_assoc();
        echo "<font color='red'>При регистрации пользователя, вы указали логин, который уже используется!!!</>";
        ?>
        <table class='info' width='100%'>
            <tr>
                <td>
                    <table border='0'>
                        <form action="admin.php?module=users&action=user_save" method='POST'
                              onSubmit='return checkInfo();' name='auth'>
                            <tr>
                                <td>Ф.И.О</td>
                                <td><input type='text' name='name' size='50' value='<?= $row['name'] ?>'></td>
                            </tr>
                            <tr>
                                <td>Организация</td>
                                <td><input type='text' name='organization' size='50'
                                           value='<?= $row['organization'] ?>'></td>
                            </tr>
                            <tr>
                                <td>Логин</td>
                                <td><input type='text' name='login' size='50' value='<?= $row['login'] ?>'></td>
                            </tr>
                            <tr>
                                <td>Пароль</td>
                                <td><input type='text' name='password' size='50' value='<?= $row['password'] ?>'></td>
                            </tr>
                            <tr>
                                <td>Статус</td>
                                <td></td>
                            </tr>
                            <?
                            if ($row['status'] == '0') {
                                echo "<tr><td><input type='radio' name='status' value='0' checked>Пользователь</td><td></td></tr><tr><td><input type='radio' name='status' value='1'>Администратор</td><td></td></tr>";
                            } else {
                                echo "<tr><td><input type='radio' name='status' value='0'>Пользователь</td><td></td></tr><tr><td><input type='radio' name='status' value='1' checked>Администратор</td><td></td></tr>";
                            }
                            ?>
                            <tr>
                                <td><input type='submit' name='action' value='user_save'>
                                    <input type='hidden' name='id' value='<?= $id ?>'>
                        </form>
                    </table>
                </td>
            </tr>
        </table>
        <?
    }

    public function userNew($info)
    {
        //Добавить сюда еще и дату и время регистрации
        $result = $this->link->query("SELECT * FROM users WHERE login='" . $info['login'] . "'");
        if ($result->num_rows == 0) {
            //Тогда, все ок, такой логин не используется
            $this->link->query("INSERT INTO users(name,organization,login,password,status,date) VALUES('" . $info['name'] . "','" . $info['organization'] . "','" . $info['login'] . "','" . $info['password'] . "','" . $info['status'] . "',NOW())");
            header("Location:admin.php?module=users&action=users_show");
        } else {
            //Опп-па, а у нас уже есть такой пользватель, надо нам переделать логин
            header("Location:admin.php?module=users&action=user_new&error=1");
        }

    }

    public function userSave($info)
    {
        //echo "UPDATE users SET name='".$info['name']."',organization='".$info['organization']."',login='".$info['login']."',password='".$info['password']."',status='".$info['status']."' WHERE id='".$info['id']."'";
        $result = $this->link->query("SELECT * FROM users WHERE login='" . $info['login'] . "'AND id NOT IN('" . $info['id'] . "')");
        $blocked = 0;
        if (isset($info['blocked'])) {
            $blocked = $info['blocked'];
        }
        // echo "SELECT * FROM users WHERE login='".$info['login']."'AND id NOT IN('".$info['id']."')";
        if ($result->num_rows == '0') {
            //echo "login is not in use";
            //echo "UPDATE users SET name='".$info['name']."',organization='".$info['organization']."',login='".$info['login']."',password='".$info['password']."',status='".$info['status']."',blocked='".$blocked."' WHERE id='".$info['id']."'";
            $this->link->query("UPDATE users SET name='" . $info['name'] . "',organization='" . $info['organization'] . "',login='" . $info['login'] . "',password='" . $info['password'] . "',status='" . $info['status'] . "',blocked='" . $blocked . "' WHERE id='" . $info['id'] . "'");
            header("Location:admin.php?module=users&action=users_show");
        } else {
            //echo "login is  in use";
            header("Location:admin.php?module=users&action=user_edit&id=" . $info['id'] . "&error=1");
        }
    }

    public function userDelete($id)
    {
        //Добавить поле актив в таблицу users и при удалении пользователя менять в ней значение
        //echo "UPDATE users SET blocked='0' WHERE id='".$id."'";
        $this->link->query("UPDATE users SET blocked='1' WHERE id='" . $id . "'");
        header("Location:admin.php?module=users&action=users_show");
    }

    public function userReport($id)
    {
        $this->id = $id;
        $userInfo = $this->link->query("SELECT * FROM users WHERE id='" . $id . "'");
        $userInfoRow = $userInfo->fetch_assoc();
        echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Отчет по полученным номерам на документы</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
        echo "<table class='info'><tr><td colspan='2'>Информация о пользователе</td></tr><tr><td>Логин</td><td>" . $userInfoRow['login'] . "</td></tr><tr><td>Ф.И.О</td><td>" . $userInfoRow['name'] . "</td></tr><tr><td>Организация</td><td>" . $userInfoRow['organization'] . "</td></tr></table><br>";
        //echo "SELECT * FROM number WHERE uid='".$id."' LIMIT ".$page*self::page.",".self::page;
        // echo "SELECT * FROM number ".$this->where." ".$this->limit;
        $result = $this->link->query("SELECT * FROM number " . $this->where . " " . $this->limit);
        $this->action = "user_rep";
        if ($result->num_rows != 0) {
            //типа пользователь уже брал номерки
            echo "<table  class='info' cellpadding='3' cellspacing='3' width='100%' id='high'>";
            echo "<tr><th>Выданный номер</th><th>Дата выдачи</th></tr>";
            echo "<tr><td class='cell'>";
            $this->module = 'users';
            $this->type = "<input type='hidden' name='type' value='doc'>";
            $this->makeFilter($this->link, "number WHERE uid='" . $id . "' ORDER BY number ASC", "none", "form1",
                "number", "id", "number");

            echo "</td><td class='cell'>";
            $this->makeFilter($this->link, "number WHERE uid='" . $id . "' ORDER BY date ASC", "DISTINCT", "form2",
                "date", "DATE_FORMAT(date,GET_FORMAT(date,'JIS'))AS date");
            echo "</td></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row['number'] . "</td><td class='cell'>" . $row['date'] . "</td><td class='cell'><a href='admin.php?module=reserve&action=this&id=" . $row['id'] . "&mod=admin'>Отдать номер пользователю rezerv</a></td></tr>";
            }
            echo "</table>";
            $this->makeNavigation();
            echo "</table>";
        } else {
            //Ага, он еще совсем зеленный, номерки не брал
            echo "Пользователь еще не взял не одного номера!!!!";
        }
    }

    public function userReportWaybill($id)
    {
        $this->id = $id;
        $userInfo = $this->link->query("SELECT * FROM users WHERE id='" . $id . "'");
        $userInfoRow = $userInfo->fetch_assoc();
        echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Отчет по номерам на договора</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
        echo "<table class='info'><tr><td colspan='2'>Информация о пользователе</td></tr><tr><td>Логин</td><td>" . $userInfoRow['login'] . "</td></tr><tr><td>Ф.И.О</td><td>" . $userInfoRow['name'] . "</td></tr><tr><td>Организация</td><td>" . $userInfoRow['organization'] . "</td></tr></table><br>";
        //echo "SELECT * FROM number WHERE uid='".$id."' LIMIT ".$page*self::page.",".self::page;
        // echo "SELECT * FROM number ".$this->where." ".$this->limit;
        $result = $this->link->query("SELECT * FROM doc_number " . $this->where . " " . $this->limit);
        $this->action = "user_rep";
        if ($result->num_rows != 0) {
            //типа пользователь уже брал номерки
            echo "<table  class='info' cellpadding='3' cellspacing='3' width='100%'  id='high'>";
            echo "<tr><th>Выданный номер</th><th>Комментарий</th><th>Дата выдачи</th></tr>";
            /*
            echo "<tr><td class='cell'>";

            $this->module='users';
            $this->type = "<input type='hidden' name='type' value='doc'>";
            $this->makeFilter($this->link,"number WHERE uid='".$id."' ORDER BY number ASC","none","form1","number","id","number");

            echo "</td><td class='cell'>";
            $this->makeFilter($this->link,"number WHERE uid='".$id."' ORDER BY date ASC","DISTINCT","form2","date","DATE_FORMAT(date,GET_FORMAT(date,'JIS'))AS date");
            echo "</td></tr>";
            */
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row['number'] . "</td><td class='cell'>" . $row["comment"] . "</td><td class='cell'>" . $row['date'] . "</td></tr>";
            }
            echo "</table>";
            $this->makeNavigationRezerve("SELECT COUNT(id) AS nid FROM doc_number WHERE uid='" . $id . "'");
            echo "</table>";
        } else {
            //Ага, он еще совсем зеленный, номерки не брал
            echo "Пользователь еще не взял не одного номера!!!!";
        }
    }

    public function rezerveNumber($numberId, $mod_name)
    {
        //echo "UPDATE number SET uid=(SELECT id FROM users WHERE login='rezerv') WHERE id='".$numberId."'";
        $this->link->query("UPDATE number SET uid=(SELECT id FROM users WHERE login='rezerv') WHERE id='" . $numberId . "'") or die($this->link->error);
        //echo $_SERVER['HTTP_REFERER'];
        //Тогда в принципе можно перенаправлять на пользователя резерв, ну а пока просто перенаправим на главную страницу
        switch ($mod_name) {
            case "admin": {
                header("Location:admin.php?module=users&action=users_show");
                break;
            }
            case "management": {
                header("Location:admin.php?module=report");
                break;
            }
            default: {
                echo "Произошла ошибка. Пожалуйста свяжитесь с создателями сайта admin@admin.ru и опишите проблему.Техническая информация<br>.";
                //print_r($_GET);
                break;
            }
        }
        /*
        if(isset($_SERVER['HTTP_REFERER']))
        {
            if(strpos($_SERVER['HTTP_REFERER'],"management.php"))
            {
                echo "Refer admin to managment.php?action=man_report";
            }
            elseif(strpos($_SERVER['HTTP_REFERER'],"admin.php"))
            {
                echo "Refer admin to index.php?action=users_show";
            }
            else
            {
                echo "You have troubles, please contact admin@admin.ru with your problem.Probleb is ".$_SERVER['HTTP_REFERER'];
                exit();
            }
        }
        else
        {
            //header("Location:index.php?action=users_show");
            echo "You have troubles with your browser? please contact admin@admin.ru";
        }
        */

    }

    public function searchReserve($reserveNumberId)
    {
        $result = $this->link->query("SELECT * FROM number WHERE uid=(SELECT id FROM users WHERE login='rezerv') AND number='" . (int)$reserveNumberId . "'");
        //echo "SELECT * FROM number WHERE uid=(SELECT id FROM users WHERE login='rezerv') ".$this->limit;
        //echo "SELECT * FROM number WHERE uid=(SELECT id FROM users WHERE login='rezerv') ".$this->limit;
        if ($result->num_rows == 0) {
            //Тогда просто нет зарезервированных номеров

            echo "<div style='color:red;'>Вы указали неверный номер для поиска</div><h2>Новый поиск</h2><div><form method='GET'><input type='hidden' name='action' value='search'><input type='hidden' name='module' value='reserve'><input type='text' name='number' value=''><input type='submit' value='Искать'></form></div><h2>Вернуться</h2><div><a href='admin.php?module=reserve'>Вернуться на страницу '<strong>Пользователь Резерв</strong>'</a></div>";
        } else {
            //Выводим все зарезервированные номера
            echo "<table border='0' cellpadding='3' cellspacing='3' width='100%' class='info' id='high'>";
            echo "<tr><th>Зарезервированный номер</th><th>Дата выдачи</th><th></th></tr><tr><td class='cell'><form method='GET'><input type='hidden' name='action' value='search'><input type='hidden' name='module' value='reserve'><input type='text' name='number' value=''><input type='submit' value='Искать'></form></td><td class='cell'></td><td class='cell'></td></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row['number'] . "</td><td class='cell'>" . $row['date'] . "</td><td class='cell'><a href='admin.php?module=reserve&action=give&id=" . $row['id'] . "'>Присвоить номер пользователю</a></td></tr>";
            }
            echo "<tr><td colspan='3' class='cell'><center><a href='admin.php?module=reserve&action=more'>Зарезервировать еще</a></center></td></tr>";
            echo "</table>";
        }
    }

    public function rezerveMenu()
    {
        echo "<table border='0' cellpadding='3' cellspacing='3' width='100%' class='info'>";
        $result = $this->link->query("SELECT * FROM number WHERE uid=(SELECT id FROM users WHERE login='rezerv') " . $this->limit);
        //echo "SELECT * FROM number WHERE uid=(SELECT id FROM users WHERE login='rezerv') ".$this->limit;
        //echo "SELECT * FROM number WHERE uid=(SELECT id FROM users WHERE login='rezerv') ".$this->limit;
        if ($result->num_rows == 0) {
            //Тогда просто нет зарезервированных номеров

        } else {
            //Выводим все зарезервированные номера
            echo "<tr><th>Зарезервированный номер</th><th>Дата выдачи</th><th></th></tr><tr><td class='cell'><form method='GET'><input type='hidden' name='action' value='search'><input type='hidden' name='module' value='reserve'><input type='text' name='number' value=''><input type='submit' value='Искать'></form></td><td class='cell'></td><td class='cell'></td></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row['number'] . "</td><td class='cell'>" . $row['date'] . "</td><td class='cell'><a href='admin.php?module=reserve&action=give&id=" . $row['id'] . "'>Присвоить номер пользователю</a></td></tr>";
            }
        }
        echo "<tr><td colspan='3' class='cell'><center><a href='admin.php?module=reserve&action=more'>Зарезервировать еще</a></center></td></tr></table>";
        $this->makeNavigation();
    }

    //ВОТ ЗДЕСЬ ИСПРАВИЛ SQL ЗАПРОС
    public function rezerveMore()
    {
        //echo "INSERT INTO number( uid, number, `date`)VALUES ((SELECT id FROM users WHERE login='rezerv'),(SELECT MAX( number )+1 FROM `number` temp_num),NOW())";
        //echo "INSERT INTO number( uid, number, `date`)VALUES ((SELECT id FROM users WHERE login='rezerv'),(SELECT MAX( number )+1 FROM `number` temp_num),NOW())";
        $max_result = $this->link->query("SELECT MAX(number) AS `max` FROM number");
        $max = $max_result->fetch_assoc();
        $this->score = $max["max"] + 1;
        $result = $this->link->query("SELECT * FROM rezerve_num WHERE number='" . $this->score . "' AND active <> '0'");
        if ($result->num_rows != 0) {
            //ТОгда такой номер уже есть
            $this->checkNumber($this->score);
            $num = $this->score;
        } else {
            $num = $this->score;
        }
        #print "INSERT INTO number( uid, number, `date`)VALUES ((SELECT id FROM users_ WHERE login='rezerv'),'".$num."',NOW())";
        $res = $this->link->query("SELECT id FROM users WHERE login='rezerv'");
        $rezerve_admin = $res->fetch_assoc();
        $this->link->query("INSERT INTO number( uid, number, `date`)VALUES ((SELECT id FROM users WHERE login='rezerv'),'" . $num . "',NOW())") or die($this->link->error);
        //$this->link->query("INSERT INTO number( uid, number, `date`)VALUES ((SELECT id FROM users WHERE login='rezerv'),(SELECT MAX( number )+1 FROM `number` temp_num),NOW())")or die($this->link->error);
        include "mailClass.php";
        $mail = new Mail($this->link, $rezerve_admin["id"], $this->link->insert_id, "doc", "1");
        header("Location:admin.php?module=reserve");
    }

    private function checkNumber($number)
    {
        $result = $this->link->query("SELECT * FROM rezerve_num WHERE number='" . $this->score . "'");
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $this->link->query("INSERT INTO number(uid,number,date) VALUES('" . $row["uid"] . "','" . $row["number"] . "','" . $row["date"] . "')");
            $this->score += 1;
            $this->checkNumber($this->score);
        } else {
            #return $number;
        }
    }

    //Метод для передачи зарезервированного номера пользователю
    public function rezerveGive($number_id)
    {
        //Добавим сюда еще вывод информации о пользователе и еще надо обязательно принять  номер  номера
        $number_info_result = $this->link->query("SELECT * FROM number WHERE id='" . $number_id . "'");
        $number_info = $number_info_result->fetch_assoc();
        $this->action = "give";
        $this->id = $number_id;
        echo "<div class='info'>Для того, чтобы присвоить пользователю зарезервированный номер, просто щелкните \"присвоить номер\"<br> Номер:" . $number_info['number'] . "<br>Дата:" . $number_info['date'] . "<br>";
        echo "</div><br><table class='info' width='100%' id='high'><tr><th>Имя пользователя</th><th>Логин</th><th>Организация</th></tr>";
        $this->module = "reserve";
        $this->type = "";
        echo "<tr><td class='cell'>";
        //Фильтр по имени
        $this->makeFilter($this->link, "users ORDER BY name ASC", "none", "form1", "name", "id", "name");
        echo "</td><td class='cell'>";
        //Фильтр по логину
        $this->makeFilter($this->link, "users ORDER BY login ASC", "none", "form2", "login", "id", "login");
        echo "</td><td class='cell'>";
        $this->makeFilter($this->link, "users ORDER BY organization", "DISTINCT", "form3", "org", "organization");
        //Фильтр по организации
        echo "</td></tr>";
        $result = $this->link->query("SELECT * FROM users " . $this->where . " " . $this->limit);
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td class='cell'>" . $row['name'] . "</td><td class='cell'>" . $row['login'] . "</td><td class='cell'>" . $row['organization'] . "</td><td class='cell'><a href='admin.php?module=reserve&action=num&nid=" . $number_id . "&uid=" . $row['id'] . "'>Присвоить номер</a></td></tr>";
        }
        echo "</table>";
        $this->makeNavigationRezerve("SELECT COUNT(id) AS nid FROM users " . $this->where);
    }

    //Метод для присвоения зарезервированного номера пользователю
    public function rezerveNum($uid, $nid)
    {
        //echo "UPDATE number SET uid='".$uid."' WHERE id='".$nid."'";
        $this->link->query("UPDATE number SET uid='" . $uid . "' WHERE id='" . $nid . "'");
        header("Location:admin.php?module=reserve");
    }

    public function rezerveForFuture($number, $date, $uid)
    {
        $result1 = $this->link->query("SELECT id FROM number WHERE number='" . $number . "'");
        $result2 = $this->link->query("SELECT id FROM rezerve_num WHERE number='" . $number . "' AND active <> '0'");
        $error = 0;
        if (ereg("^[a-zA-Zа-яА-Я]+", $date)) {
            $error = 1;
        } else {
            $newdate = explode(".", $date);
            if (count($newdate) == 3) {

                $day = $newdate["0"];
                $month = $newdate["1"];
                $year = $newdate["2"];
                #Доделать провереку
                if (($day > 31) || ($month > 12) || (strlen($year) > 4) || (strlen($year) < 4) || ($year < 2007)) {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        }
        if (($result1->num_rows == '0') && (!is_int($number)) && ($result2->num_rows == '0') && ($error == 0)) {
            //Такого номера еще нет
            $this->link->query("INSERT INTO rezerve_num(number,date,uid,active) VALUES ('" . $number . "','" . $year . "-" . $month . "-" . $day . "','" . $uid . "','1')");
            $id = $this->link->insert_id;
            include "mailClass.php";
            $mail = new Mail($this->link, $uid, $id, "rezerve", "1");
            header("Location:admin.php?module=future&fid=" . $id);
            //Типа хорошо, номер зарезервирован и ничего не должно случиться
        } else {
            //Такой номер уже есть, надо написать последний максимальный номер, лиюо человек напечатл не номер, либо человек хочет тот номер, который уже зарегали
            header("Location:admin.php?module=future&error=1&number=" . $number . "&date=" . $date);
        }
    }

    public function futureMenu($error, $fid)
    {
        if ($fid != 0) {
            $result = $this->link->query("SELECT * FROM rezerve_num WHERE id='" . $fid . "'");
            $row = $result->fetch_assoc();
            echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Полученный номер</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>Вы зарегестрировали номер <b>" . $row["number"] . "</b>, на дату <b>" . $row["date"] . "</b></td></tr></table>";
        }
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Резервирование номер для пользователя</td><td width='60%'></td>
        </tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>Для резервирования номера на документ, введите номер, дату и нажмите кнопку. <br>Последний зарезервированный номер <b>" . $this->lastNumber() . "</b>.<br>Список всех зарегестрированных
        номеров для пользователей вы можете увидеть в меню <b>Отчет по всем номерам->Резерв<b> <br><br>";
        if ($error != 0) {
            echo "<p style='color:red;'>Вы допустили ошибку или такой номер уже зарезервирован или ввели неправильно дату или номер</a>";
            $number = $_GET["number"];
            $date = $_GET["date"];
        } else {
            $number = "";
            $date = "00.00.0000";
        }
        echo "</td></tr></table>";
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Получить номер</td><td width='60%'></td>
        </tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action=\"admin.php?module=future&action=get\" method='POST'>Номер&nbsp;<input type='text' name='number' value='" . $number . "'><br>Дата&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='date' value='" . $date . "'>&nbsp;&nbsp;&nbsp;Дата в формате <b>день.месяц.год</b><br><input type='submit' value='Получить'></form></td></tr></table>";
    }

    public function futureShow()
    {
        $result = $this->link->query("SELECT rezerve_num.id,rezerve_num.number,rezerve_num.date,users.name,users.organization FROM rezerve_num LEFT JOIN users ON(rezerve_num.uid=users.id) WHERE rezerve_num.active <> '0' ORDER BY rezerve_num.id DESC");
        if ($result->num_rows != 0) {
            echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Зарезервированные пользователями номера</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
            echo "<table class='info' cellpadding='3' cellspacing='3' width='100%'><tr><th>Выданный номер</th><th>Дата выдачи</th><th>Имя пользователя</th><th>Организация</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row["number"] . "</td><td class='cell'>" . $row["date"] . "</td><td class='cell'>" . $row["name"] . "</td><td class='cell'>" . $row["organization"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Пользователи еще не зарезервировали номера";
        }
    }

    private function lastNumber()
    {
        $result = $this->link->query("SELECT MAX(number) AS lastnumber FROM number");
        $num = $result->fetch_assoc();
        return $num["lastnumber"];
    }

    //Метод для того, чтобы вывести стартовую страницу Настроек Приложения
    public function managementStart()
    {
        echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Настройки для номеров на документы</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
        echo "<table width='100%' border='0' class='info'><tr><td align='center' style='border-bottom:1px solid black;background-color:#e6e3e3'>Операции</td><td align='center' style='border-bottom:1px solid black;background-color:#e6e3e3'>Заметка</td></tr>";
        echo "<tr><td valign='top' width='40%'> Задать начальный номер<form action='admin.php?module=setup&action=new' method='POST' onSubmit='return numberCheck();'><input type='text' name='firstnumber' id='firstnumber'><input type='submit' value='Задать' ></form></td><td valign='top' width='60%'>При задании начального номера, база данных текущих номеров будет обнулена и сохранена в виде файла Excel</td></tr>";
        echo "<tr><td style='border-bottom:1px solid black;background-color:#e6e3e3'>Файлы отчетов</td><td style='border-bottom:1px solid black;background-color:#e6e3e3'>Заметка</td></tr>";
        echo "<tr><td>";
        $this->showReports("document/");
        echo "</td><td>Файлы содержат отчеты, формирующиеся при задании начального номера, имена файлов содержат дату создания</td></tr></table></table>";
    }

    //Метод для вывода в окно браузера полного отчета о выданных номерках
    public function managementWaybill()
    {
        echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Настройки для номеров на договора</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
        echo "<table width='100%' border='0' class='info'><tr><td align='center' style='border-bottom:1px solid black;background-color:#e6e3e3'>Операции</td><td align='center' style='border-bottom:1px solid black;background-color:#e6e3e3'>Заметка</td></tr>";
        echo "<tr><td valign='top' width='40%'> Задать начальный номер<form action='admin.php?module=setup&action=newwaybill' method='POST' onSubmit='return numberCheck();'><input type='text' name='firstnumber' id='firstnumber'><input type='submit' value='Задать' ></form></td><td valign='top' width='60%'>При задании начального номера, база данных текущих номеров будет обнулена и сохранена в виде файла Excel</td></tr>";
        echo "<tr><td style='border-bottom:1px solid black;background-color:#e6e3e3'>Файлы отчетов</td><td style='border-bottom:1px solid black;background-color:#e6e3e3'>Заметка</td></tr>";
        echo "<tr><td>";
        $this->showReports("waybill/");
        echo "</td><td>Файлы содержат отчеты, формирующиеся при задании начального номера, имена файлов содержат дату создания</td></tr></table></table>";
    }

    public function managementReport()
    {
        //echo "SELECT number.number,number.date,users.name,users.organization FROM number LEFT JOIN users ON(number.uid=users.id) ".$this->where." ORDER BY number.number ASC ".$this->limit;
        $result = $this->link->query("SELECT number.id,number.number,number.date,users.name,users.organization FROM number LEFT JOIN users ON(number.uid=users.id) " . $this->where . " ORDER BY number.number DESC " . $this->limit);

        if ($result->num_rows != '0') {
            //$this->action = "report";
            echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Полученные номера на документы</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
            echo "<table class='info' cellpadding='3' cellspacing='3' width='100%' id='high'><tr><th>Выданный номер</th><th>Дата выдачи</th><th>Имя пользователя</th><th>Организация</th></tr>";
            echo "<tr><td class='cell'>";
            $this->module = "report";
            $this->type = "";
            $this->makeFilter($this->link, "number ORDER BY number ASC", "none", "form1", "number", "id", "number");
            echo "</td><td class='cell'>";
            $this->makeFilter($this->link, "number ORDER BY date ASC", "DISTINCT", "form2", "date",
                "DATE_FORMAT(date,GET_FORMAT(date,'JIS'))AS date");
            echo "</td><td class='cell'>";
            $this->makeFilter($this->link, "users ORDER BY name ASC", "none", "form3", "name", "id", "name");
            echo "</td><td class='cell'>";
            $this->makeFilter($this->link, "users ORDER BY organization ASC", "DISTINCT", "form4", "org",
                "organization");
            echo "</td></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row['number'] . "</td><td class='cell'>" . $row['date'] . "</td><td class='cell'>" . $row['name'] . "</td><td class='cell'>" . $row['organization'] . "</td><td class='cell'><a href='admin.php?module=reserve&action=this&id=" . $row['id'] . "&mod=management'>Вернуть</a></td></tr>";
            }
            echo "<tr><td></td></tr></table>";
            $this->makeNavigation();
            echo " <form action='admin.php'><input type='submit' value='Скачать отчет'><input type='hidden' name='action' value='download'><input type='hidden' name='module' value='report'></form></table>";
            //echo "<td valign='top'><table width='100%' class='table'><tr><td><form action='management.php'><input type='submit' value='Скачать отчет'><input type='hidden' name='action' value='download'></form></td></tr></table></td>";
        } else {
            echo "Пользователь не взял еще ни одного номера!";
        }
    }

    //Метод для вывода отчета по номерам для договоров
    public function waybillReport()
    {
        //echo "SELECT doc_number.id,doc_number.number,doc_number.comment,doc_number.date,users.name,users.organization FROM number LEFT JOIN users ON(number.uid=users.id) ".$this->where." ORDER BY number.number DESC ".$this->limit;
        $result = $this->link->query("SELECT doc_number.id,doc_number.number,doc_number.comment,doc_number.date,users.name,users.organization FROM doc_number LEFT JOIN users ON(doc_number.uid=users.id) " . $this->where . " ORDER BY doc_number.number DESC " . $this->limit);
        //$result = $this->link->query("SELECT doc_number.id,doc_number.number,doc_number.comment,doc_number.date,users.name,users.organization FROM number LEFT JOIN users ON(number.uid=users.id) ".$this->where." ORDER BY number.number DESC ".$this->limit);
        if ($result->num_rows != 0) {

            echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Полученные номера на договора</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
            echo "<table class='info' cellpadding='3' cellspacing='3' width='100%' id='high'><tr><th>Выданный номер</th><th>Комментарий</th><th>Дата выдачи</th><th>Имя пользователя</th><th>Организация</th></tr>";
            /*	echo "<tr><td class='cell'>";
                $this->module = "report";
                $this->type = "";
                $this->action='waybill';
                $this->makeFilter($this->link,"doc_number ORDER BY number ASC","none","form1","number","id","number");
                echo "</td><td class='cell'></td><td class='cell'>";
                $this->makeFilter($this->link,"doc_number ORDER BY date ASC","DISTINCT","form2","date","DATE_FORMAT(date,GET_FORMAT(date,'JIS'))AS date");
                echo "</td><td class='cell'>";
                $this->makeFilter($this->link,"users ORDER BY name ASC","none","form3","name","id","name");
                echo "</td><td class='cell'>";
                $this->makeFilter($this->link,"users ORDER BY organization ASC","DISTINCT","form4","org","organization");
                echo "</td></tr>";
            */
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row["number"] . "</td><td class='cell'>" . $row["comment"] . "</td><td class='cell'>" . $row["date"] . "</td><td class='cell'>" . $row["name"] . "</td><td class='cell'>" . $row["organization"] . "</td></tr>";
            }
            echo "</table>";
            $this->makeNavigationRezerve("SELECT COUNT(id) AS nid FROM doc_number");
            echo "<br><form action=\"admin.php?module=report&action=load\" method='POST'><input type='submit' value='Скачать отчет'></form></table>";
        } else {
            echo "Пользователи на данный момент номеров не брали!";
        }
    }

    private function makeNavigation()
    {
        $result = $this->link->query("SELECT COUNT(id) AS nid FROM number " . $this->where);
        $row = $result->fetch_assoc();
        $pages_allowed = 10;
        $pages = ceil($row['nid'] / self::page);
        //переменная для подсчета промежутка
        $pagess = floor($this->page / $pages_allowed);
        $view = 0;
        if ($this->page < $pages) {
            $view = $pagess * $pages_allowed + $pages_allowed;
            if ($view > $pages) {
                $view = $pages;
            }
        }
        echo "<table width='100%'><tr><td align='left' style='border:1px solid gray'>";
        $previus = $pagess * $pages_allowed - $pages_allowed;
        $next = $pagess * $pages_allowed + $pages_allowed;
        if ($previus < 0) {
            $previus = 0;
        }
        if ($next > $pages - 1) {
            $next = $pages - 1;
        }
        if ($pagess != 0) {
            echo "<a href='" . $this->href . "&page=0' style='text-decoration:none;' title='На первую страницу'>&nbsp;&nbsp;&lt;&lt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . $previus . "' style='text-decoration:none;' title='Предыдущие " . $pages_allowed . " страниц'>&nbsp;&nbsp;&lt;&nbsp;&nbsp;</a>";
        }
        for ($i = $pagess * $pages_allowed; $i < $view; $i++) {
            if ($i == $this->page) {
                echo "&nbsp;&nbsp;[" . $i . "]&nbsp;&nbsp;|";
                continue;
            }
            echo "<a href='" . $this->href . "&page=" . $i . "' title='Страница '" . $i . ">&nbsp;&nbsp;" . $i . "&nbsp;&nbsp;</a>|";
        }
        if (($this->page != $pages - 1) && (($pagess + 1) * $pages_allowed <= $pages)) {
            echo "<a href='" . $this->href . "&page=" . $next . "' style='text-decoration:none;' title='Следующие " . $pages_allowed . " страниц'>&nbsp;&nbsp;&gt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . --$pages . "' style='text-decoration:none;' title='Последняя страница'>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;</a>";
        }
        echo "</td></tr></table>";
    }

    private function makeNavigationRezerve($sql)
    {
        //echo $sql;
        $result = $this->link->query($sql);
        //echo "SELECT COUNT(id) AS nid FROM number ".$this->where;
        $row = $result->fetch_assoc();
        $pages_allowed = 10;
        $pages = ceil($row['nid'] / self::page);
        //переменная для подсчета промежутка
        $pagess = floor($this->page / $pages_allowed);

        if ($this->page < $pages) {
            $view = $pagess * $pages_allowed + $pages_allowed;
            if ($view > $pages) {
                $view = $pages;
            }
        }
        echo "<table width='100%'><tr><td align='left' style='border:1px solid gray'>";
        $previus = $pagess * $pages_allowed - $pages_allowed;
        $next = $pagess * $pages_allowed + $pages_allowed;
        if ($previus < 0) {
            $previus = 0;
        }
        if ($next > $pages - 1) {
            $next = $pages - 1;
        }
        if ($pagess != 0) {
            echo "<a href='" . $this->href . "&page=0' style='text-decoration:none;' title='На первую страницу'>&nbsp;&nbsp;&lt;&lt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . $previus . "' style='text-decoration:none;' title='Предыдущие " . $pages_allowed . " страниц'>&nbsp;&nbsp;&lt;&nbsp;&nbsp;</a>";
        }
        for ($i = $pagess * $pages_allowed; $i < $view; $i++) {
            if ($i == $this->page) {
                echo "&nbsp;&nbsp;[" . $i . "]&nbsp;&nbsp;|";
                continue;
            }
            echo "<a href='" . $this->href . "&page=" . $i . "' title='Страница '" . $i . ">&nbsp;&nbsp;" . $i . "&nbsp;&nbsp;</a>|";
        }
        if (($this->page != $pages - 1) && (($pagess + 1) * $pages_allowed <= $pages)) {
            echo "<a href='" . $this->href . "&page=" . $next . "' style='text-decoration:none;' title='Следующие " . $pages_allowed . " страниц'>&nbsp;&nbsp;&gt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . --$pages . "' style='text-decoration:none;' title='Последняя страница'>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;</a>";
        }
        echo "</td></tr></table>";
    }

    private function makeFilter($mysqliObj, $tableName, $selectOpt, $formName, $selectName, $rows)
    {
        $numParams = func_num_args();
        //параметров 6
        $paramsStandart = true;
        switch ($selectOpt) {
            case "none": {
                $select = "SELECT ";
                break;
            }
            case "DISTINCT": {
                $select = "SELECT DISTINCT ";
                break;
            }
            default: {
                echo "Error selectOpt";
                exit();
                break;
            }
        }
        switch ($numParams) {
            case "6": {
                $sql = $select . $rows . " FROM " . $tableName;
                break;
            }
            case "7": {
                //параметров 7
                $paramsStandart = false;
                $params = func_get_args();
                $sql = $select . $params['5'] . "," . $params['6'] . " FROM " . $tableName;
                break;
            }
            default: {
                //Некорректное число параметров, и надо завершить выполнение скрипта
                echo "Error params";
                exit();
                break;
            }
        }
        #echo $sql;
        $result = $mysqliObj->query($sql);
        #  print $sql;
        echo "<form name='" . $formName . "'><select name='" . $selectName . "' onChange='document[\"" . $formName . "\"].submit()'><option selected>Выберите фильтр</option>";
        while ($row = $result->fetch_assoc()) {
            if ($paramsStandart == true) {
                //Количество параметров - стандартное
                foreach ($row as $k => $v) {
                    echo "<option value='" . $v . "'>" . $v . "</option>";
                }
            } else {
                //Количество параметров не стандартное
                echo "<option value='" . $row[$params['5']] . "'>" . $row[$params['6']] . "</option>";
            }
        }
        echo "</select><input type='hidden' name='filter' value='" . $selectName . "'>";
        if ($this->action != "") {
            echo "<input type='hidden' name='action' value='" . $this->action . "'>";
        }
        echo "<input type='hidden' name='id' value='" . $this->id . "'><input type='hidden' name='module' value='" . $this->module . "'>$this->type</form>";
    }

    function makelogDeleteDatabase($newNumber, $uid)
    {
        //Записываем лог logs/delete.txt, при обнулении базы данных
        $result = $this->link->query("SELECT * FROM users WHERE id='" . $uid . "'");
        $personal_info = $result->fetch_assoc();
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $this->date = date("d.m.y_H!i!s");
        if (is_writable("logs/delete.txt")) {

            if (!$fp = fopen("logs/delete.txt", 'a')) {
                die("Не могу открыть файл");
            }
            if (!fwrite($fp,
                $personal_info['name'] . "|" . $personal_info['organization'] . "|" . $this->date . "|" . $newNumber . "|" . $_SERVER['REMOTE_ADDR'] . "\r\n")
            ) {
                die("Невозможно записать в файл " . __LINE__);
            }
        } else {
            die ("Файл не доступен для записи!");
        }
        fclose($fp);
    }

    function makelogDeleteDatabaseWaybill($newNumber, $uid)
    {
        //Записываем лог logs/delete.txt, при обнулении базы данных
        $result = $this->link->query("SELECT * FROM users WHERE id='" . $uid . "'");
        $personal_info = $result->fetch_assoc();
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $this->date = date("d.m.y_H!i!s");
        if (is_writable("logs/delete.txt")) {

            if (!$fp = fopen("logs/deletewaybill.txt", 'a')) {
                die("Не могу открыть файл");
            }
            if (!fwrite($fp,
                $personal_info['name'] . "|" . $personal_info['organization'] . "|" . $this->date . "|" . $newNumber . "|" . $_SERVER['REMOTE_ADDR'] . "\r\n")
            ) {
                die("Невозможно записать в файл " . __LINE__);
            }
        } else {
            die ("Файл не доступен для записи!");
        }
        fclose($fp);
    }

    private function saveCurrentDataBase($name)
    {
        touch("report/" . $name . ".xls");
        if (($fp = fopen("report/" . $name . ".xls", 'r+')) === false) {
            die("Не могу открыть файл" . __LINE__);
        }
        $result1 = $this->link->query("SELECT MIN(DATE) AS mindate,MAX(date) AS maxdate FROM number");
        $dates = $result1->fetch_assoc();
        $result = $this->link->query("SELECT number.number,number.date,users.name,users.organization,users.login FROM number LEFT JOIN users ON(number.uid=users.id)");
        fwrite($fp,
            "<table border='1'><tr><td colspan='5'>Отчет по взятым номерам, с " . $dates['mindate'] . " по " . $dates['maxdate'] . ".Созданный в результате обновления базы данных</td></tr><tr><th>Номер</th><th>Дата получения</th><th>Ф.И.О</th><th>Организация</th><th>Логин</th></tr>");
        while ($row = $result->fetch_assoc()) {
            fwrite($fp,
                "<tr><td>" . $row['number'] . "</td><td>" . $row['date'] . "</td><td>" . $row['name'] . "</td><td>" . $row['organization'] . "</td><td>" . $row['login'] . "</td></tr>");
        }
        fwrite($fp, "</table>");
        fclose($fp);
        #Найдем сначала и увеличим идентификатор сессии
        $result1->free();
        $result->free();
        $this->link->query("INSERT INTO number_archive(uid,number,date, year, month) SELECT uid,number,date, DATE_FORMAT(date, '%Y'), DATE_FORMAT(date, '%m') FROM number");
        $this->link->query("INSERT INTO session(`date`) VALUES (NOW())");
        $this->link->query("UPDATE number_archive SET sid='" . $this->link->insert_id . "' WHERE sid='0'");
        $this->link->query("UPDATE rezerve_num SET active='0' WHERE active='1'");
    }

    private function saveCurrentDataBaseWaybill($name)
    {
        touch("report/" . $name . ".xls");
        if (($fp = fopen("report/" . $name . ".xls", 'r+')) === false) {
            die("Не могу открыть файл" . __LINE__);
        }
        $result1 = $this->link->query("SELECT MIN(DATE) AS mindate,MAX(date) AS maxdate FROM doc_number");
        $dates = $result1->fetch_assoc();
        $result = $this->link->query("SELECT doc_number.number,doc_number.date,doc_number.comment,users.name,users.organization,users.login FROM doc_number LEFT JOIN users ON(doc_number.uid=users.id)");
        fwrite($fp,
            "<table border='1'><tr><td colspan='5'>Отчет по взятым номерам, с " . $dates['mindate'] . " по " . $dates['maxdate'] . ".Созданный в результате обновления базы данных</td></tr><tr><th>Номер</th><th>Комментарий</th><th>Дата получения</th><th>Ф.И.О</th><th>Организация</th><th>Логин</th></tr>");
        while ($row = $result->fetch_assoc()) {
            fwrite($fp,
                "<tr><td>" . $row['number'] . "</td><td>" . $row["comment"] . "</td><td>" . $row['date'] . "</td><td>" . $row['name'] . "</td><td>" . $row['organization'] . "</td><td>" . $row['login'] . "</td></tr>");
        }
        fwrite($fp, "</table>");
        fclose($fp);
    }

    private function clearDataBase($newNumber)
    {
        $this->link->query("TRUNCATE TABLE number");
        $this->link->query("INSERT INTO number(number,uid,date) VALUES ('" . $newNumber . "',(SELECT id FROM users WHERE login='rezerv'),NOW())");
        header("Location:admin.php?module=setup");
    }

    private function clearDataBaseWaybill($newNumber, $uid)
    {
        $this->link->query("TRUNCATE TABLE doc_number");
        $this->link->query("INSERT INTO doc_number(number,uid,date) VALUES ('" . $newNumber . "','" . $uid . "',NOW())");
        header("Location:admin.php?module=setup&action=waybill");
    }

    public function newNumber($newNumber, $uid)
    {
        //Сохраняем базу данных, формируем файл отчетный, задаем первый номер, делаем запись в логи, кто и зачем это сделал и когда
        $this->makelogDeleteDatabase($newNumber, $uid);
        $this->saveCurrentDataBase("document/" . $this->date);
        $this->clearDataBase($newNumber);
    }

    public function newWaybillNumber($newNumber, $uid)
    {
        $this->makelogDeleteDatabaseWaybill($newNumber, $uid);
        $this->saveCurrentDataBaseWaybill("waybill/" . $this->date);
        $this->clearDataBaseWaybill($newNumber, $uid);
    }

    //Метод, который считывает из директории файлы отчетов в формате .xls
    private function showReports($dir)
    {
        $report_dir = "report/" . $dir;
        if (is_dir($report_dir)) {
            //Директория сущесвует
            if ($dh = opendir($report_dir)) {
                echo "<table width='100%'>";
                while (false !== ($report_file = readdir($dh))) {
                    if (($report_file == "..") || ($report_file == ".")) {
                        continue;
                    } else {
                        echo "<tr><td><a href='" . $report_dir . "" . $report_file . "' class='rel'><img src='images/myexcel.jpg' alt='Отчет'></a></td><td>Отчет за " . $report_file . "</td></tr>";
                    }
                }
                echo "</table>";
            }
        }
    }

    public function downloadReport()
    {
        header("Content-type: application/vnd.ms-excel");
        header('Content-disposition: attachment; filename="report.xls"');
        $result = $this->link->query("SELECT number.number,number.date,users.name,users.organization FROM number LEFT JOIN users ON(number.uid=users.id) ORDER BY number.number ASC");
        echo "<table border='1'><tr><th>Выданный номер</th><th>Дата выдачи</th><th>Имя пользователя</th><th>Организация</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['number'] . "</td><td>" . $row['date'] . "</td><td>" . $row['name'] . "</td><td>" . $row['organization'] . "</td></tr>";
        }
        echo "</table>";
    }

    public function downloadWaybill()
    {
        header("Content-type: application/vnd.ms-excel");
        header('Content-disposition: attachment; filename="report.xls"');
        $result = $this->link->query("SELECT doc_number.number,doc_number.comment,doc_number.date,users.name,users.organization FROM doc_number LEFT JOIN users ON(doc_number.uid=users.id) ORDER BY doc_number.number ASC");
        echo "<table border='1'><tr><th>Выданный номер</th><th>Комментарий</th><th>Дата выдачи</th><th>Имя пользователя</th><th>Организация</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['number'] . "</td><td>" . $row["comment"] . "</td><td>" . $row['date'] . "</td><td>" . $row['name'] . "</td><td>" . $row['organization'] . "</td></tr>";
        }
        echo "</table>";
    }

    // выводим строку поиска и календарь
    public function archive()
    {
        $month = array(
            "1" => "Янв.",
            "2" => "Фев.",
            "3" => "Март",
            "4" => "Апр.",
            "5" => "Май",
            "6" => "Июнь",
            "7" => "Июль",
            "8" => "Авг.",
            "9" => "Сен.",
            "10" => "Окт.",
            "11" => "Ноя.",
            "12" => "Дек."
        );
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Поиск номера</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action=\"admin.php\" method='GET'><input type='hidden' name='module' value='archive'><input type='hidden' name='action' value='search'><input type='text' size='50' name='what'>&nbsp;&nbsp;&nbsp;<input type='submit' value='Найти'></form></td></tr></table>";
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Календарь</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>";
        #Выводим блок календаря!
        echo "<table cellpadding='5' cellspacing='1' class='calendar'>";
        echo "<tr><th></th>";
        foreach ($month as $k => $v) {
            print "<th>" . $v . "</th>";
        }
        echo "</tr>";

        $result = $this->link->query("SELECT DISTINCT year FROM `number_archive` ORDER BY year DESC ");

        $numbers = $this->link->query("select year, month, count(*) as number from number_archive group by year, month order by year  desc, month");

        $numbers_result = array();

        while ($number = $numbers->fetch_assoc()) {
            $numbers_result[$number['year']][$number['month']] = $number['number'];
        }

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["year"];
            $months = array("0" => "0");

            for ($i = 1; $i < 13; $i++) {
                if (isset($numbers_result[$row['year']][$i])) {
                    echo "<td><a href='admin.php?module=archive&action=calendar&year=" . $row['year'] . "&m=" . $i . "'>" . $numbers_result[$row['year']][$i] . "</a></td>";
                } else {
                    echo "<td>&nbsp;&nbsp;<b>-</b>&nbsp;&nbsp;</td>";
                }
            }
            echo "</td></tr>";
        }
        $result->free();
        echo "</table>";
        echo "</td></tr></table>";
    }

    // Метод для поиска номера, если номер не задан, тогда мы просто выводим форму поиска, ну всмысле одну
    public function search($search_string, $search_active = false)
    {
        if ($search_active == true) {
            #Тогда ищем и отображаем результаты поиска
            $result = $this->link->query("SELECT number_archive.id AS nid, number_archive.number AS number,number_archive.date AS date, users.id AS usid, users.name AS name ,users.organization AS organization FROM number_archive LEFT JOIN users ON users.id = number_archive.uid WHERE number_archive.number = '" . $search_string . "' ORDER BY `date`");
            #echo "SELECT number_archive.*,users.name,users.organization,users.login FROM number_archive LEFT JOIN users ON users.id = number_archive.uid WHERE number_archive.number = '".$search_string."' ORDER BY `date`";
            echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Результаты поиска</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>";
            if ($result->num_rows > 0) {
                echo "<table class='info' cellpadding='3' cellspacing='3' width='100%'><tr><th>Выданный номер</th><th>Дата выдачи</th><th>Имя пользователя</th><th>Организация</th></tr>";
                while ($row = $result->fetch_array()) {
                    #Отображаем результаты поиска
                    echo "<tr><td class='cell'>" . $row["number"] . "</td><td class='cell'>" . $row["date"] . "</td><td class='cell'>" . $row["name"] . "</td><td class='cell'>" . $row["organization"] . "</td><td class='cell'><a href='admin.php?module=archive&action=users&id=" . $row["nid"] . "'>Сменить владельца</a></td></tr>";
                }
            } else {
                #Поиск не дал никакх результатов
                echo "Поиск не дал результатов.<br>";
                echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Новый поиск</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action=\"admin.php\" method='GET'><input type='hidden' name='module' value='archive'><input type='text' size='50' name='what'>&nbsp;&nbsp;&nbsp;<input type='submit' name='action' value='search'></form></td></tr></table>";
            }
            echo "</td></tr></table>";
        } else {

        }
    }

    #Метод который меняет владельца номера в архиве и показываем что мы сменили владельца номера, показываем что за номер и кто теперь нынешний владелец, кстати прошлого тоже можно показать!
    public function archiveChangeUser($uid, $nid)
    {
        $this->link->query("UPDATE number_archive SET uid='" . $uid . "' WHERE id='" . $nid . "'");
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Смена владельца номера</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>";
        $result = $this->link->query("SELECT id,name FROM users WHERE id=(SELECT uid FROM number_archive WHERE id='" . $nid . "')");
        $result1 = $this->link->query("SELECT id,number,date FROM number_archive WHERE id='" . $nid . "'");
        $rows = $result1->fetch_assoc();
        while ($row = $result->fetch_assoc()) {
            echo "Номер:" . $rows["number"] . "<br>Дата выдачи номера:" . $rows["date"] . "<br>Новый владелец номера:" . $row["name"] . "<br><br><br><a href='admin.php?module=archive'>Вернуться в главное меню</a>";
        }
        echo "</td></tr></table>";

    }

    #Выводим список пользователей с навигацией и возможностью передать номер пользователю
    public function archiveShowUsers($number_id)
    {
        $number_info_result = $this->link->query("SELECT * FROM number_archive WHERE id='" . $number_id . "'");
        $number_info = $number_info_result->fetch_assoc();
        $this->action = "users";
        $this->id = $number_id;
        $result1 = $this->link->query("SELECT name FROM users WHERE id=(SELECT uid FROM number_archive WHERE id='" . $number_id . "')");
        $user_name = $result1->fetch_assoc();
        echo "<div class='info'>Для того, чтобы присвоить пользователю зарезервированный номер, просто щелкните \"присвоить номер\"<br> Номер:" . $number_info['number'] . "<br>Дата:" . $number_info['date'] . "<br>Текущий владелец: " . $user_name["name"] . "<br>";
        echo "</div><br><table class='info' width='100%' id='high'><tr><th>Имя пользователя</th><th>Логин</th><th>Организация</th></tr>";
        $this->module = "archive";
        $this->type = "";
        echo "<tr><td class='cell'>";
        //Фильтр по имени
        $this->makeFilter($this->link, "users ORDER BY name ASC", "none", "form1", "name", "id", "name");
        echo "</td><td class='cell'>";
        //Фильтр по логину
        $this->makeFilter($this->link, "users ORDER BY login ASC", "none", "form2", "login", "id", "login");
        echo "</td><td class='cell'>";
        $this->makeFilter($this->link, "users ORDER BY organization", "DISTINCT", "form3", "org", "organization");
        //Фильтр по организации
        echo "</td></tr>";
        $result = $this->link->query("SELECT * FROM users " . $this->where . " " . $this->limit);
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td class='cell'>" . $row['name'] . "</td><td class='cell'>" . $row['login'] . "</td><td class='cell'>" . $row['organization'] . "</td><td class='cell'><a href='admin.php?module=archive&action=give&id=" . $number_id . "&uid=" . $row['id'] . "'>Присвоить номер</a></td></tr>";
        }
        echo "</table>";
        $this->makeNavigationRezerve("SELECT COUNT(id) AS nid FROM users " . $this->where);
    }

    public function archiveShowNumbers($year, $month)
    {
        #Введем две переменные, месяц и год
        #echo "SELECT number_archive.id AS id,number_archive.number,number_archive.date,users.name,users.organization,users.id AS uid FROM number_archive LEFT JOIN users ON (number_archive.uid=users.id) ".$this->where." ORDER BY number_archive.date ASC ".$this->limit;
        $result = $this->link->query("SELECT number_archive.id AS id,number_archive.number,number_archive.date,users.name,users.organization,users.id AS uid FROM number_archive LEFT JOIN users ON (number_archive.uid=users.id) " . $this->where . " ORDER BY number_archive.id ASC " . $this->limit);
        $month = array(
            "1" => "январь",
            "2" => "февраль",
            "3" => "март",
            "4" => "апрель",
            "5" => "май",
            "6" => "июнь",
            "7" => "июль",
            "8" => "август",
            "9" => "сентябрь",
            "10" => "октябрь",
            "11" => "ноябрь",
            "12" => "декабрь"
        );
        $this->action = "calendar";
        $this->module = "archive";
        $this->type = "";
        #$result = $this->link->query("SELECT * FROM users ".$this->where." ".$this->limit);
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Архив номеров на документы полученные за " . $month[$_GET["m"]] . "  " . $_GET["year"] . " года</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>";

        echo "<div style='text-align: right; padding: 5px 5px 10px 5px;'>
			<a href='admin.php?module=download&where=" . base64_encode($this->where) . "'>Скачать</a></div>";

        if ($result->num_rows > 0) {
            echo "<table width='100%' id='high'><tr><th>Номер</th><th>Дата выдачи</th><th>Владелец</th><th>Организация</th></tr>";
            echo "<tr><td class='cell'><form><input type='text' name='number' value=''><input type='hidden' name='year' value='" . $_GET["year"] . "'/><input type='hidden' name='module' value='" . $this->module . "'/><input type='hidden' name='action' value='" . $this->action . "'/><input type='hidden' name='filter' value='number'><input type='hidden' name='m' value='" . $_GET["m"] . "' /><input type='submit' value='Найти'></form>";
            $this->type = "<input type='hidden' name='year' value='" . $_GET["year"] . "'/><input type='hidden' name='m' value='" . $_GET["m"] . "' />";
            //Фильтр по имени
            if ($_GET["m"] < 10) {
                $m = "0" . $_GET["m"];
            } else {
                $m = $_GET["m"];
            }
            //$this->makeFilter($this->link,"number_archive WHERE DATE_FORMAT(number_archive.date,\"%Y %m\")='".$_GET["year"]." ".$m."' ORDER BY number_archive.date ASC","none","form2","number","id","number");
            echo "</td><td class='cell'>&nbsp;</td>";
            //Фильтр по логину
            echo "<td class='cell'>";
            $this->makeFilter($this->link, "users ORDER BY name ASC", "none", "form1", "name", "id", "name");
            //$this->makeFilter($this->link,"users WHERE users.id IN (SELECT DISTINCT uid FROM number_archive WHERE DATE_FORMAT(number_archive.date,\"%Y %m\")='".$_GET["year"]." ".$m."') ORDER BY name ASC","none","form1","name","id","name");
            echo "</td><td class='cell'>";
            $this->makeFilter($this->link, "users ORDER BY organization ASC", "DISTINCT", "form3", "org",
                "organization");
            //$this->makeFilter($this->link,"users WHERE users.id IN (SELECT DISTINCT uid FROM number_archive WHERE DATE_FORMAT(number_archive.date,\"%Y %m\")='".$_GET["year"]." ".$m."') ORDER BY organization","DISTINCT","form3","org","organization");
            //Фильтр по организации
            echo "</td></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td class='cell'>" . $row['number'] . "</td><td class='cell'>" . $row['date'] . "</td><td class='cell'>" . $row['name'] . "</td><td class='cell'>" . $row["organization"] . "</td><td class='cell'><a href='admin.php?module=archive&action=users&id=" . $row["id"] . "'>Сменить владельца</a></td></tr>";
            }
            echo "</table>";
            #echo "SELECT COUNT(id) AS nid FROM numbers_archive ".$this->where;
            #print "SELECT COUNT(id) AS nid FROM number_archive ".$this->where;
            $this->makeNavigationRezerve("SELECT COUNT(id) AS nid FROM number_archive " . $this->where);
        } else {
            echo "Пользователь не брал номеров в данный период времени";
        }
        echo "</td></tr></table>";
    }

    public function dailyReserve($dailyNumbersCount = 0, $succed = false, $error = false)
    {
        if (($dailyNumbersCount != '') && ($dailyNumbersCount > 0)) {

        } else {
            //Default daily numbers count
            $dailyNumbersCount = $this->daily_numbers_count;
        }
        if ($succed == true) {
            echo "<div style='font-size:1.2em;color:green;border:1px dashed green;padding:20px;margin:20px 0;'>Поздравляем. Вы удачно задали количество номеров, которые будут резервироваться каждый день.</div>";
        }
        if ($error == true) {
            echo "<div style='font-size:1.2em;color:red;border:1px dashed red;padding:20px;margin:20px 0;'>Укажите числовое значение, количества номеров, которых следует резервировать каждый день!</div>";
        }
        echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>Ежедневное резервирование номеров</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><p>Вам неободимо указать количество номеров на документы, которое будет каждый день откладываеться автоматически в резерв.<br>Будьте внимательны, максимальное количество равно " . $this->max_daily_numbers . " номерам в сутки. В поле количества, указано колчество, которое установлено сейчас.</p>";
        echo "<form action='admin.php?module=reserve&action=daily_new' method='POST'>Количество <input type='text' name='number' value='" . $dailyNumbersCount . "'><input type='submit' value='Задать новый номер'></form>";
        echo "</td></tr></table>";
    }

    public function getDailyNumbers()
    {
        $daily_numbers = 0;
        $setting_name = 'daily_numbers';
        $settings_sql = 'SELECT * FROM settings WHERE name="' . $this->link->escape_string($setting_name) . '" LIMIT 1';
        $query = $this->link->query($settings_sql);
        if ($query->num_rows == 1) {
            while ($row = $query->fetch_assoc()) {
                if (($row['value'] > 0) && ($row['value'] < $this->max_daily_numbers)) {
                    $daily_numbers = $row['value'];
                } else {
                    $daily_numbers = $this->daily_numbers_count;
                }
            }
        }
        return $daily_numbers;
    }

    public function setDailyNumbers($number)
    {
        $status = false;
        $setting_name = 'daily_numbers';
        $settings_update_query = '';
        if (($number > 0) && ($number < $this->max_daily_numbers)) {
            $setting_select_sql = 'SELECT * FROM settings WHERE name="' . $setting_name . '" LIMIT 1';
            $query = $this->link->query($setting_select_sql);
            if ($query->num_rows == 1) {
                $settings_update_query = 'UPDATE settings SET `value`="' . $this->link->escape_string($number) . '" WHERE `name`="' . $setting_name . '"';
            } else {
                $settings_update_query = 'INSERT INTO settings(`name`, `value`) VALUES("' . $setting_name . '","' . $this->link->escape_string($number) . '")';
            }
            $this->link->query($settings_update_query);
            $status = true;
        }
        return $status;
    }

    function __destruct()
    {
        $this->link->close();
    }

    public function createArchiveDownload($params)
    {

        header("Content-type: application/vnd.ms-excel");
        $fileName = 'Выгрузка_номеров_' . date("Y-m-d_H:i:s") . '.xls';
        header('Content-disposition: attachment; filename="' . $fileName . '"');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=cp1251">';

        if (isset($params['where'])) {
            $where = base64_decode($params['where']);

            $result = $this->link->query("SELECT number_archive.id AS id,number_archive.number,number_archive.date,users.name,users.organization,users.id AS
uid FROM number_archive LEFT JOIN users ON (number_archive.uid=users.id) " . $where . " ORDER BY number_archive.id ASC");

            echo "<table>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row['number'] . "</td><td>" . $row['date'] . "</td><td>" . $row['name'] .
                    "</td><td>" . $row['organization'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<table><tr><td>Нет результата</td></tr></table>";
        }


    }
}

?>
