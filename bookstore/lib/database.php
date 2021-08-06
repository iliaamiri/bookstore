<?php
/**
 * Created by PhpStorm.
 * User: pcs
 * Date: 21/03/2018
 * Time: 03:57 PM
 */

class database
{
    private $hostname = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "bookstore";
    /* preg_match patterns */
    private $Table_pattern = "/^[a-zA-Z0-9_]*$/i";
    private $Database_pattern = "/^[a-zA-Z0-9_]*$/i";
    private $Columns_pattern = "/^[a-zA-Z0-9,()\s]*$/i";
    public static $conn = NULL;
    function __construct()
    {
        try{
            self::$conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return self::$conn;
        }catch (PDOException $e){
            die($e);
        }
    }
    private static function alert($message){
        echo '<script>alert("'.$message.'")</script>';
    }
    public function AddDatabase($DATABASENAME){
        if (preg_match($this->Database_pattern,$DATABASENAME)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $AddDB = $conn->prepare("CREATE DATABASE $DATABASENAME");
                $AddDB->execute();
                self::alert("The ('.$DATABASENAME.') Is Created Now.");
                header("refresh:0");
            }catch (PDOException $e){
                echo "this database does exist! do you want to delete it?<form method='post'><input type='submit' name='no' value='no'><input type='submit' name='yes' value='yes'></form>";
                if (isset($_POST['yes'])){
                    $this->DeleteDatabase($DATABASENAME);
                }elseif (isset($_POST['no'])){
                    self::alert("Ok but please try another database after this.");
                    //header("refresh:0");
                }
            }
        }else{
            die("invalid");
        }
    }
    public function DeleteDatabase($DATABASENAMEFORDELETE){
        if (preg_match($this->Database_pattern,$DATABASENAMEFORDELETE)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $DeleteDB = $conn->prepare("DROP DATABASE $DATABASENAMEFORDELETE");
                $DeleteDB->execute();
                self::alert("This Database ('.$DATABASENAMEFORDELETE.') Is Deleted Now.");
                //header("refresh:0");
            }catch (PDOException $e){
                self::alert("this database even does not exist to delete!");
                //header("refresh:0");
            }
        }else{
            die("invalid");
        }
    }
    public function Addtable($TableNAME,$columns){
        if (preg_match($this->Table_pattern,$TableNAME) AND preg_match($this->Columns_pattern,$columns)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $AddTable = $conn->prepare("CREATE TABLE $TableNAME($columns)");
                $AddTable->execute();
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("invalid");
        }
    }
    public function Deletetable($TableNAMEFORDELETE){
        if (preg_match($this->Table_pattern,$TableNAMEFORDELETE)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $DropTable = $conn->prepare("DROP TABLE $TableNAMEFORDELETE");
                $DropTable->execute();
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
    public function AddColumn($TableNAMEAC,$NEWCOLUMNS){
        if (preg_match($this->Table_pattern,$TableNAMEAC) AND preg_match($this->Columns_pattern,$NEWCOLUMNS)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $AddColumn = $conn->prepare("ALTER TABLE $TableNAMEAC ADD $NEWCOLUMNS");
                $AddColumn->execute();
                self::alert("This Column Has Been Added Now");
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
    public function DeleteColumn($TableNAMEDC,$DELETECOLUMNS){
        if (preg_match($this->Table_pattern,$TableNAMEDC) AND preg_match($this->Columns_pattern,$DELETECOLUMNS)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $AddColumn = $conn->prepare("ALTER TABLE $TableNAMEDC DROP $DELETECOLUMNS");
                $AddColumn->execute();
                self::alert("This Column Has Been Deleted Now");
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
    public function InsertInto($Table,$Columns,$Values){
        if (preg_match($this->Table_pattern,$Table) AND preg_match($this->Columns_pattern,$Columns)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $InsertInto = $conn->prepare("INSERT INTO $Table ($Columns) VALUE ($Values)");
                $InsertInto->execute();
                self::alert("Inserted Successfully");
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
    public function UpdateARow($Table,$Columns,$Statements){
        if (preg_match($this->Table_pattern,$Table) AND preg_match($this->Columns_pattern,$Columns)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $UpdateARow = $conn->prepare("UPDATE $Table SET $Columns $Statements");
                $UpdateARow->execute();
                self::alert("Updated Successfully");
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
    public function SelectFrom($Table,$Columns,$Statements){
        if (preg_match($this->Table_pattern,$Table) AND preg_match($this->Columns_pattern,$Columns)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $UpdateARow = $conn->prepare("SELECT $Columns FROM $Table $Statements");
                $UpdateARow->execute();
                self::alert("Updated Successfully");
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
    public function DeleteFrom($Table,$Statements){
        if (preg_match($this->Table_pattern,$Table)){
            try{
                $conn = new PDO("mysql:host=$this->hostname;dbname=$this->db",$this->user,$this->pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $UpdateARow = $conn->prepare("DELETE FROM $Table $Statements");
                $UpdateARow->execute();
                self::alert("Deleted Successfully");
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid");
        }
    }
}
//$columns = array('LastName varchar(255)','FirstName varchar(255)');
$database = new database();
//$database->Addtable("table2","LastName varchar(255),FirstName varchar(255)");
//$database->Deletetable('table2');
//$database->AddDatabase('example');
//$database->DeleteDatabase('example');
//$database->DeleteColumn("table2","LastName");