<?php
# code to Start a session
session_start();

# Confirm if the user is logged in and redirect to the login page if this is no true.
if (! isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true)
{
    header('Location: signin.php');
}
 
require_once "head.php";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
</head>

<body>
    <form action="<?php echo htmlspecialchars("signout.php");?>" method="POST">
        <button type="submit" name="signout">Sign out</button>
    </form>
    <a href="index.php" role="button">Home</a>
           
     
    <p>
    <?php 
    if($_SESSION['email'])
    {
        echo "Welcome " . $_SESSION['email'];
    }
    ?>
    </p>
    <p>
        <a href="createCourse.php" role="button">Add course</a>
    </p>
    <h1>courses</h1>


    <?php
    require_once "config.php";
    $sql = "SELECT * FROM courses WHERE user_id = :user_id";
    
    if($stmt = $pdo->prepare($sql))
    {
        $stmt->bindParam(":user_id", $param_user_id);
        $param_user_id = $_SESSION["id"];

        if($stmt->execute())
        {
            if($stmt->rowCount() > 0)
            {
                echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Course name</th>";
                            echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while($row = $stmt->fetch()){
                        echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>";
                                echo "<a href='read.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'</a>";
                                echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip' </a>";
                                echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip' </a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";
                unset($stmt);
            } else{
                echo "<p class='lead'><em>No courses were found.</em></p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
        }
        unset($pdo);
    }
    ?>
</body>
</html>
