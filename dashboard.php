<?php include 'api/hadfj_connect.php'; 
include 'api/functions.php';

session_start();

if (!isset($_SESSION['token']) || !isset($_SESSION['token_id'])) {
	header('Location: login.php?reason=4'); /*reason = 4 is forbidden*/
}
else
{
	$now = new DateTime(date("Y/m/d H:i:s", strtotime("now")));
	$expiry = new DateTime($_SESSION['expires']);
	if ($now > $expiry) {
		if (isset($_SESSION['token_id'])) {

			$logging_out_token = $_SESSION['token_id'];
			$stmt = $sql_conn->prepare("DELETE FROM asdfz_sessions WHERE sadkp_id = ?");
			$stmt->bind_param("s", $logging_out_token );
			$stmt->execute();
			session_unset();

			header('Location: login.php?reason=3'); 
		}
	}
}

$global_categories = get_categories($sql_conn);

?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Rosario|Rufina&display=swap" rel="stylesheet">
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

	<link rel="stylesheet" type="text/css" href="dp/css/datepicker.min.css">

	<title>Light Insight | Dashboard</title>

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
		font-family: 'Rosario', sans-serif;
		margin-bottom:0.2em;
		}

		span{
		font-family: 'Rosario', sans-serif;
		}

		h1.alt{
		font-family: 'Rufina', serif;
		}

		#surrounding_container{
			width:100vw;
			white-space: nowrap;
			height:100vh;
		}

		#settings_section{
			width: 28%;
		    height: calc(100% - 62px);
		    box-sizing: border-box;
		    border-right: 1px solid #565656;
		    display: inline-block;
		    position: fixed;
		    margin-right: -4px;
		    background: #2f2c3a;
		    z-index: 200000;
		    left: -28%;
		    transition: all 0.24s cubic-bezier(0.77, 0, 0.175, 1);
		}

		.open{
			left:0% !important;
		}

		#transactions_section{
		width: 39%;
    	height: calc(100vh - 62px);
    	display: inline-block;
    	margin-right: -4px;
    	overflow-y: auto;
    	background-color: #f7f7f7;
    	perspective: 600px;
    	position: relative;
    	top: -62px;
		}

		.apexcharts-canvas{
    	top: 10px;
		}

		#graph_section{
			width:61%;
			display:inline-block;
			margin-right:-4px;	
			background-color: #2f2c3a;	
			position: relative;
			height:calc(100vh - 62px);
			top:-62px;
		}

		#insight_section{
			background-color: #f7f7f7;
			width:0%;
			height:100%;
			display:inline-block;
			margin-right:-4px;	
			position: relative;
		}

		#welcome_text{
			position: absolute;
			top:27%;
			padding-left:120px;
		}

		.gold_text {
			color:#E4C7AB;
			font-size: 87px;
			font-weight: 200;
			letter-spacing: -0.02em;
		}

		.white_text {
			margin: 16px 0px 16px 0px;
			color:white;
			font-family: 'Rosario', sans-serif;
			font-size:23px;
		}


		input:focus,
		select:focus,
		textarea:focus,
		button:focus {
		    outline: none;
		}

		input{
			box-sizing: border-box;
		}

		#add_transaction{
			font-family: Oxygen, sans-serif;
		}


		input,select {
			display:inline-block;
			margin:13px 4px 13px 0px;
			padding:7px 7px 7px 14px;
			border-radius: 8px;
			height:37px;
			border:0;
			font-size:18px;
			border: 1px solid #eee;
			transition: all 0.3s cubic-bezier(0.77, 0, 0.175, 1);
		}

		#add_transaction select{
			width:98%;
		}

		input:focus {
		border: 1px solid #6578dc;
		box-shadow: 0 0 10px #eeedff;
		}
		
		#add_transaction select:focus {
		border: 1px solid #6578dc;
		box-shadow: 0 0 10px #eeedff;
		}

		.loader {
			position: absolute;
		    height: 120px;
		    width: 34%;
		    text-align: center;
		    padding: 0;
		    margin: 0;
		    display: inline-block;
		    top:calc(50% - 60px);
		}


		.loader2 {
			position: absolute;
		    height: 120px;
		    width: 43%;
		    text-align: center;
		    padding: 0;
		    margin: 0;
		    display: inline-block;
		    top:calc(50% - 60px);
		}

		.panel{
			margin:32px;
			padding:17px;
			border-radius: 7px;
			border: 1px solid #eee;
			background:white;
			width:calc(100% - 98px);
			box-shadow: 3px 3px 6px 0px #e4e4e4, 6px 6px 12px 0px #eeedff;
		}

		.nav-icon {
			position: absolute;
			left: 0.5em;
		  	margin: 0.5em;
		  	width: 40px;
		  	cursor:pointer;
		}

		.nav-icon:hover{
			opacity:0.8;
		}

		.nav-icon:after, 
		.nav-icon:before, 
		.nav-icon div {
		  background-color: #eee;
		  border-radius: 8px;
		  content: '';
		  display: block;
		  height: 3px;
		  margin: 9px 0;
		  transition: all .17s ease-in-out;
		}

		.clicked_nav:before {
		  transform: translateY(12px) rotate(135deg);
		}

		.clicked_nav:after {
		  transform: translateY(-12px) rotate(-135deg);
		}

		.clicked_nav div {
		  transform: scale(0);
		}

		.button {
		    font-family: 'Rosario', sans-serif;
		    -webkit-appearance: none;
		    height: 37px;
		    border: 0;
		    outline: none;
		    cursor: pointer;
		    transition: all 0.16s cubic-bezier(0.77, 0, 0.175, 1);
		    font-size: 16px;
		    border-radius: 8px;
		    font-weight: 600;
		    letter-spacing: 0.1em;
		    padding: 0;
		   	margin: 13px 4px 13px 0px;
		    padding: 0px 16px;
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
		  	background-color: #7482FF;
		    color: white;
		}

		.secondary_button{
			box-shadow: 0px 0px 8px -3px #7482FF;
			background-color: white;	
			color: #7482FF;
		}



		#chart{
			position: absolute;
			width:96%;
			height:100%;
		}

		#insights_inner{
			position: absolute;
    		bottom: 0;
    		background: #6578dc;
    		width: 100%;
    		height: 20%;
    		border-top-left-radius: 90px;
		}


		.transaction_header{
		    color: #bbb;
		    margin: 13px 4px 13px 0px;
		    display: inline-block;
		    padding-bottom: 10px;
		    position: relative;
		    width: 50%;
		    text-align: center;
		    cursor:pointer;
    		margin-bottom: 23px;
    		height: 36px;
    		font-size:1.4em;
		}

		.transaction_header:hover{
			color:#60d4ff;
		}

		.th_chosen{
			color: #6578dc;
			margin-bottom: 23px;
		}

		.th_chosen:before{
		    content: "";
		    position: absolute;
		    width: 100%;
		    height: 8px;
		    bottom: 0;
		    left: 0;
		    border-bottom: 2px solid #6578dc;

		}

		#headerBar{
			display: block;
    		width: 100%;
    		z-index: 500;
    		height: 62px;
    		background-color: #6578dc;
		}

		#logout_button_container{
		    width: 100%;
		    display: table-row;
		    height: 100px;
		    box-sizing: border-box;
		    color: white;
		    bottom:0;
		    padding: 23px;
		    position: absolute;
		    font-size: 24px;
		    cursor: pointer;
		}

		#logout_button_container:hover{
			background: #6578dc;
		}

	#logout_icon{
		width: 50px;
    position: absolute;
    right: 20px;
	}

	#logout_button{
		line-height: 54px;
	}

	.label {
		display: inline-flex;
	    -webkit-box-align: center;
	    align-items: center;
	    cursor: pointer;
	    width: 50%;
	}

	.toggle {
	  isolation: isolate;
	  position: relative;
	  height: 24px;
	  width: 48px;
	  border-radius: 15px;
	  background: #d6d6d6;
	  overflow: hidden;
	}

	.toggle-inner {
	  z-index: 2;
	  position: absolute;
	  top: 1px;
	  left: 1px;
	  height: 22px;
	  width: 46px;
	  border-radius: 15px;
	  overflow: hidden;
	}

	.active-bg {
	  position: absolute;
	  top: 0;
	  left: 0;
	  height: 100%;
	  width: 200%;
	background: #6578dc;
	  -webkit-transform: translate3d(-100%, 0, 0);
	          transform: translate3d(-100%, 0, 0);
	  -webkit-transition: -webkit-transform 0.05s linear 0.17s;
	  transition: -webkit-transform 0.05s linear 0.17s;
	  transition: transform 0.05s linear 0.17s;
	  transition: transform 0.05s linear 0.17s, -webkit-transform 0.05s linear 0.17s;
	}

	.toggle-state {
	  display: none !important;
	}

	.indicator {
	  height: 100%;
	  width: 200%;
	  background: white;
	  border-radius: 13px;
	  -webkit-transform: translate3d(-75%, 0, 0);
	          transform: translate3d(-75%, 0, 0);
	  -webkit-transition: -webkit-transform 0.35s cubic-bezier(0.85, 0.05, 0.18, 1.35);
	  transition: -webkit-transform 0.35s cubic-bezier(0.85, 0.05, 0.18, 1.35);
	  transition: transform 0.35s cubic-bezier(0.85, 0.05, 0.18, 1.35);
	  transition: transform 0.35s cubic-bezier(0.85, 0.05, 0.18, 1.35), -webkit-transform 0.35s cubic-bezier(0.85, 0.05, 0.18, 1.35);
	}

	.toggle-state:checked ~ .active-bg {
	   -webkit-transform: translate3d(-50%, 0, 0);
	           transform: translate3d(-50%, 0, 0);
	}

	.toggle-state:checked ~ .toggle-inner .indicator {
	   -webkit-transform: translate3d(25%, 0, 0);
	           transform: translate3d(25%, 0, 0);
	}

	.label-text{
	font-family: Oxygen, sans-serif;
	    margin: 14px 27px;
	}

	.custom-select-cats{
		display:inline-block;
	}

	tr{
		height:48px;
	}

	.green_row{
		background: #cfffe0 !important;
	}

	.red_row{
		    color: #ad6589 !important;
	}

	@media only screen and (max-width: 980px) {
		.oneSection{
			width:100% !important;
		}
	}

	</style>


















</head>

<script src="js/apexCharts.js"></script>
<script src="dp/js/datepicker.min.js"></script>
<script type="text/javascript" src="dp/js/i18n/datepicker.en.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

<body>
<div id="surrounding_container">

	<div id="headerBar">
		
		<div class="nav-icon">
  			<div></div>
		</div>

	</div>

	<div id="settings_section">

		<div id="logout_button_container">
			<span id="logout_button">Logout <img id="logout_icon" style="width:50px;" src="images/logout.svg"></span>
		</div>


	</div>

	<div class="oneSection" id="transactions_section">
		
		<div class="loader loader--style2" style="display:none;">
			<img src="purple_loader.svg">
		</div>

		<div class="panel" style="min-height:5%;">

				<span id="th_single" class="transaction_header th_chosen">One off</span>

				<span id="th_recurring" class="transaction_header">Recurring</span>




				<form id="add_transaction" method="POST" action="api/transaction.php">
					
					<input type="hidden" name="method" value="create">
					<input id="hidden_type" type="hidden" name="type" value="0">


					<label class="label" id="toggle_type">
						<div class="label-text">Out</div>
					  <div class="toggle">
					    <input class="toggle-state" type="checkbox" id="in_or_out" name="in_or_out" value="check" />
					    <div class="toggle-inner">
					       <div class="indicator"></div>
					    </div>
					    <div class="active-bg"></div>
					  </div>
					  <div class="label-text">In</div>
					</label>



					<div class="custom-select-cats" id="out_cats" style="width:50%;">
						<select name="out_cat" >

					<?php foreach ($global_categories['in'] as $cat_id => $cat_name) {
						echo '<option value="'.$cat_id.'">'.$cat_name.'</option>';
					}
					?>

					  </select>
					</div>

					<div class="custom-select-cats" id="in_cats" style="width:50%;display:none;">
						<select name="in_cat" >

					<?php foreach ($global_categories['out'] as $cat_id => $cat_name) {
						echo '<option value="'.$cat_id.'">'.$cat_name.'</option>';
					}
					?>

					  </select>
					</div>

					<br>

					<input id="amount" class="red_row" onkeydown= "return event.keyCode !== 69" style="width:49%;" type="number" placeholder="Value" name="amount">

					<input type='text' id='date_picker' data-language='en' style="width:49%;" name="date" placeholder="Select a date">

					<br>
					<div id="recurring_section" style="display:none;">
						<label style="width:20%;">Repeat </label><input style="width:60%" name="occurences" type="number" placeholder="Leave blank if ongoing"><label style="width:20%;"> times</label><br>
						<select name="repeat_type" style="width:49%;">
							<option value="1">On the x every month</option>
							<option value="2">Every x days</option>
						</select>
						<input style="width:49%;" id="repeat" name="repeat" placeholder="What is x? e.g. 19th">
					</div>

					<input style="width:100%;display:block" type="text" placeholder="Description" name="description">


					<button style="width:100%;" class="button primary_button" type="submit" name="add" value="add">Add</button>
				</form>
			
		</div>

		<div class="panel" id="monthly_transactions" style="text-align:center;font-family: 'Oxygen', sans-serif;">

			<style>
				#monthly_transactions tr:hover{
					opacity:0.7;
					cursor: pointer;
				}

				.dataTables_wrapper .dataTables_info {
				    clear: both;
				    float:unset;
				    padding-top: 0.755em;
				    font-size: 0.8em;
				}

				#viewing_date{
				    margin: 14px;
				    position: relative;
				    top: -12px;
				    font-size: 1.4em;
				    width:33%;
				}

				#monthly_header{
				    display: flex;
				    align-items: flex-end;
				    padding: 16px 0px;
				}

				.dataTables_wrapper .dataTables_filter {
				  float: unset;
				   text-align: unset;
				}

				.viewing_date_wrapper{
					width: 40%;
    				display: inline-block;
				}

				.date_arrow{
					cursor:pointer;
					width:33%;
				}

				.date_arrow .filled{
					display:none;
				}
				.date_arrow .outline{
					display:inline-block;
				}

				.date_arrow:hover .filled{
					display:inline-block;
				}

				.date_arrow:hover .outline{
					display:none;
				}

				#transaction_table_wrapper{
					padding: 23px 0;
				}

				#transaction_table{
					margin: 23px 0px;
    				font-size: 0.8em;
    				background: #f7f7f7;
				}

			</style>

			<span id="monthly_header">

				<span class="date_arrow da_left">
					<img class="filled" src="images/arrow-left-filled.svg">
					<img class="outline" src="images/arrow-left.svg">
				</span>
			
			<div class="viewing_date_wrapper">
				<span id="viewing_date"></span>
			</div>

				<span class="date_arrow da_right">
					<img class="filled"  src="images/arrow-right-filled.svg">
					<img class="outline" src="images/arrow-right.svg">
				</span>

			</span>

			<table id="transaction_table">
				<thead><tr><th>Description</th><th>&pound;</th><th>Date</th><th>Type</th></tr></thhead><tbody></table>
			
		</div>


	</div>

	<div class="oneSection" id="graph_section">

		<div class="loader2 loader--style2" style="display:none;">
			<img src="purple_loader.svg">
		</div>
		
		<div id="chart"></div>

	</div>

	<div class="oneSection" id="insight_section">

		<div class="loader2 loader--style2" style="display:none;">
			<img src="purple_loader.svg">
		</div>
		


	</div>

</div>
</body>

</html>

















<script>

months_to_numbers = ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec'];

var table = $('#transaction_table').DataTable({"paging": false});

var options = {
	chart: {
		foreColor: '#ffffff',
    type: 'line',
    dropShadow: 
    	{
        	color:'#9137BB',
			enabled: true,
			top: 0,
			left: 0,
			blur: 6,
			opacity: 0.3
		},
	toolbar: 
		{
		show: false,
		},
	zoom:
		{
		enabled:false,
		}
  	},
  	series: 
  		[{
    	name: 'Balance',
    	data: [32,43,54,53,136,64,246,245,264,223,134]
  		}],
  	xaxis: 
  		{
    	categories: [10,11,12,13,14,15,16,17,18,19,20]
  		},
	stroke:
		{
		width: 2,
	    curve: 'smooth'
	    },
	grid: 
		{
		show: false,
		padding: 
			{
			left: 0,
			right: 0
			}
		},
	fill: {
	type: 'gradient',
		gradient: 
			{
			gradientToColors: ['#C67DFF','#7482FF','#79C7FF','#3FF3FF'],
			shadeIntensity: 0,
			type: 'horizontal',
			opacityFrom: 1,
			opacityTo: 1,
			stops: [0,100]
			}
		}
	};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();


function populate_transactions(month,year){
	$.ajax({
	type: "GET",
	url: 'api/monthly_transactions.php?year=' + year + '&month='+ month ,

	success: function(data)
	{
		var result_object = JSON.parse(data);
		
		if (result_object.result === "success")
		{
			$('#transaction_table').html('<thead><tr><th>Value</th><th>Date</th><th>Type</th></tr></thhead><tbody>');
			for (var i = 0; i < result_object['data'].length; i++){

				var green = result_object['data'][i]['type'];
				if (green===0){
    				$('#transaction_table').append('<tr class="red_row"><td>'+result_object['data'][i].description+'</td><td>'+result_object['data'][i].value+'</td><td>'+result_object['data'][i].date+'</td><td> - </td></tr>');
				}else{
		    		$('#transaction_table').append('<tr class="green_row"><td>'+result_object['data'][i].description+'</td><td>'+result_object['data'][i].value+'</td><td>'+result_object['data'][i].date+'</td><td> + </td></tr>');	
				}
			}
			$('#transaction_table').append('</tbody>');
			table.destroy()
			table = $('#transaction_table').DataTable({"paging": false});
			
		}
		else
		{
			$('.loader2').hide();
		}
	}
	});

}


$('#date_picker').datepicker({
    language: 'en',
    toggleSelected: false,
    autoClose: true,
    dateFormat : "dd/mm/yyyy"
});

/*function set date*/
var MyDate = new Date();

viewing_year = parseInt(MyDate.getFullYear());
viewing_month = parseInt(MyDate.getMonth()+1);

MyDateString = ('0' + MyDate.getDate()).slice(-2) + '/'
             + ('0' + (MyDate.getMonth()+1)).slice(-2) + '/'
             + MyDate.getFullYear();

document.getElementById('date_picker').value = MyDateString;

populate_transactions(viewing_month,viewing_year);
populate_graph(chart);

$('#viewing_date').html(months_to_numbers[viewing_month-1] + " " + viewing_year);




$("#add_transaction").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    var submit_data = form.serialize(); // serializes the form's elements.

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
				transaction_type = 0;
				$('.loader2').hide();
				var date_copy = document.getElementById('date_picker').value 
				$('#add_transaction').trigger("reset");

				$('#amount').removeClass('red_row');
				$('#amount').removeClass('green_row');
				$('#amount').addClass('red_row');

				document.getElementById('date_picker').value = date_copy;
				populate_transactions(viewing_month,viewing_year);
				populate_graph(chart);

			}
			else
			{
				$('.loader2').hide();
			}
		}
		});
	});

$('.nav-icon').click(function(){
	$(this).toggleClass('clicked_nav');
	$('#settings_section').toggleClass('open');
})

$('.da_left').click(function(e){
	viewing_month -=1;

	if (viewing_month==0){
		viewing_month=12;
		viewing_year-=1;
	}

	$('#viewing_date').html(months_to_numbers[viewing_month-1] + " " + viewing_year);
	populate_transactions(viewing_month,viewing_year);

});

function populate_graph(chart){

chart.destroy();

	$.ajax({
		type: "GET",
		url: 'api/yearly_graph.php?year='+viewing_year,
		success: function(response_data)
		{
			var result_object = JSON.parse(response_data);
			
			if (result_object.result === "success")
			{
				console.log(result_object);
			}
			else
			{
				console.log(result_object);
			}
			options.series[0].data = result_object.balance;
			options.xaxis.categories = result_object.labels;

			
			var chart = new ApexCharts(document.querySelector("#chart"), options);
			chart.render();

		}
	});
}

$('.da_right').click(function(e){

	viewing_month +=1;

	if (viewing_month>12){
		viewing_month=1;
		viewing_year+=1;
	}

	$('#viewing_date').html(months_to_numbers[viewing_month-1] + " " + viewing_year);
	populate_transactions(viewing_month,viewing_year);

});

var transaction_type = 0;

$('#th_single').click(function(){
	transaction_type = 0;
	$('.transaction_header').removeClass('th_chosen');
	$(this).addClass('th_chosen');
	$('#recurring_section').slideUp(95);
	$('#add_transaction').attr("action","api/transaction.php")
});

$('#th_recurring').click(function(){
	transaction_type = 1;
	$('.transaction_header').removeClass('th_chosen');
	$(this).addClass('th_chosen');
	$('#recurring_section').slideDown(95);
	$('#add_transaction').attr("action","api/recurring_transaction.php")
});

$('#in_or_out').change(function(){
	$('.custom-select-cats').hide();
	$('#amount').toggleClass('red_row');
	$('#amount').toggleClass('green_row');

	if(!this.checked) {
		$('#out_cats').show();
		$('#hidden_type').attr('value',0);

	}
	else{
		$('#in_cats').show();
		$('#hidden_type').attr('value',1);
	}
})


$("#logout_button_container").click(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

	$.ajax({
		type: "POST",
		url: 'api/logout.php',
		data: '',
		success: function(response_data)
		{
			var result_object = JSON.parse(response_data);
			
			if (result_object.result === "success")
			{
				console.log(result_object);
				window.location.href = "login.php?reason=2";
			}
			else
			{
				window.location.href = "login.php?reason=2";
			}
		}
	});
});

</script>

