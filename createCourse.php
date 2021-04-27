<?php
# Starting a session
session_start();

# Check if the user is already logged in, if yes then redirect him to the dashboard
if (!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== true) {
    header("Location: signin.php");
    exit;
}

require_once "config.php";
require_once "validation.php";
require_once "head.php";

$course_name = $course_name_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["course_name"])) {
        $course_name_err = "* course name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $course_name)) {
        $course_name_err = "Only letters and white space allowed";
    } else {
        $course_name = test_input($_POST["course_name"]);


        $sql = "SELECT id FROM courses WHERE name = :name AND user_id = :user_id";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":user_id", $param_user_id);

            $param_name = $course_name;
            $param_user_id = $_SESSION['id'];

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $course_name_err = "This course already exists.";
                } else {
                    $course_name = $course_name;
                }
            }
        } else {
            echo "Sorry, something went wrong. Please retry again later.";
        }

        unset($stmt);
    }

    if (empty($course_name_err)) {
        $sql = "INSERT INTO courses (name, user_id) VALUES (:name, :user_id)";

        if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(":name", $param_course_name);
            $stmt->bindParam(":user_id", $param_user_id);

            $param_course_name = $course_name;
            $para_user_id = $_SESSION["id"];


            if ($stmt->execute()) {
                echo "Course created successfully.";
                header("location: dashboard.php");
                exit();
            } else {
                echo "Sorry, something went wrong. Please retry again later.";
            }
        }

        unset($stmt);
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
    <title>createCourse</title>
</head>

<body>
    <h1>Create course</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="first_name">Course name</label>
        <input type="text" id="course_name" name="course_name" placeholder="Course name">
        <span><?php echo $course_name_err; ?></span>
        <button type="submit" name="submit">Add</button>
        <a href="dashboard.php" role="button">Back</a>
    </form>
</body>

</html>