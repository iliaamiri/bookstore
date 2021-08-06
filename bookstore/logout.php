<?php include "resources/config.php";include "resources/customfunctions.php";
if (isset($_SESSION['user'])){
    if (isset($_SESSION['degree'])){
        if (isset($_COOKIE['id'])){
            $cookie = explode("-",$_COOKIE['id']);
            if ($cookie['0']== $_SESSION['degree']){
                $degree = $_SESSION['degree'];
                if (isset($_SESSION['CERTIFICATE_CODE'])){
                    if (isset($_SESSION['USERS_EMAIL'])){
                        if (isset($_SESSION['Certificated'])){
                            try{
                                $connection = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $sql = $connection->prepare("UPDATE user_tbl SET Log = 0 , Session_id = NULL WHERE Username = '$_SESSION[user]'");
                                $sql->execute();
                                CookieDelete("id");
                                session_destroy();
                                header("refresh:0;url=http://localhost/bookstore/login?just_logged_out");
                            }
                            catch (PDOException $e){
                                CookieDelete("id");USession('degree');header("refresh:1;url=http://localhost/bookstore/404.php?something_went_wrong");die($e);
                            }
                        }
                        elseif(!isset($_SESSION['Certificated'])){
                            USession('degree');USession('CERTIFICATE_CODE');USession('USERS_EMAIL');CookieDelete("id");header("location:404.php");
                        }
                    }
                    elseif (!isset($_SESSION['USERS_EMAIL'])){
                        USession('degree');USession('CERTIFICATE_CODE');CookieDelete("id");header("location:404.php");
                    }
                }
                elseif (!isset($_SESSION['CERTIFICATE_CODE'])){USession('degree');CookieDelete("id");header("location:404.php");
                }
            }
            else{
                header("location:404.php?something_went_wrong");
            }
        }
        elseif (!isset($_COOKIE['id'])){
            USession('degree');
        }
    }
    elseif (!isset($_SESSION['degree'])){
        header("location:404.php");
    }
}
elseif (!isset($_SESSION['user'])){
    header("location:404.php");
}
