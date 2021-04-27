<?php
#Start the session
session_start();

#Check if the user is already logged in, if yes then redirect him to the dashboard
if(!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== true)
{
    header("Location: signin.php");
    exit;
}
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    require_once "config.php";
    require_once "head.php";
    $sql = "SELECT * FROM courses WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":id", $param_id);
        $param_id = trim($_GET["id"]);
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $name = $row["name"];
            } else{
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Sorry! Something went wrong. Please retry later.";
            
        }
    }
    unset($stmt);
    unset($pdo);
} else{
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>View course</h1>
    <label>Course name</label>
    <p><?php echo $row["name"]; ?></p>
    <p><a href="dashboard.php">Back</a></p>
</body>
</html>