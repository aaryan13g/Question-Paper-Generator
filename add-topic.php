<?php
require_once "config.php";

$subject_code = $subject_name = "";
$subjectcode_err = $subjectname_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["subject_code"]))){
        $subjectcode_err = "Please enter a subject code.";
    } else{
        $sql = "SELECT sr_no FROM topics WHERE subject_code = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_subject_code);
            
            $param_subject_code = trim($_POST["subject_code"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $subjectcode_err = "This subject already exists.";
                } else{
                    $subject_code = trim($_POST["subject_code"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    if(empty(trim($_POST["subject_name"]))){
        $subjectname_err = "Please enter a subject name.";     
    } 
    else{
        $subject_name = trim($_POST["subject_name"]);
    }
    
    if(empty($subjectcode_err) && empty($subjectname_err)){
        
        $sql = "INSERT INTO topics (subject_code,subject_name) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_subject_code, $param_subject_name);
            
            $param_subject_code = $subject_code;
            $param_subject_name = $subject_name; 
            if(mysqli_stmt_execute($stmt)){
                header("location: welcome-admin.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }  
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Topic</title>
    <link rel="stylesheet" href="newcss.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>ADD A NEW TOPIC</h2>
        <br><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($subjectcode_err)) ? 'has-error' : ''; ?>">
                <label>Subject Code</label>
                <input type="text" name="subject_code" class="form-control" value="<?php echo $subject_code; ?>">
                <span class="help-block"><?php echo $subjectcode_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($subjectname_err)) ? 'has-error' : ''; ?>">
                <label>Subject Name</label>
                <input type="text" name="subject_name" class="form-control" value="<?php echo $subject_name; ?>">
                <span class="help-block"><?php echo $subjectname_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p><a href="welcome-admin.php">Go to home</a></p>
        </form>
    </div>
</body>
</html>