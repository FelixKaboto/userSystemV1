<?php

require_once "config.php";
require_once 'head.php';
require_once 'validation.php';


$email = $password = '';
$email_err = $password_err = '';

if (isset($_POST['submitted'])) {
    $email = test_input($_POST["email"]);

    if (empty($_POST["email"])) {
        $email_err = "* Email is required";
    } else {
        $sql = "SELECT id FROM users WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(":email", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                if (!$stmt->rowCount() == 1) {
                    $email_err = "This email doesn't exists.";
                } else {
                    $email = $email;
                }
            }
        } else {
            echo "Soery, something went wrong. Please retry again later.";
        }

        unset($stmt);
    };


    if (empty($_POST["password"])) {
        $password_err = "* Password is required";
    } else {
        $password = md5(test_input($_POST["password"]));
    }

    #This code ensure that the details of the user are validated
    if (empty($email_err) && empty($password_err)) {
        $sql = "UPDATE users SET password = :password WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            #Connect the variables and statement using parameters
            $stmt->bindParam(":password", $param_password);
            $stmt->bindParam(":email", $param_email);
            $param_password = $password;
            $param_email = $email;

            #try statement
            if ($stmt->execute()) {
                echo "Password successfully changed.";
            } else {
                echo "Sorry, something went wrong. Please retry again.";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password reset</title>
</head>
<body>

    <h2> Reset Password</h2>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Email">
    <span><?php echo $email_err; ?></span>
    <label for="password">New password</label>
    <input type="password" name="password" id="password" placeholder="New password">
    <span><?php echo $password_err; ?></span>
    <button type="submit" name="submitted">Reset</button>
    <a href="signin.php" role="button">Return</a>

</body>

</html>