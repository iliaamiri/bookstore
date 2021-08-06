<?php
if (isset($_GET['d'])){
    if ($_GET['d']  == 'books'){
        if ($_SERVER['REQUEST_URI'] != '/bookstore/admin/AddBook'){
            die("404 - Not Found");
        }
        else{
            $book = new book();
                    $cookie_id = explode("-",$_COOKIE['id']);
                    if ($cookie_id['0'] == $_SESSION['degree']){
                        if (isset($_SESSION['user'])){
                            $_SESSION['books'] = md5(rand(100,2000));
                            if (isset($_POST['Add'])){
                                $book->AddBook($_POST['Title'],$_POST['Price'],$_POST['Brief'],$_POST['Age'],$_POST['Off']);
                                alert("Your New Book Is Added Successfully!, We Will Check It And Tell You More. GOOD LUCK!");
                                redirect('0',"http://localhost/bookstore/admin");
                                exit();
                            }
                        }
                        elseif (!isset($_SESSION['user'])){
                            header("location:localhost/bookstore/404.php");
                        }
                    }
                    else{
                        unset($_SESSION['degree']);
                        CookieDelete("id");
                        header("location:http://localhost/bookstore/404.php");
                    }
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
<?php if (isset($_SESSION['books']) AND isset($_SESSION['Certificated']) AND $_SESSION['Certificated'] === md5($_SESSION['user'])){?>
    <div class="col-md-4">
        <form method="post">
        <input type="text" name="Title" class="input" placeholder="Title" required>
        <input type="text" name="Age" class="input" placeholder="Age" required>
        <input type="text" name="Off" class="input" placeholder="Off" required>
        <input type="text" name="Brief" class="input" placeholder="Brief" required>
        <input type="text" name="Price" class="input" placeholder="Price" required>
        <input type="submit" name="Add" class="submit" value="Add This Book" required>
        </form>
    </div>
<?php }elseif (!isset($_SESSION['books']) OR !isset($_SESSION['Certificated']) OR $_SESSION['Certificated'] !== md5($_SESSION['user'])){ die("Something is Working badly.."); }?>

