<?php
session_start();
if (isset($_SESSION["legal1"])) {
    include_once("classes/authClass.php");
    //���������� ������������, ������� ���� ����� � ������ � ��� ����������
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case "logout": {
                $auth = new auth();
                $auth->logout();
                break;
            }
            case "": {
                break;
            }
            default: {
                break;
            }
        }
    } else {
        switch ($_SESSION["legal1"]["status"]) {
            case "user": {
                //���������� ������������ �� ���������������� ����
                header("Location:users.php?module=document");
                break;
            }
            case "admin": {
                //���������� ������������ �� �����������������  ����
                header("Location:admin.php?module=users&action=users_show");
                break;
            }
            default: {
                //��� ���� ���������� �������, � ������ ����� ������ �������� �� ��������� � �� ���������� ������������
                break;
            }
        }
    }
} else {
    include_once("classes/authClass.php");
    //������� ����� ����� ������ � ������
    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "login": {
                if (isset($_POST["login"]) && isset($_POST["password"])) {
                    $auth = new auth();
                    //���� access= 0,�� ��� ��������� � ����� login ��� ���������� header ��� ������������ ��������
                    $access = $auth->login($_POST["login"], $_POST["password"]);
                    if ($access == 1) {
                        //��������, ��������
                        $auth->form($access);

                    } else {
                        header("Location:index.php");
                    }
                } else {
                    //��� �������� ��������, ����� � ��������� ������ ���� ���������� index.php?action=login
                    $auth = new auth();
                    $auth->form(1);
                }
                break;
            }
            default: {
                //������������ ��������  action, ������ ���-�� ����� �������
                unset($_GET["action"]);
                //header("Location: index.php");
                break;
            }
        }
    } else {
        //������� ����� �����������
        $auth = new auth();
        $auth->form();
    }

}
?>
