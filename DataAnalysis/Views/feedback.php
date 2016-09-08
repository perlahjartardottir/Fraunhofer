<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];
$user = $_SESSION["username"];
// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

$userIDSql = "SELECT employee_ID
FROM employee
WHERE employee_name = '$user'";
$userID = mysqli_fetch_row(mysqli_query($link, $userIDSql))[0];

?>
<head>
	<title>Data Analysis</title>
</head>
<body>
	<?php include '../header.php';?>
	<div class='container'>
		<div id='success_message'></div>
		<div class='row well well-lg'>
			<!-- 			<h2 class='custom_heading center_heading'>Feedback</h2> -->
			<h5>This form is for leaving feedback for the data analysis part of the website.</h5>
			<h5>Only the description field is required, but feel free to fill in the other fields if it's relevant.</h5>
<!-- 		</div> -->
<!-- 		<div class='row well well-lg'> -->
			<form id='feedback_form' role='form'>
				<div id='error_message'></div>
				<input type='hidden' id='feedback_user' value='<?php echo $userID; ?>'>
				<input type='hidden' id='feedback_date' value='<?php echo date("Y-m-d"); ?>'>
				<div class='form-group row'>
					<label class='col-xs-2 col-form-label'>Location:</label>
					<div class='col-md-6'>
						<input type="text" id='feedback_location' class="form-control">
						<span class='help-block'>Where on the site is the error? You can paste the url if you wish.</span>
					</div>
				</div>
				<div class='form-group row'>
					<label class='col-xs-2 col-form-label'>Sample:</label>
					<div class='col-md-6'>
						<input type="text" id='feedback_sample' class="form-control">
						<span class='help-block'>Does it involve particular samples or sets?</span>
					</div>
				</div>
				<div class='form-group row'>
					<label class='col-xs-2 col-form-label'>Description:*</label>
					<div class='col-md-6'>
						<textarea id='feedback_description' class='form-control' rows='4'></textarea>
						<span class='help-block'></span>
					</div>
				</div>
				<div class='col-md-12'>
				<button type='button' class='btn btn-primary col-md-2' style='float:right;' onclick='addFeedback(this.form)'>Submit</button>
				</div>
			</form>
		</div>
		<div id='all_feedback'></div>
	</div>
	<script>

	// Refreshes the page automatically after 2 minutes if the user is inactive.
    var idleTime = 0;
    $(document).ready(function () {

    	$("#nav_comment").button('toggle');
		showFeedback();

        //Increment the idle time counter every minute.
        var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

        //Zero the idle timer on mouse movement.
        $(this).mousemove(function (e) {
            idleTime = 0;
        });
        $(this).keypress(function (e) {
            idleTime = 0;
        });
    });
    function timerIncrement() {
        idleTime = idleTime + 1;
        if(idleTime > 2) {
            window.location.reload();
        }
    }
	</script>
