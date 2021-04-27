<?php
# Include code from the config, validation and head PHP files
require_once "config.php";
require_once "validation.php";
require_once "head.php";
 

$first_name = $last_name = $email = $password = "";
$first_name_err = $last_name_err = $email_err = $password_err = "";
 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $email = test_input($_POST["email"]);
  
  if (empty($_POST["first_name"])) {
    $first_name_err = "* First name is required";
  } elseif (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
    $first_name_err = "Only letters and white space allowed";
  }
  else {
    $first_name = test_input($_POST["first_name"]);
  }

  if (empty($_POST["last_name"])) {
    $last_name_err = "* Last name is required";
  } elseif (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
    $last_name_err = "Only letters and white space allowed";
  }
  else {
    $last_name = test_input($_POST["last_name"]);
  }
  if (empty($_POST["email"])) {
    $email_err = "* Email is required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_err = "Invalid email format";
  }
  else {
    $sql = "SELECT id FROM users WHERE email = :email";
    if($stmt = $pdo->prepare($sql))
    {
      $stmt->bindParam(":email", $param_email);
      $param_email = $email;

      if($stmt->execute())
      {
        if($stmt->rowCount() == 1)
        {
          $email_err = "Email enetered already exists.";
        } else{
          $email = $email;
        }
      }
    } else{
      echo "Sorry, something went wrong. Please retry again.";
    }

    unset($stmt);
  }
  if (empty($_POST["password"])) {
    $password_err = "* Password is required";
  } else {
    $password = test_input($_POST["password"]);
  }

  # Verifying that data has no errors before appending to database
  if(empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err))
  {
    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";

    if($stmt = $pdo->prepare($sql))
    {
      $stmt->bindParam(":first_name", $param_first_name);
      $stmt->bindParam(":last_name", $param_last_name);
      $stmt->bindParam(":email", $param_email);
      $stmt->bindParam(":password", $param_password);
      $param_first_name = $first_name;
      $param_last_name = $last_name;
      $param_email = $email;
      $param_password = md5($password);
      if($stmt->execute()){
          echo "User created successfully.";
          exit();
      } else{
          echo "Something went wrong. Kindly retry again later.";
      }
  }
  unset($stmt);
  }
unset($pdo);
}
?>
 
<body>
    <h1>signup Form</h1>
    <a href="index.php" role="button">Home</a>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <label for="first_name">First name</label>
        <input type="text" id="first_name" name="first_name" placeholder="First name">
        <span ><?php echo $first_name_err;?></span>
        <label for="last_name">Last name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Last name">
        <span><?php echo $last_name_err;?></span>
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" placeholder="Email">
        <span><?php echo $email_err;?></span>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password">
        <span><?php echo $password_err;?></span>
        <button type="submit" name="submit">Create</button>
    </form>

</body>