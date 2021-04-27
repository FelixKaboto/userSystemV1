<?php
# Start a session
session_start();

# Check if the user is already logged in, if yes then redirect him to the dashboard
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true)
{
    header("Location: dashboard.php");
    exit;
}

# Include code from the config, head and validation PHP files
require_once 'config.php';
require_once 'head.php';
require_once 'validation.php';

$email = $password = "";
$email_err = $password_err = "";

if (isset($_POST['submitted']))
{
    $email = test_input($_POST["email"]);

    if (empty($_POST["email"])) {
        $email_err = "* Email is required";
    } else {
    $email = test_input($_POST["email"]);
    }
    
    if (empty($_POST["password"])) {
    $password_err = "* Password is required";
    } else {
    $password = md5(test_input($_POST["password"]));
    }

    if(empty($email_err) && empty($password_err))
    {  

        $sql = "SELECT * FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql))
        {
            $stmt->bindParam(":email", $param_email);
            $param_email = $email;

            if($stmt->execute())
            { 
                if($stmt->rowCount() == 1)
                {
                    if($row = $stmt->fetch())
                    {
                        $id = $row["id"];
                        $first_name = $row["first_name"];
                        $last_name = $row["last_name"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        
                        if($password == $hashed_password)

                            session_start();

                            $_SESSION["loggedIn"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["first_name"] = $first_name;
                            $_SESSION["last_name"] = $last_name;
                            $_SESSION["email"] = $email;

                            header("Location: dashboard.php");
                        } else{
                            echo "Provided email or password is incorrect.";
                        }
                    } 
                } else{
                    echo "Provided email or password is incorrect.";
                }
            } else{
                echo "Sorry, something went wrong. Please retry later.";
            }
            unset($stmt);
        }
    }
    unset($pdo); 
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in page</title>
</head>

<body>
    <h1>User login</h1>
    <p><a href="index.php" role="button">Home</a></p>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Email">
            <span><?php echo $email_err;?></span>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password">
            <span><?php echo $password_err;?></span>
            <a href="passwordReset.php" >Password reset</a>
            <button type="submit" name="submitted">Sign in</button>
        </form>
</body>  
</html>