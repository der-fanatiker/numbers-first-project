<?php

class portal
{
    const hostname = "numbers-database-legal-3";
    const username = "root";
    const password = "docker";
    const dbname = "db_numbers";
    const page = 15;
    public $page;
    public $where;
    public $limit;
    public $href;
    public $page_allowed;
    public $score;
    public $link;

    //
    function __construct()
    {
        $this->link = new mysqli(self::hostname, self::username, self::password, self::dbname);
        $this->link->query("SET NAMES cp1251");
        if (mysqli_connect_errno()) {
            printf("������ �����������: %s\n" . mysqli_connect_error());
            exit();
        }
    }
    //
    //
    public function reserveSomeNumbers($lastMaxNumber)
    {
        $default_daily_numbers = 5;
        $daily_numbers = 0;
        $daily_date = '';
        $today_date = date('d:m:Y', time());
        $daily_sql = 'SELECT * FROM settings WHERE name="daily_numbers" LIMIT 1';
        $daily_data_sql = 'SELECT * FROM settings WHERE name="daily_current_data" LIMIT 1';
        $daily_query = $this->link->query($daily_sql);
        if ($daily_query->num_rows == 1) {
            while ($row = $daily_query->fetch_assoc()) {
                $daily_numbers = $row['value'];
            }
        } else {
            $daily_numbers = $default_daily_numbers;
        }
        $daily_data_query = $this->link->query($daily_data_sql);
        if ($daily_data_query->num_rows == 1) {
            while ($row = $daily_data_query->fetch_assoc()) {
                $daily_date = $row['value'];
            }
            if ($today_date != $daily_date) {
                //Reserving nubers and updating daily_date
                $this->doReserve($lastMaxNumber, $daily_numbers);
                $update_sql = 'UPDATE settings SET value="' . $today_date . '" WHERE name="daily_current_data"';
                $this->link->query($update_sql);
            }
        } else {
            //Reserve ssome numbers, gogogo
            $this->doReserve($lastMaxNumber, $daily_numbers);
            $insert_sql = 'INSERT INTO settings (`name`, `value`) VALUES("daily_current_data", "' . $today_date . '")';
            $this->link->query($insert_sql);
        }
    }

    private function doReserve($number, $reserveNum)
    {
        $insert_sql = 'INSERT INTO number (uid, number, date) VALUES ';
        $select_rezerve_sql = 'SELECT id FROM users WHERE login="rezerv" LIMIT 1';
        $select_query = $this->link->query($select_rezerve_sql);
        $row = $select_query->fetch_assoc();
        $uid_rezerve_id = $row['id'];
        for ($i = 1; $i < ($reserveNum + 1); $i++) {
            $insert = $insert_sql;
            $insert .= '("' . $uid_rezerve_id . '", "' . ($number + $i) . '", NOW())';
            //echo $insert."<br>";
            $this->link->query($insert) or die($this->link->error());
        }
        //exit();
    }

    public function getNum($uid, $header = "")
    {
        $max = "SELECT MAX(number) AS maxnum FROM number";
        $max_id = $this->link->query($max);
        $max_result = $max_id->fetch_assoc();
        $this->score = $max_result["maxnum"] + "1";
        $result = $this->link->query("SELECT * FROM rezerve_num WHERE number='" . $this->score . "' AND active <> '0'");
        if ($result->num_rows != 0) {
            //����� ����� ����� ��� ����
            # print "First number ".$max_number;
            $this->checkNumber($this->score);
            $num = $this->score;
            #   print "Max number ".$max_number;
        } else {
            $num = $this->score;
        }
        #print "INSERT INTO number(uid,number,date) VALUES ('".$uid."','".$num."',NOW())";
        #print "INSERT INTO number(uid,number,date) VALUES ('".$uid."','".$num."',NOW())";
        $this->link->query("INSERT INTO number(uid,number,date) VALUES ('" . $uid . "','" . $num . "',NOW())") or die($this->link->error);
        $this->reserveSomeNumbers($num);
        //������������ �������� � �������� ��������� ������� ������ � ���������� ���
//			echo $this->link->insert_id;

        if ($header != "") {
            //��������� ������� ����������� ������ ������������
            include "mailClass.php";
            $mail = new Mail($this->link, $uid, $this->link->insert_id, "doc", "1");
            header($header . "&nid=" . $this->link->insert_id);
        } else {
            //��������� ������� ����������� ������ ������������
            include "mailClass.php";
            $mail = new Mail($this->link, $uid, $this->link->insert_id, "doc", "1");
            header("Location:users.php?module=document&nid=" . $this->link->insert_id);

        }
    }

    private function checkNumber($number)
    {
        $result = $this->link->query("SELECT * FROM rezerve_num WHERE number='" . $this->score . "' AND active <> '0'");
        #print "SELECT * FROM rezerve_num WHERE number='".$number."'";
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            #           print "INSERT INTO number(uid,number,date) VALUES('".$row["uid"]."','".$row["number"]."','".$row["date"]."')";
            #  print "INSERT INTO number(uid,number,date) VALUES('".$row["uid"]."','".$row["number"]."','".$row["date"]."')<br>";
            $this->link->query("INSERT INTO number(uid,number,date) VALUES('" . $row["uid"] . "','" . $row["number"] . "','" . $row["date"] . "')");
            $this->score += 1;
            $this->checkNumber($this->score);
        } else {
            #return $number;
        }
    }

    public function showGetForm($uid, $nid = 0)
    {
        $result = $this->link->query("SELECT * FROM number WHERE uid='" . $uid . "' ORDER BY number DESC " . $this->limit);
        //echo "SELECT * FROM number WHERE uid='".$uid."' ORDER BY number DESC ".$this->limit;
        if ($result->num_rows != '0') {
            //����� ������������ ������� �������
            echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>��� ��������� ������ �� ��������, ������� ������, ���������� ����� ����� ��������� �������.<br><br>";
            if ($_SESSION["legal3"]["status"] == "admin") {
                echo "<form action=\"admin.php?module=number&action=getd\" method='POST'><input type='submit' value='�������� �����'></form></td></tr></table>";
            } else {
                echo "<form action=\"users.php?module=document&action=getnum\" method='POST'><input type='submit' value='�������� �����'></form></td></tr></table>";
            }
            echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>������� �������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
            echo "<table border='0' width='100%' align='center'><tr><th style='border:1px solid gray;background-color:#e6e3e3;'>�������</th><th style='border:1px solid gray;background-color:#e6e3e3;'>���� ������</th></tr>";
            while ($row = $result->fetch_assoc()) {
                if ($nid == $row['id']) {
                    echo "<tr><td bgcolor='red' style='border:1px solid gray'>" . $row['number'] . "</td><td style='border:1px solid gray'>" . $row['date'] . "</td></tr>";
                } else {
                    echo "<tr><td style='border:1px solid gray'>" . $row['number'] . "</td><td style='border:1px solid gray'>" . $row['date'] . "</td></tr>";
                }
            }
            echo "</table></td></tr></table>";
            $this->makeNavigation();
        } else {
            //�� �������
            if ($_SESSION["legal3"]["status"] == "admin") {
                echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>��� ��������� ������ �� ��������, ������� ������, ���������� ����� ����� ��������� �������.<br><br><form action=\"admin.php?module=number&action=getd\" method='POST'><input type='submit' value='�������� �����'></form></td></tr></table>";
                echo "�� ���� �� �������� �� ������ ������!������!";
            } else {
                echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'>��� ��������� ������ �� ��������, ������� ������, ���������� ����� ����� ��������� �������.<br><br><form action=\"users.php?module=document&action=getnum\" method='POST'><input type='submit' value='�������� �����'></form></td></tr></table>";
                echo "�� ���� �� �������� �� ������ ������!������!";
            }

        }

    }

    //����� �����, 24.10.2007, ��� ������ ����� ��������� ������ �� ���������
    public function getDocNumberForm($user_id, $error = 0, $nid = 0)
    {
        if ($error != 0) {
            if ($_SESSION["legal3"]["status"] == "admin") {
                echo "<p style='color:red'>�� �� ������� ���� �� �������� �������� �������!!!��������� ��������� ��������� ����!!!</p><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action='?module=number&action=getw' method='POST' onSubmit='return commentCheck()'>������� � ��������� ���� ���������� ����������� �� ��, ���� �� �������� �������� ������� � ������� ������, ����� ����� ����� ��������� �������. <br><br><textarea cols='60' rows='5' name='comment' id='comment'></textarea><br><br><input type='submit' value='�������� ����� �� ������� �����-�������'></form></td></tr></table>";
            } else {
                echo "<p style='color:red'>�� �� ������� ���� �� �������� �������� �������!!!��������� ��������� ��������� ����!!!</p><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action='?module=waybill&action=getwnum' method='POST' onSubmit='return commentCheck()'>������� � ��������� ���� ���������� ����������� �� ��, ���� �� �������� ������� � ������� ������, ����� ����� ����� ��������� �������. <br><br><textarea cols='60' rows='5' name='comment' id='comment'></textarea><br><br><input type='submit' value='�������� ����� �� ������� �����-�������'></form></td></tr></table>";
            }
            //����� ����� ������, �.�. �������� ��� ������������ �� ���� �����������, ������ ��� � ������ ������ ����������� ������, ����� ������� ��� �������� JavaScriptom
        } else {
            //��� ������ ������������, ���������� ��� ������ �����
            if ($_SESSION["legal3"]["status"] == "admin") {
                echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action='?module=number&action=getw' method='POST' onSubmit='return commentCheck()'>������� � ��������� ���� ���������� ����������� �� ��, ���� �� �������� �������� ������� � ������� ������, ����� ����� ����� ��������� �������. <br><br><textarea cols='60' rows='5' name='comment' id='comment'></textarea><br><br><input type='submit' value='�������� ����� �� ������� �����-�������'></form></td></tr></table>";
                $this->showUserDocNumbers($user_id, $nid);
            } else {
                echo "<table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>��������� ������ ������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray;padding:10px'><form action='?module=waybill&action=getwnum' method='POST' onSubmit='return commentCheck()'>������� � ��������� ���� ���������� ����������� �� ��, ���� �� �������� �������� ������� � ������� ������, ����� ����� ����� ��������� �������. <br><br><textarea cols='60' rows='5' name='comment' id='comment'></textarea><br><br><input type='submit' value='�������� ����� �� ������� �����-�������'></form></td></tr></table>";
                $this->showUserDocNumbers($user_id, $nid);
            }

        }
    }

    //��� ����, ��� ��������� ������,������ ��� ������ � ���� �����x
    public function getDocNumber($user_id, $comment)
    {
        $operation_success = false;
        $newComment = trim($comment);
        $last_id = 0;
        if (!empty($newComment)) {
            //��� �������, ���������� ����� � ������������ � ����
            $operation_success = true;
            $maxNresult = $this->link->query("SELECT MAX(number)AS num FROM doc_number");
            $max = 0;
            $maxNumber = $maxNresult->fetch_assoc();
            if (is_null($maxNumber["num"])) {
                //����� ����������� ������ ����� �� ���������
                $max = 1;
            } else {
                //����������� ��������� �����
                $max = $maxNumber["num"] + 1;
            }
            //����� ��� ����� �������� ��������� id , ����� ���������� ��� ��� ������������
            $this->link->query("INSERT INTO doc_number(number,comment,uid,date) VALUES ('" . $max . "','" . $newComment . "','" . $user_id . "',NOW())") or die($this->link->error);
            $last_id = $this->link->insert_id;
            include "mailClass.php";
            $mail = new Mail($this->link, $user_id, $last_id, "dog", "1");
        }
        $result["success"] = $operation_success;
        $result["id"] = $last_id;
        return $result;
    }

    //����� ������� ��� ���������� ������������� ������
    public function showUserDocNumbers($user_id, $nid)
    {
        $result = $this->link->query("SELECT id,number,comment,date FROM doc_number " . $this->where . " ORDER BY number DESC " . $this->limit);
        if ($result->num_rows == 0) {
            //������������ ��� �� ������� �������
            echo "�� ��� �� �������� ������� �� ��������, ����� ������ � ���������!";
        } else {
            echo "<br><table width='100%'><tr><td width='40%' class='headv' style='border-bottom-width:0px;border-top:1px solid gray;border-left:1px solid gray;border-right:1px solid gray'>������� �������</td><td width='60%'></td></tr><tr><td colspan='2' style='border:1px dashed gray; padding:10px;'>";
            echo "<table border='0' width='100%'><th style='border:1px solid gray;background-color:#e6e3e3;'>�����</th><th style='border:1px solid gray;background-color:#e6e3e3;'>�����������</th><th style='border:1px solid gray;background-color:#e6e3e3;'>���� ������</th>";
            while ($row = $result->fetch_assoc()) {
                if ($row["id"] == $nid) {
                    echo "<tr><td style='background-color:red;border:1px solid gray;'>" . $row["number"] . "</td><td  style='background-color:red;border:1px solid gray;'>" . $row["comment"] . "</td><td  style='background-color:red;border:1px solid gray;'>" . $row["date"] . "</td></tr>";
                } else {
                    echo "<tr><td style='border:1px solid gray'>" . $row["number"] . "</td><td style='border:1px solid gray'>" . $row["comment"] . "</td><td style='border:1px solid gray'>" . $row["date"] . "</td></tr>";
                }
            }
            echo "</table></td></tr></table>";
        }
    }

    private function makeNavigation()
    {
        $result = $this->link->query("SELECT COUNT(id) AS nid FROM number " . $this->where);
        $row = $result->fetch_assoc();
        $pages_allowed = 10;
        $pages = ceil($row['nid'] / self::page);
        //���������� ��� �������� ����������
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
            echo "<a href='" . $this->href . "&page=0' style='text-decoration:none;' title='�� ������ ��������'>&nbsp;&nbsp;&lt;&lt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . $previus . "' style='text-decoration:none;' title='���������� " . $pages_allowed . " �������'>&nbsp;&nbsp;&lt;&nbsp;&nbsp;</a>";
        }
        for ($i = $pagess * $pages_allowed; $i < $view; $i++) {
            if ($i == $this->page) {
                echo "&nbsp;&nbsp;[" . $i . "]&nbsp;&nbsp;|";
                continue;
            }
            echo "<a href='" . $this->href . "&page=" . $i . "' title='�������� '" . $i . ">&nbsp;&nbsp;" . $i . "&nbsp;&nbsp;</a>|";
        }
        if (($this->page != $pages - 1) && (($pagess + 1) * $pages_allowed <= $pages)) {
            echo "<a href='" . $this->href . "&page=" . $next . "' style='text-decoration:none;' title='��������� " . $pages_allowed . " �������'>&nbsp;&nbsp;&gt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . --$pages . "' style='text-decoration:none;' title='��������� ��������'>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;</a>";
        }
        echo "</td></tr></table>";
    }

    public function makeNavigationDoc()
    {
        $result = $this->link->query("SELECT COUNT(id) AS nid FROM doc_number " . $this->where);
        $row = $result->fetch_assoc();
        $pages_allowed = 10;
        $pages = ceil($row['nid'] / self::page);
        //���������� ��� �������� ����������
        $view = 0;
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
            echo "<a href='" . $this->href . "&page=0' style='text-decoration:none;' title='�� ������ ��������'>&nbsp;&nbsp;&lt;&lt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . $previus . "' style='text-decoration:none;' title='���������� " . $pages_allowed . " �������'>&nbsp;&nbsp;&lt;&nbsp;&nbsp;</a>";
        }
        for ($i = $pagess * $pages_allowed; $i < $view; $i++) {
            if ($i == $this->page) {
                echo "&nbsp;&nbsp;[" . $i . "]&nbsp;&nbsp;|";
                continue;
            }
            echo "<a href='" . $this->href . "&page=" . $i . "' title='�������� '" . $i . ">&nbsp;&nbsp;" . $i . "&nbsp;&nbsp;</a>|";
        }
        if (($this->page != $pages - 1) && (($pagess + 1) * $pages_allowed <= $pages)) {
            echo "<a href='" . $this->href . "&page=" . $next . "' style='text-decoration:none;' title='��������� " . $pages_allowed . " �������'>&nbsp;&nbsp;&gt;&nbsp;&nbsp;</a>|<a href='" . $this->href . "&page=" . --$pages . "' style='text-decoration:none;' title='��������� ��������'>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;</a>";
        }
        echo "</td></tr></table>";
    }

    function __destruct()
    {

    }
}

?>
