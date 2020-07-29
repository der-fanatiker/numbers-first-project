<?php

class auth
{
    const hostname = "numbers-database-legal-1";
    const username = "root";
    const password = "docker";
    const dbname = "db_numbers";
    private $link;

    function __construct()
    {
        $this->link = new mysqli(self::hostname, self::username, self::password, self::dbname);
        $this->link->query("SET NAMES cp1251");
        if (mysqli_connect_errno()) {
            printf("Ошибка подключения: %s\n" . mysqli_connect_error());
            exit();
        }
    }

    public function login($username, $password)
    {
        $error = 0;
        $result = $this->link->query("SELECT * FROM users WHERE login='" . $this->link->real_escape_string($username) . "'");
        if ($result->num_rows == '1') {
            //Тогда пользователь один и существует
            $row = $result->fetch_assoc();
            if ($username != 'rezerv') {
                //Здесь мы просто регистрируем все необходимые сессионные переменные и пускаем пользователя на его территорию на его
                if ($row["blocked"] == '1') {
                    header("Location:index.php");
                } else {

                    if ($row['password'] == $password) {
                        if ($row['status'] == '1') {
                            //echo "ADMIN";
                            $_SESSION['legal1']['status'] = "admin";
                            $_SESSION['legal1']['name'] = $row['name'];
                            $_SESSION['legal1']['id'] = $row['id'];
                            $_SESSION['legal1']['org'] = $row['organization'];
                            //header("Location:admin.php");
                        } else {
                            //echo "USER";
                            $_SESSION['legal1']['status'] = "user";
                            $_SESSION['legal1']['name'] = $row['name'];
                            $_SESSION['legal1']['id'] = $row['id'];
                            $_SESSION['legal1']['org'] = $row['organization'];
                            //header("Location:users.php");
                        }
                        //header("Location:index.php");
                    } else {
                        $error = 1;
                    }
                }
            } else {
                $error = 1;
            }
        } else {
            //Пользователь не существует, либо он просто злоумышленник
            $error = 1;
        }
        return $error;
    }

    public function logout()
    {
        unset($_SESSION['legal1']);
        session_destroy();
        header("Location:index.php");
    }

    public function form($error = 0)
    {
        ?>
        <html>
        <head>
            <title>Страница аутентификации пользователя</title>
            <script language='JavaScript' type='text/javascript' src='js/scripts.js'>
            </script>
        </head>
        <body>
        <table order='1' cellspacing='3' cellpadding='3'>
            <tr>
                <td>
                    <table align='100%'>
                        <tr>
                            <td>
                                <?php
                                if ($error == '1') {
                                    ?>
                                    <font color='red'>Вы указали неправильный логин или пароль</font>
                                    <?
                                }
                                ?>
                                <form action='index.php?action=login' method='POST' name='auth'
                                      onSubmit='return checkInfo();'>
                                    <pre>Login    <input type='text' name='login'></pre>
                                    <pre>Password <input type='password' name='password'></pre>
                                    <input type='submit' name='action' value='login' style='width:220px;'>
                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
            </tr>
        </table>
        </body>
        </html>
        <?
    }

    function __destruct()
    {
        $this->link->close();
    }
}
