<?php
session_start();
if (isset($_SESSION["legal1"]) && ($_SESSION["legal1"]["status"] == "user")) {
    //“ерритори€ работы пользовател€
    if (isset($_GET["module"])) {
        //»м€ модул€ заданно, может быть два варианта 1-получение номера на документ(document), 2-получени€ номера на накладную(waybill)
        switch ($_GET["module"]) {
            case "document": {
                include_once("classes/portalClass.php");

                if (isset($_GET["action"])) {
                    //“огда пользователь выполн€ет какое-либо действие
                    switch ($_GET["action"]) {
                        case "getnum": {
                            $portal = new portal();
                            $portal->getNum($_SESSION['legal1']['id']);
                            break;
                        }
                        default: {
                            header("Location:users.php");
                            break;
                        }
                    }
                } else {
                    //Ќикаких действий он не выполн€ет, а просто зашел на страницу

                    if (isset($_GET['nid'])) {
                        //ѕоказываем номерок
                        $portal = new portal();
                        if (isset($_GET['page'])) {
                            $portal->page = $_GET['page'];
                        } else {
                            $portal->page = 0;
                        }
                        $portal->where = " WHERE uid='" . $_SESSION['legal1']['id'] . "'";
                        $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                        $portal->href = "users.php?module=document";
                        include_once "templates/userheader.html";
                        $portal->showGetForm($_SESSION['legal1']['id'], $_GET['nid']);
                        include_once "templates/userfooter.html";
                    } else {
                        //ѕоказываем форму получени€ номерка и\или все выданные пользователю номерки
                        $portal = new portal();
                        if (isset($_GET['page'])) {
                            $portal->page = $_GET['page'];
                        } else {
                            $portal->page = 0;
                        }
                        $portal->where = " WHERE uid='" . $_SESSION['legal1']['id'] . "'";
                        $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                        $portal->href = "users.php?module=document";
                        include_once "templates/userheader.html";
                        $portal->showGetForm($_SESSION['legal1']['id']);
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
                            $result = $portal->getDocNumber($_SESSION["legal1"]["id"], $_POST["comment"]);
                            //print_r($result["success"]);
                            if ($result["success"] == false) {
                                //«начит пользователь не ввел комментарий к покупке!
                                header("Location:users.php?module=waybill&error=1");
                            } else {
                                //¬се отлично, можно его даже похвалить и переслать снова на страничку получени€ номера на накладную и подсветить его и
                                //завтра 25.10.2007 нужно сделать отображение названи€ модул€ дл€ пользовател€
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
                    //ѕользователь зашел на страничку получени€ номера дл€ накладной
                    if (isset($_GET["nid"])) {
                        //ѕользователь получил номерок и мы можем показать ему чего он получил
                        $portal = new portal();
                        if (isset($_GET['page'])) {
                            $portal->page = $_GET['page'];
                        } else {
                            $portal->page = 0;
                        }
                        $portal->where = " WHERE uid='" . $_SESSION['legal1']['id'] . "'";
                        $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                        $portal->href = "users.php?module=waybill";
                        include_once "templates/userheader.html";
                        $portal->getDocNumberForm($_SESSION["legal1"]["id"], 0, $_GET["nid"]);
                        $portal->makeNavigationDoc();
                        include_once "templates/userfooter.html";

                    } else {
                        //ƒиалог получени€ номера на накладную
                        if (isset($_GET["error"])) {
                            $portal = new portal();
                            include_once "templates/userheader.html";
                            $portal->getDocNumberForm($_SESSION["legal1"]["id"], $_GET["error"]);
                            include_once "templates/userfooter.html";
                        } else {
                            $portal = new portal();
                            if (isset($_GET['page'])) {
                                $portal->page = $_GET['page'];
                            } else {
                                $portal->page = 0;
                            }
                            $portal->where = " WHERE uid='" . $_SESSION['legal1']['id'] . "'";
                            $portal->limit = " LIMIT " . portal::page * $portal->page . "," . portal::page;
                            $portal->href = "users.php?module=waybill";
                            include_once "templates/userheader.html";
                            $portal->getDocNumberForm($_SESSION["legal1"]["id"]);
                            $portal->makeNavigationDoc();
                            include_once "templates/userfooter.html";
                        }
                    }
                }
                break;
            }
            default: {
                //Ќепредусмотренное им€ модул€
                header("Location: index.php?action=logout");
                break;
            }
        }
    } else {

        include_once "templates/userheader.html";
        echo "<i>«десь вам обь€сн€т как и чем пользоватс€ :)</i>";
        include_once "templates/userfooter.html";
    }
} else {
    //Ёто у нас не пользователь или просто админа занесло, отправим его на index.php
    header("Location:index.php");
}
?>