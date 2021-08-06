<?php
if (isset($_GET['f'])){
    if ($_GET['d']  == 'users'){
            if (isset($_SESSION['degree'])){
                if (isset($_COOKIE['id'])){
                    $cookie_id = explode("-",$_COOKIE['id']);
                    if ($cookie_id['0'] == $_SESSION['degree']){
                        if (isset($_SESSION['user'])){
                            $_SESSION['users'] = $_SESSION['user'];
                            $msg = "";
                            $_SESSION['msg'] = $msg;
                            try{
                                $conn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $sql = $conn->prepare("SELECT * FROM user_tbl WHERE Status != '2'");
                                $sql->execute();
                                $count = $sql->rowCount();
                                if ($count === 0){
                                    $_SESSION['msg'] = "Nothing found in user tables";
                                }
                            }
                            catch (PDOException $e){
                                die($e);
                            }
                        }
                        elseif (!isset($_SESSION['user'])){
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
    else{
        die("404 - Not Found");
        //header("location:http://localhost/bookstore/404.php");
    }
}
elseif (!isset($_GET['f'])){
    die("404 - Not Found");
    //header("location:http://localhost/bookstore/404.php");
}
?>
<?php if (isset($_SESSION['users'])){?>
    <form method="post">
        <?php
        if (isset($_GET['action'])){
            if (isset($_GET['id'])){
                if (preg_match('/^[0-9]*$/',$_GET['id'])){
                    $_SESSION['id'] = $_GET['id'];
                    if ($_GET['action'] === "edit"){
                        include "edit.php";
                    }
                    elseif ($_GET['action'] === "delete"){
                        echo '<b style="font-size: 25px;">Do You Want To delete This User ??? with id = ('.$_SESSION['id'].')</b>';
                        include "delete.php";
                    }
                }
                else{
                    die("404 - Not Found");
                }
            }
            elseif (!isset($_GET['id'])){
                //header("location:http://localhost/bookstore/404.php");
                die("404 - Not Found");
            }
        }
        ?>
        <table border="5">
            <thead>
            <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Age</th>
            <th>Permissions</th>
            <th>Log</th>
            <th>Edit</th>
            <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)){?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['Username'];?></td>
                <td><?php echo $row['Email'];?></td>
                <td><?php echo $row['Age'];?></td>
                <td><?php
                    $perm = "";
                        if ($row['Status'] === '1'){
                            $perm = "Client";
                        }
                        elseif ($row['Status'] === '2'){
                            $perm = "Administrator";
                        }
                        elseif ($row['Status'] === '3'){
                            $perm = "Co-Admin";
                        }
                        elseif ($row['Status'] === '202'){
                            $perm = "Banned-202";
                        }
                        elseif ($row['Status'] === '303'){
                            $perm = "Banned-303";
                        }
                        elseif ($row['Status'] === '404'){
                            $perm = "Banned By Admin";
                        }
                        echo $perm;
                    ?></td>
                <td>
                    <?php
                        if ($row['Log'] === '0'){
                            echo 'NOT LOGIN</p?';
                        }
                        elseif ($row['Log'] === '1'){
                            echo 'LOGGED IN';
                        }
                    ?>
                </td>
                <td><a href="http://localhost/bookstore/admin/index.php?d=users&f=index&action=edit&id=<?php echo $row['id'];?>">Edit</a></td>
                <td><a href="http://localhost/bookstore/admin/index.php?d=users&f=index&action=delete&id=<?php echo $row['id'];?>">Delete</a></td>
            </tr>
            <?php }?>
            </tbody>
        </table>
    </form>
<?php }elseif (!isset($_SESSION['users'])){die("404 - Not Found");}?>