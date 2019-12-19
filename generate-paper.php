<?php
require_once "config.php";
session_start();

$subject = $marks = $timing = "";
$subject_err = $marks_err = $timing_err  = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST")
{
 
    if(empty($_POST["subject"])){
        $subject_err = "Please choose a subject.";
    }
    else{
        $subject = $_POST["subject"];
    }

    if(empty(trim($_POST["marks"]))){
        $marks_err = "Please enter maximum marks.";     
    }
    else{
        $marks = trim($_POST["marks"]);
    }
    
    if(empty(trim($_POST["timing"]))){
        $timing_err = "Please enter duration of exam in minutes.";     
    }
    else{
        $timing = trim($_POST["timing"]);
    }

    if(empty($subject_err) && empty($marks_err) && empty($timing_err))
    {

        $sql = "SELECT * FROM questions WHERE subject = '$subject' ORDER BY RAND()";
        $result = mysqli_query($link, $sql);
        $num_results = mysqli_num_rows($result);
        $data = [];

        for ($i=0; $i < $num_results; $i++) 
        {
            $data[] = mysqli_fetch_array($result);
        }

        $total_marks = 0;
        $total_minutes = 0;
        $count=0;
        $row_index = 0;
        $selected_questions = [];
        $suitable_set_found = false;

        foreach ($data as $row) 
        {
            $row_index++;
            if ($total_marks + $row['marks'] > $marks || $total_minutes + $row['timing'] > $timing) 
            {
                continue;
            }
            $q="  \t\t\t (";
            $qq=")";
            $selected_questions[$row_index] = $row['question'].$q.$row['marks'].$qq;

            $total_marks += $row['marks'];
            $count++;

            if ($total_marks == $marks) 
            {

                    $paper = fopen('D:\xampp\htdocs\newpaper.rtf', 'w') or die('Unable to open file!');
                    $text = "\t\t\t\tGENERATED QUESTION PAPER\r\n\r\n\t\t\t\t   $subject\r\n\r\nMaximum Marks : $marks \t \t \t \t \t Maximum Time : $timing minutes \r\nTotal questions : $count \r\n \r\n ";
                    fwrite($paper, $text);
                    $count=1;
                    foreach ($selected_questions as $selected_question) 
                    {
                        $text = "\r\n Q $count) $selected_question \r\n";
                        fwrite($paper, $text);
                        $count++;
                    }

                    fclose($paper);
                    break;
            }
            //else 
            //{
            //    echo "Oops! Something went wrong. Please try again later.";
            //}
        }
    mysqli_close($link);
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Question Paper</title>
    <link rel="stylesheet" href="newcss.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper" align="center">
        <h2><b>GENERATE A QUESTION PAPER</b></h2>
        <br><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($subject_err)) ? 'has-error' : ''; ?>">
                <?php
                require "config.php";
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

            <div class="form-group <?php echo (!empty($timing)) ? 'has-error' : ''; ?>">
                <label>Duration of the Exam (in minutes):</label>
                <input type="number" min="15" name="timing" class="form-control" value="<?php echo $timing; ?>">
                <span class="help-block"><?php echo $timing_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($marks)) ? 'has-error' : ''; ?>">
                <label>Maximum Marks:</label>
                <input type="number" name="marks" min="10" class="form-control" value="<?php echo $marks; ?>">
                <span class="help-block"><?php echo $marks_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Generate Question Paper">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p><a href="welcome-teacher.php">Go to home</a></p>
        </form>
    </div>
</body>
</html>