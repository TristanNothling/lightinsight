<?php 
if (isset($_SESSION['token']) && isset($_SESSION['token_id'])) {

	/*if session hasn't expired*/
	$now = new DateTime(date("Y/m/d H:i:s", strtotime("now")));
	$expiry = new DateTime(date("Y/m/d H:i:s", $_SESSION['expires']));

	if ($now > $expiry){
		header('Location: login.php?reason=3'); 
	}
	else{
		header('Location: dashboard.php'); 
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Oxygen|Exo&display=swap" rel="stylesheet">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
	<link rel="manifest" href="site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#603cba">
	<meta name="theme-color" content="#ffffff">

	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

	<title>Light Insight | Login</title>

	<style>

		img{
			user-drag: none; 
			user-select: none;
			-moz-user-select: none;
			-webkit-user-drag: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}

		body{
			margin:0;
			overflow:hidden;
		}

		h1{
		font-family: 'Oxygen', sans-serif;
		margin-bottom:0.2em;
		}

		h1.alt{
		font-family: 'Exo', sans-serif;
		}

		#surrounding_container{
			width:100vw;
			white-space: nowrap;
			height:100vh;
		}

		#login_section{
			width:38%;
			height:100%;
			background:white;
			display:inline-block;
			position: relative;
		}

		#right_section{
			width: 62%;
    		height: 100%;
    		background-image: 
    		linear-gradient(180deg, rgba(33, 26, 18, 0.85) 0%, rgba(31, 28, 111, 0.73) 100%),
    		url('images/wave.jpg');
    		display: inline-block;
    		background-size: cover;
		}


		#welcome_text{
			position: absolute;
			top:33%;
			padding-left:120px;
			animation: fadein 2s;
    		-moz-animation: fadein 2s; /* Firefox */
    		-webkit-animation: fadein 2s; /* Safari and Chrome */
    		-o-animation: fadein 2s; /* Opera */
		}

		.gold_text {
			color:white;
			font-size: calc(30px + 2.8vw);
			letter-spacing: -0.02em;

		}

		.white_text {
			margin: 16px 0px 16px 0px;
			color:white;
			font-family: 'Oxygen', sans-serif;
			font-size:23px;
		}

		#login_form {
			text-align: center;
			bottom: 18%;
	    	width: 74%;
	    	left: 13%;
	    	height: 36%;
	    	position: absolute;
	   		background: #fff;
		}

		input:focus,
		select:focus,
		textarea:focus,
		button:focus {
		    outline: none;
		}

		input{
			box-sizing: border-box;
			font-family: 'Oxygen', sans-serif;
		}

		#login_form input
		{
			display:block;
			width:calc(80% - 21px);
			margin: 32px 10% 32px 10%;
			height:56px;
			padding-left:21px;
			border:0;
			font-size:21px;
			border-bottom: 2px solid #eee;
			transition: all 0.3s cubic-bezier(0.77, 0, 0.175, 1);
		}

		#login_form .error{
			border-bottom: 2px solid rgb(222, 121, 121);
		}

		#login_form input:focus
		{
			border-bottom: 2px solid #7482FF !important;
		}

		#login_form form
		{
			width:100%;
			height:50%;
		}

		#submit_container{
			display: block;
    		width: calc(80% - 21px);
    		margin: 47px 10% 32px 10%;
    		height: 56px;
		}

		#result{
			font-family: 'Oxygen', sans-serif;
			font-size:18px;
		}

		.button{
			font-family: 'Oxygen', sans-serif;
			-webkit-appearance:none;
			height:56px;
			border:0;
			outline:none;
			cursor: pointer;
			transition: all 0.16s cubic-bezier(0.77, 0, 0.175, 1);
			font-size:18px;
			border-radius: 40px;
		    font-weight: 600;
			letter-spacing: 0.2em;
			padding:0;

		}

		.secondary_button:hover{
		    box-shadow: 0px 0px 8px -2px #C67DFF;
		    color:#C67DFF;

		}
		.primary_button:hover{
			box-shadow: 0px 0px 8px -2px #C67DFF;
    		background-color: #C67DFF;
		}

		.primary_button{
		    width: 48%;
		    margin: 0;
		  	background-color: #7482FF;
		    color: white;
		}

		.secondary_button{
			width:48%;
			margin:0;
			box-shadow: 0px 0px 8px -3px #7482FF;
			background-color: white;	
			color: #7482FF;
		}


		@media only screen and (max-width: 980px) {
			body{background:white;}
			#login_form {bottom:10%;}
			#login_section{width:100%;height:90vh;}
			#login_form {width:80%;left:10%;}
			#right_section{display: none;}

		}

		.loader2 {
			position: absolute;
		    height: 120px;
		    width: 100%;
		    padding: 0;
		    text-align:center;
		    margin: 0;
		    display: inline-block;
		    top:calc(50% - 60px);
		    z-index: 500;
		}

		#result{
			transition: all 0.16s cubic-bezier(0.77, 0, 0.175, 1);
		}

		#result img{
			width:32px;
			position: relative;
			top: -12px;
		}


		@keyframes fadein {
		    from {
		    	top:30%;
		        opacity:0;
		    }
		    to {
		    	top:33%;
		        opacity:1;
		    }
		}
		@-moz-keyframes fadein { /* Firefox */
		    from {
		    	top:30%;
		        opacity:0;
		    }
		    to {
		    	top:33%;
		        opacity:1;
		    }
		}
		@-webkit-keyframes fadein { /* Safari and Chrome */
		    from {
		    	top:30%;
		        opacity:0;
		    }
		    to {
		    	top:33%;
		        opacity:1;
		    }
		}
		@-o-keyframes fadein { /* Opera */
		    from {
		    	top:30%;
		        opacity:0;
		    }
		    to {
		    	top:33%;
		        opacity:1;
		    }
		}


	</style>

</head>

<body>
<div id="surrounding_container">
	<div id="login_section">

		<div class="loader2 loader--style2" style="display:none;">
			<img src="purple_loader.svg">
		</div>
		
		<div id="logo">
			<img style="width:24%;left:38%;top:16%;position: absolute;" src="logo-text.svg">
		</div>

		

		<div id="login_form" >

			<span id="result" style="opacity:0;"><img src="images/warning.svg"><br>Nothing to see here.</span>
			
			<form id="login_form_inner" action="api/login.php" method="POST">
				<input id="email_input" type="text" placeholder="Your email" name="email">
				<input id="password_input" type="password" placeholder="Your password" name="password">

				<div id="submit_container">
					<button style="margin-right:2%;" class="button primary_button" type="submit" name="login" value="Login">Login</button>
					<button style="margin-left:2%;" class="button secondary_button" type="submit" name="register" value="Register">Register</button>
				</div>
			</form>

		</div>

	</div>

	<div id="right_section">
		
		<div id="welcome_text">
			<h1 class="alt gold_text"><?php include 'greeting.php'; ?></h1>
			<span class="white_text">Welcome to the new, interactive way to manage your money.</span>
		</div>

	</div>
</div>
</body>

</html>

<script>

$("#login_form_inner").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    var submit_data = form.serialize(); // serializes the form's elements.

    $('#login_form_inner').trigger("reset");
    $('#result').css('opacity','0');
    $('.loader2').show();

	$.ajax({
		type: "POST",
		url: url,
		data: submit_data,
		success: function(data)
		{
			var result_object = JSON.parse(data);
			
			if (result_object.result === "success")
			{
				/*fade in white overlay*/
				window.location.href = "dashboard.php";
			}
			else
			{
				$('#result').html( '<img src="images/warning.svg"><br> ' + result_object.message);

				$('#email_input').addClass('error');
				$('#password_input').addClass('error');
				//timeout 5 seconds to remove class?

				$('#result').css('opacity','1');
				$('.loader2').hide();
			}
		}
		});
	});

</script>