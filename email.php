<? php
$name= $_POST['name'];
$visitor_name=$_POST['email'];
$message=$_POST['message'];

$email_from= 'QuestionPaperGenerator.com'

$email_subject="New Form Submission"

$email_body= "User Name: $name.\n".
				"User Email: $visitor_email.\n".
					"User Message: $message.\n";
					
$to: "17bce001@nirmauni.ac.in";	
$headers = "From: $email_from\r\n";
$headers .="Reply To-: $visitor_email\r\n";
mail($to,$email_subject,$email_body,$headers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Sent</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Your query was successfully sent.</h1>
    </div>
    <br><br><br>
    <p>
    	<a href="welcome-teacher.php" class="btn btn-link">Go to Home</a><br><br>
    </p>
</body>
</html>