<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">

		<?php
			include("../core/mod-core-meta.php");
			include("mod-volunteer-meta.php"); 
		?>	
		
		<style> .button-submit {width: 100px; bottom: 20px; left: 90px;}	</style>

		<!--
		*********************************************************************
		These bits of script alter the production DOM to allow for unit 
		validation testing.
		
		*********************************************************************
		-->
		
		<script>
			function stubForFrontValidation() {
				document.forms["volunteer-tester-form"].elements['frontSide'].checked = true;
				document.forms["volunteer-tester-form"].elements['serverSide'].checked = false;
				document.forms["volunteer-tester-form"].elements['allSide'].checked = false;
				document.forms["volunteer-form"].action = "processor-front-stub.php";
				document.forms["volunteer-form"].elements['testMode'].value = "";
				document.getElementById("volunteerSubmitButton").innerHTML = "Validate Front";
				document.getElementById("volunteerSubmitButton").setAttribute('onclick','volunteerValidateAndSubmit();');
			}
			
			function stubForServerValidation() {
				document.forms["volunteer-tester-form"].elements['frontSide'].checked = false;
				document.forms["volunteer-tester-form"].elements['serverSide'].checked = true;
				document.forms["volunteer-tester-form"].elements['allSide'].checked = false;
				document.forms["volunteer-form"].action = "mod-volunteer-processor.php";
				document.forms["volunteer-form"].elements['testMode'].value = "true";
				document.getElementById("volunteerSubmitButton").innerHTML = "Validate Server";
				document.getElementById("volunteerSubmitButton").setAttribute('onclick','skipValidationAndSubmit();');
			}
			
			function stubForRoundTrip() {
				document.forms["volunteer-tester-form"].elements['frontSide'].checked = false
				document.forms["volunteer-tester-form"].elements['serverSide'].checked = false;
				document.forms["volunteer-tester-form"].elements['allSide'].checked = true;
				document.forms["volunteer-form"].action = "mod-volunteer-processor.php";
				document.forms["volunteer-form"].elements['testMode'].value = "";
				document.getElementById("volunteerSubmitButton").innerHTML = "Round Trip";
				document.getElementById("volunteerSubmitButton").setAttribute('onclick','volunteerValidateAndSubmit();');
			}
			
			function skipValidationAndSubmit() {
				document.forms["volunteer-form"].submit();
			}
		</script>
		
	</head>
	<body>
		<div class="container">
			<p>SELECT TEST MODE</p>
			<p>NOTE: To clear success message, remove parameters from request url in browser bar and reload</p>
			<form id="volunteer-tester-form" class="pure-form volunteer-form" action="" method="">
			<p><input type="radio" name="testRadio" id="frontSide" onClick="stubForFrontValidation();" checked /> Front side input validation (no server side validation, email, or data post)</p>
			<p><input type="radio" name="testRadio" id="serverSide" onClick="stubForServerValidation();" /> Server side input validation (no front validation, email, or data post)</p>
				<ul><li>This mode allows testing on post-submit alterations to the post data by using a browser plug-in like TamperData</li></ul>
			<p><input type="radio" name="testRadio" id="allSide" onClick="stubForRoundTrip();" /> Full functionality (all validations, email, and data post)</p>
		</form>
			<div class="allianceContent">
					<?php include("mod-volunteer.php"); ?>					
			</div>
		</div>

		<script>
				stubForFrontValidation();
		</script>
		
	</body>
</html>

