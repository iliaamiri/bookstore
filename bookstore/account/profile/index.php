<?php
        if ($_SERVER['REQUEST_URI'] != '/bookstore/account/me'){
            header("location:http://localhost/bookstore/404.php");
        }
        else{
            if (isset($_SESSION['degree'])){
                if (isset($_COOKIE['id'])){
                    $cookie_id = explode("-",$_COOKIE['id']);
                    if (!$cookie_id){
                        die(header("refresh:0;url=http://localhost/bookstore/404.php"));
                    }
                    if ($cookie_id['0'] == $_SESSION['degree']){
                        if (isset($_SESSION['user'])){
                            try{
                                $profileconn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $profileconn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $psql = $profileconn->prepare("SELECT * FROM user_tbl WHERE Username = '$_SESSION[user]' AND Status = '1'");
                                $psql->execute();
                                $rows = $psql->fetch(PDO::FETCH_ASSOC);
                                $_SESSION['profile'] = $rows['Username'];
                            }
                            catch (PDOException $e){
                                die($e);
                            }
                        }
                        elseif (!isset($_SESSION['user'])){
                            unset($_SESSION['degree']);
                            CookieDelete("id");
                            header("location:http://localhost/bookstore/404.php");
                        }
                    }
                    else{
                        unset($_SESSION['degree']);
                        CookieDelete("id");
                        header("location:http://localhost/bookstore/404.php");
                    }
                }
                elseif (!isset($_COOKIE['id'])){
                    unset($_SESSION['degree']);
                    header("location:http://localhost/bookstore/404.php");
                }
            }
            elseif (!isset($_SESSION['degree'])){
                header("location:http://localhost/bookstore/404.php");
            }
        }


?>
<?php if (isset($_SESSION['profile'])){?>
<div class="col-md-4">
    <p>Username: <?php echo $rows['Username'];?></p>
    <p>Email   : <?php echo $rows['Email'];?></p>
    <p>Access  : Normal Client</p>
    <p>Age     : <?php echo $rows['Age'];?></p>
    <a href="http://localhost/bookstore/account/me_edit" class="btn btn-primary">Edit</a>
</div>
<?php }elseif (!isset($_SESSION['profile'])){ die("Something is Working badly.."); }?>
