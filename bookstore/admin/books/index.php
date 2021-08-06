<?php
    if ($_SERVER['REQUEST_URI'] != '/bookstore/admin/Books') {
        //die("404 - Not Found");
    } else {
        if (isset($_SESSION['degree']) AND isset($_SESSION['Certificated']) AND isset($_COOKIE['id'])) {
            $cookie_id = explode("-", $_COOKIE['id']);
            if ($cookie_id['0'] == $_SESSION['degree']) {
                if (isset($_SESSION['user'])) {
                    $_SESSION['books'] = md5(rand(100, 2000));
                } elseif (!isset($_SESSION['user'])) {
                    header("location:localhost/bookstore/404.php");
                }
            } else {
                unset($_SESSION['degree']);
                CookieDelete("id");
                header("location:http://localhost/bookstore/404.php");
            }
        } elseif (!isset($_SESSION['degree']) OR !isset($_SESSION['Certificated']) OR !isset($_COOKIE['id'])) {
            session_destroy();
            go("../../404.php");
        }
    }

?>
<?php if (isset($_SESSION['books']) AND isset($_SESSION['Certificated'])){?>
    <?php
    try{
        $coonection = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
        $coonection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $selectbooks = $coonection->prepare("SELECT * FROM books_tbl");
        $selectbooks->execute();
    }
    catch (PDOException $e){
        die("Error".$e);
    }
    ?>
    <table border="2">
        <h1>Not Verified :</h1>
        <thead>
            <th>Id</th>
            <th>Owner</th>
            <th>Title</th>
            <th>Ages</th>
            <th>Brief</th>
            <th>Price $</th>
            <th>Available</th>
            <th>Status</th>
            <th>Product ID</th>
            <th>added Date</th>
            <th>Off</th>
        <th>Apply</th>
        <th>Not Agree</th>
        </thead>
        <?php while ($rows = $selectbooks->fetch(PDO::FETCH_ASSOC)){?>
            <tr>
            <td><?php echo $rows['id'];?></td>
            <td><?php echo $rows['BookOwner'];?></td>
            <td><?php echo $rows['BookName'];?></td>
            <td><?php echo $rows['AgeSort'];?></td>
            <td><?php echo $rows['Brief'];?></td>
            <td><?php echo $rows['Price'];?></td>
            <td><?php echo $rows['Available'];?></td>
            <td><?php echo $rows['Status'];?></td>
            <td><?php echo $rows['Product_ID'];?></td>
            <td><?php echo $rows['AddDate'];?></td>
            <td><?php echo $rows['OFF'];?></td>
                <td><a href="http://localhost/bookstore/admin/books/apply.php?a=yes" class="btn btn-success">I'm Agree</a></td>
                <td><a href="http://localhost/bookstore/admin/books/apply.php?a=no" class="btn btn-danger">I'm Not Agree</a></td>
                <td></td>
            </tr>
        <?php }?>
    </table>
    <table>

    </table>
<?php }elseif (!isset($_SESSION['books']) OR !isset($_SESSION['Certificated'])){die("something is wrong");}?>
