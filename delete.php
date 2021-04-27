<?php
// Start the session
session_start();


if(!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== true)
{
    header("Location: signin.php");
    exit;
}
if(isset($_POST["id"]) && !empty($_POST["id"])){
    require_once "config.php";
    require_once "head.php";

    $sql = "DELETE FROM courses WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":id", $param_id);
        $param_id = trim($_POST["id"]);
        if($stmt->execute()){
            header("location: dashboard.php");
            exit();
        } else{
            echo "Sorry! Something went wrong. Kindly retry again.";
        }
    }
    unset($stmt);
    unset($pdo);
} else{
    if(empty(trim($_GET["id"]))){
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>

</head>
<body>

    <h1>Delete course</h1>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        
    <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
    <p>Wouldyou like to proceed and delete this course?</p><br>
    <p>
        <input type="submit" value="Yes">
        <a href="dashboard.php">No</a>
    </p>
</body>
</html>