<?php
session_start();
if (isset($_SESSION["legal3"]) && ($_SESSION["legal3"]["status"] == "user")) {
    //���������� ������ ������������
    if (isset($_GET["module"])) {
        //��� ������ �������, ����� ���� ��� �������� 1-��������� ������ �� ��������(document), 2-��������� ������ �� ���������(waybill)
        switch ($_GET["module"]) {
            case "document": {
                include_once("classes/portalClass.php");

                if (isset($_GET["action"])) {
                    //����� ������������ ��������� �����-���� ��������
                    switch ($_GET["action"]) {
                        case "getnum": {
                            $portal = new portal();
                            $portal->getNum($_SESSION['legal3']['id']);
                            break;
                        }
                        default: {
                            header("Location:users.php");
                            break;
                        }
                    }
                } else {
                    //������� �������� �� �� ���������, � ������ ����� �� ��������

                    if (isset($_GET['nid'])) {
                        //���������� �������
                        $portal = new portal();
                        if (isset($_GET['page'])) {
                            $portal->page = $_GET['page'];
                        } else {
                            $portal->page = 0;
                        }
                        $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                        $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                        $portal->href = "users.php?module=document";
                        include_once "templates/userheader.html";
                        $portal->showGetForm($_SESSION['legal3']['id'], $_GET['nid']);
                        include_once "templates/userfooter.html";
                    } else {
                        //���������� ����� ��������� ������� �\��� ��� �������� ������������ �������
                        $portal = new portal();
                        if (isset($_GET['page'])) {
                            $portal->page = $_GET['page'];
                        } else {
                            $portal->page = 0;
                        }
                        $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                        $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                        $portal->href = "users.php?module=document";
                        include_once "templates/userheader.html";
                        $portal->showGetForm($_SESSION['legal3']['id']);
                        include_once "templates/userfooter.html";
                    }
                }
                break;
            }
            case "waybill": {
                include_once("classes/portalClass.php");
                if (isset($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "getwnum": {
                            $portal = new portal();
                            $result = $portal->getDocNumber($_SESSION["legal3"]["id"], $_POST["comment"]);
                            //print_r($result["success"]);
                            if ($result["success"] == false) {
                                //������ ������������ �� ���� ����������� � �������!
                                header("Location:users.php?module=waybill&error=1");
                            } else {
                                //��� �������, ����� ��� ���� ��������� � ��������� ����� �� ��������� ��������� ������ �� ��������� � ���������� ��� �
                                //������ 25.10.2007 ����� ������� ����������� �������� ������ ��� ������������
                                header("Location:users.php?module=waybill&nid=" . $result["id"]);
                            }
                            break;
                        }
                        default: {
                            header("Location:users.php");
                            break;
                        }
                    }
                } else {
                    //������������ ����� �� ��������� ��������� ������ ��� ���������
                    if (isset($_GET["nid"])) {
                        //������������ ������� ������� � �� ����� �������� ��� ���� �� �������
                        $portal = new portal();
                        if (isset($_GET['page'])) {
                            $portal->page = $_GET['page'];
                        } else {
                            $portal->page = 0;
                        }
                        $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                        $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                        $portal->href = "users.php?module=waybill";
                        include_once "templates/userheader.html";
                        $portal->getDocNumberForm($_SESSION["legal3"]["id"], 0, $_GET["nid"]);
                        $portal->makeNavigationDoc();
                        include_once "templates/userfooter.html";

                    } else {
                        //������ ��������� ������ �� ���������
                        if (isset($_GET["error"])) {
                            $portal = new portal();
                            include_once "templates/userheader.html";
                            $portal->getDocNumberForm($_SESSION["legal3"]["id"], $_GET["error"]);
                            include_once "templates/userfooter.html";
                        } else {
                            $portal = new portal();
                            if (isset($_GET['page'])) {
                                $portal->page = $_GET['page'];
                            } else {
                                $portal->page = 0;
                            }
                            $portal->where = " WHERE uid='" . $_SESSION['legal3']['id'] . "'";
                            $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                            $portal->href = "users.php?module=waybill";
                            include_once "templates/userheader.html";
                            $portal->getDocNumberForm($_SESSION["legal3"]["id"]);
                            $portal->makeNavigationDoc();
                            include_once "templates/userfooter.html";
                        }
                    }
                }
                break;
            }
            default: {
                //����������������� ��� ������
                header("Location: index.php?action=logout");
                break;
            }
        }
    } else {

        include_once "templates/userheader.html";
        echo "<i>����� ��� �������� ��� � ��� ����������� :)</i>";
        include_once "templates/userfooter.html";
    }
} else {
    //��� � ��� �� ������������ ��� ������ ������ �������, �������� ��� �� index.php
    header("Location:index.php");
}
?>