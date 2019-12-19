<?php
require_once "config.php";
session_start();

$subject = $difficulty = $marks = $timing = $question = $uploaded_by = "";
$subject_err = $difficulty_err = $marks_err = $timing_err = $question_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty($_POST["subject"])){
        $subject_err = "Please choose a subject.";
    }
    else{
        $subject = $_POST["subject"];
    }
    
    if(empty($_POST["difficulty"])){
        $difficulty_err = "Please choose a difficulty.";     
    }
    else{
        $difficulty = $_POST["difficulty"];
    }

    if(empty(trim($_POST["marks"]))){
        $marks_err = "Please enter corresponding marks.";     
    }
    else{
        $marks = trim($_POST["marks"]);
    }
    
    if(empty(trim($_POST["timing"]))){
        $timing_err = "Please enter corresponding timing in minutes.";     
    }
    else{
        $timing = trim($_POST["timing"]);
    }

    if(empty(trim($_POST["question"]))){
        $question_err = "Please enter a question.";
    } else{
        $sql = "SELECT subject FROM questions WHERE question = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_question);
            
            $param_question = trim($_POST["question"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $question_err = "This question already exists.";
                } else{
                    $question = trim($_POST["question"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }

    if(empty($subject_err) && empty($difficulty_err) && empty($marks_err) && empty($timing_err) && empty($question_err)){
        
        $sql = "INSERT INTO questions (subject,difficulty,marks,timing,question,uploaded_by) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssiiss", $param_subject, $param_difficulty, $param_marks, $param_timing, $param_question, $param_uploaded_by);
            
            $param_subject = $subject;
            $param_difficulty = $difficulty;
            $param_marks = $marks;
            $param_timing = $timing;
            $param_question = $question;
            $param_uploaded_by = $_SESSION['username'];
            if(mysqli_stmt_execute($stmt)){
                header("location: add-question.php");
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
    <title>Add New Question</title>
    <link rel="stylesheet" href="newcss.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper" align="center">
        <h2>ADD A NEW QUESTION</h2>
        <br><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($subject_err)) ? 'has-error' : ''; ?>">
                <?php
                require_once "config.php";
                $sql = "SELECT subject_code, subject_name FROM topics GROUP BY subject_code;";
                $result = mysqli_query($link,$sql);
                    echo '<label>Subject: ';
                    echo '<select subject="subject_name" name="subject">';
                    echo '<option value="">Select a subject.</option>';

                    $num_results = mysqli_num_rows($result);
                    for ($i=0;$i<$num_results;$i++) {
                        $row = mysqli_fetch_array($result);
                        $q1 = $row['subject_name'];
                        $q2 = $row['subject_code'];
                        $q3 = "  -  ";
                        $subject = $q2.$q3.$q1;
                        echo '<option value="' .$q1. '">' .$subject. '</option>';
                    }

                    echo '</select>';
                    echo '</label>';

                mysqli_close($link); ?>
                <span class="help-block"><?php echo $subject_err; ?></span>
            </div>    

            <div class="form-group <?php echo (!empty($difficulty_err)) ? 'has-error' : ''; ?>">
                <label>Difficulty:</label>
                <input type="radio" name="difficulty" class="form-control" id = "case1" value="easy"> Easy
                <input type="radio" name="difficulty" class="form-control" id = "case2" value="moderate"> Moderate
                <input type="radio" name="difficulty" class="form-control" id = "case3" value="hard"> Hard
                <span class="help-block"><?php echo $difficulty_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($marks)) ? 'has-error' : ''; ?>">
                <label>Marks:</label>
                <input type="number" name="marks" class="form-control" value="<?php echo $marks; ?>">
                <span class="help-block"><?php echo $marks_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($timing)) ? 'has-error' : ''; ?>">
                <label>Timing (in minutes):</label>
                <input type="number" name="timing" class="form-control" value="<?php echo $timing; ?>">
                <span class="help-block"><?php echo $timing_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($question)) ? 'has-error' : ''; ?>">
                <label>Question:</label>
                <input type="text" name="question" class="form-control" value="<?php echo $question; ?>">
                <span class="help-block"><?php echo $question_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p><a href="welcome-teacher.php">Go to home</a></p>
        </form>
    </div>
</body>
</html>