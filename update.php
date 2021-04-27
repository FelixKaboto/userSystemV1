<?php
#Start the session
session_start();

#Confrirm user login status, if true then redirect user to the dashboard
if(!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== true)
{
    header("Location: signin.php");
    exit;
}

#Using code from the config and head php files
require_once "config.php";
require_once "head.php";
$name = "";
$name_err = "";
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Kindly enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Kindly enter a valid name.";
    } else{
        $name = $input_name;
    }
    if(empty($name_err))
    {
        $sql = "UPDATE courses SET name=:name WHERE id=:id";
 
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":id", $param_id);
            $param_name = $name;
            $param_id = $id;
            
            if($stmt->execute()){
                header("location: dashboard.php");
                exit();
            } else{
                echo "Sorry soemthing went wrong. Kindly retry later.";
            }
        }
        unset($stmt);
    }
    unset($pdo);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);
        $sql = "SELECT * FROM courses WHERE id = :id";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":id", $param_id);
            $param_id = $id;
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $name = $row["name"];
                } else{
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Sorry! Something went wrong. Kindly retry again later.";
            }
        }

        unset($stmt);     
        unset($pdo);
    }  else{
        
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Record</title>
</head>
<body>
    
    <h2>Change course</h2>
    </div>
    <p>Edit and submit to update the course.</p>
    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
        <div <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label>Course name</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span<?php echo $name_err;?></span>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="submit" value="Submit">
        <a href="dashboard.php">Cancel</a>
    </form>
</body>
</html>