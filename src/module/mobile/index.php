<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<?php 
require_once("../core/domain/Util.php");
$coreHttpPath = Util::getHttpCorePath();
$applyHttpPath = Util::getHttpApplyPath();

$useragent=$_SERVER['HTTP_USER_AGENT'];
$ie8 = FALSE;
$marketerId = "";

// initialize Apply link according to most recent marketer referral
if (isset($_GET['r']) && strlen($_GET['r']) == 1 && Util::isAllowedMarketerId($_GET['r'])) {
	$marketerId = $_GET['r'];
	setcookie("member_campaign_id", $marketerId, time() + (86400 * 30), "/"); // 86400 = 1 day
} else if (isset($_COOKIE['member_campaign_id']) && strlen($_COOKIE['member_campaign_id']) == 1 && Util::isAllowedMarketerId($_COOKIE['member_campaign_id'])) {
	$marketerId = $_COOKIE['member_campaign_id'];
} else {
	$marketerId = "1";
}
//$applyLink = Util::getMemberRegrUrl($marketerId);
$applyLink = Util::getGrantApplicationUrl();

if (isset($_GET['context']) && (!strcmp($_GET['context'], 'ie8') || !strcmp($_GET['context'], 'mobile'))) {
	// request is explicitly for mobile site - bypass desktop redirect
} else {
	// if user agent does not match a mobile pattern, go to desktop site
	if(!Util::isMobileUserAgent($useragent)) {
		header('Location: ' . $coreHttpPath . '/index.php?context=desktop');
	}
}

if (isset($_GET['context']) && !strcmp($_GET['context'], 'ie8')) {
	$ie8 = TRUE;
}

?>

<html>

	<head>
		<title>Northbridge Technology Alliance</title>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">	
		<meta id="meta" name="viewport" content="initial-scale=1;" />	
		<meta name="description" content="Nonprofit. Technology benefits for social justice leaders. Shouldn't your technology be as great as you are?">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Oswald:400,300|Open+Sans|Oxygen:400,700,300|Swanky+and+Moo+Moo">	
		<link rel="stylesheet" type="text/css" href="<?php echo Util:: getHttpCorePath(); ?>/style/font-awesome-4.2.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $coreHttpPath; ?>/style/pure-min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $coreHttpPath; ?>/style/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $applyHttpPath; ?>/mod-apply.css" />
		<style type="text/css">
			body { 
				font: bold 12px/20px Oxygen, Arial, sans-serif;
				text-align: center; 
				background-color: #638f90;
				color:#484848;
				background: transparent;
				margin: 0px;
			}
			
			.container {
				position: relative;
				background-color: #ffffff;
				margin: 0px auto;
				text-align: left;
			}
			input, select, textarea {
				/* This prevents iPhone from zooming in on form displays and demolishing page layout */
    		font-size: 16px;
			}
	</style>	

	<script language="javascript" type="text/javascript" src="<?php echo $applyHttpPath; ?>/mod-apply.js"></script>	
	<script language="javascript" type="text/javascript" src="<?php echo $coreHttpPath; ?>/lib/script.js"></script>	
		
	<script type="text/javascript">
		// override the scripts in domain scripts
		
		function showInfoEmailField() {
			document.getElementById("info-email").style.display='block';
		}
		
		function hideInfoEmailField() {
			document.getElementById("info-email").style.display='none';
			var input = document.getElementById("info-email-input");
			var control = document.getElementById("info-email-button");
			var closer = document.getElementById("info-email-close-box");
			input.innerHTML = "<input type='email' name='email_1' placeholder='Recipient Email' maxlength='100' style='width:95%;margin-top:10px;margin-bottom:10px;height:30px;' required >";
			control.innerHTML = "<span class='fa fa-play' style='margin-right:4px;' ></span>Send Information";
  		closer.style.display = "block";
  		control.onclick = function() {infoEmailValidateAndSubmit();};
		}

		function showInfoSuccessMessage(email_address) {
			var input = document.getElementById("info-email-input");
			var control = document.getElementById("info-email-button");
			var closer = document.getElementById("info-email-close-box");
  		input.innerHTML = "<span style='width:100%;margin-top:5px;'>Got it! Your information packet will be delivered in a few minutes to " + email_address + ".</span>";
  		control.innerHTML = "<span class='fa fa-close' style='margin-right:4px;' ></span>Close";
  		closer.style.display = "none";
  		control.onclick = function() {hideInfoEmailField();};
		}
		
		function showEligibilityForm() {
			document.getElementById("info-eligible").style.display='block';
		}
		
		function hideEligibilityForm() {
			document.getElementById("info-eligible").style.display='none';
		}	
		
		function eligibilityValidate() {
	 		var applyLink = document.getElementById("info-eligibility-apply-link");
	 		var applyDisabled = document.getElementById("info-eligibility-apply-disabled")
	 		//applyLink.style.display = "none";
			//applyDisabled.style.display = "inline-block";
	
			var pass = true;
			var eligibleDecision = [];
			var eligibleMessage = [];
			var applyForm = document.forms['info-eligibility-form'];
			var errorBackground = "rgba(252,176,64,0.5) url('') no-repeat right top";
			document.getElementById("user-message4").style.background = "white";
			document.getElementById("structure-area").style.background = "white";
	
	    var serviceField = applyForm["services"];
	    var service = serviceField.value;
			serviceField.style.backgroundColor = "white";
			if (service == 0) {
				serviceField.style.background = errorBackground;
				pass = false;
			} else if (service == 3) {
				eligibleMessage[1] = "Depending on more details of your service area, you may be eligible for membership. Our membership registration will collect that information.";
				eligibleDecision[1] = "You may be eligible for membership with Northbridge.";				
			} else {
	  	}
				
	    var budgetField = applyForm["budget"];
	    var budget = budgetField.value;
	    budgetField.style.backgroundColor = "white";
	    if (budget == null || budget == "" || budget == "0") {
	      budgetField.style.background = errorBackground;
	      pass = false;
	    }
	          
	    var countryField = applyForm["country"];
	    var country = countryField.value;
	    countryField.style.backgroundColor = "white";
	    if (country == null || country == "" || country == "0") {
	      countryField.style.background = errorBackground;
	      pass = false;
	    }
	    
	    var structureBoxes = applyForm["structure[]"];
	    checkedCount = 0;
	    for (var i=0, length = structureBoxes.length; i<length; i++) {
	    	if (structureBoxes[i].checked == true) {
	        checkedCount++;
	     	}
			}
			if (checkedCount == 0) {
				document.getElementById("structure-area").style.background = errorBackground;
				pass = false;
			} else if (checkedCount == 1) {
				if (structureBoxes[0].checked == true && budget >= 2) {
					eligibleDecision[0] = "For your grass roots team to be eligible, you must have a budget of less than $10,001 USD.";
				}
	  	}
	  	
			eligibleMessage[2] = "";
			eligibleDecision[2] = '<span style="color:#d27b4b;font-size:130%;">Good stuff!</span><br/>You are eligible for our programs.<br/>Click the orange button to continue.';
			var decisionIndex = 2;
			
			if (eligibleDecision[0]) {
				decisionIndex = 0;
			} else if (eligibleDecision[1]) {
				decisionIndex = 1;
			} else {
				decisionIndex = 2;
			}
	      
	 		if (Boolean(pass)) {
	 			// This indicates we passed form validation, not passed eligibility check
	 			document.getElementById('user-message4').innerHTML = eligibleDecision[decisionIndex];
	 			if (decisionIndex > 0) {
	 				//applyLink.style.display = "inline-block";
					//applyDisabled.style.display = "none";
	 			} else {
	 				//applyLink.style.display = "none";
					//applyDisabled.style.display = "inline-block";
					document.getElementById("structure-area").style.background = errorBackground;
					document.getElementById("user-message4").style.background = errorBackground;
	 			}
	 		} else {
	 			document.getElementById('user-message4').innerHTML = "If you fill out this form completely, we can confirm eligibility for your Board, committee, work group or task force.";
	 			//applyLink.style.display = "none";
				//applyDisabled.style.display = "inline-block";
	 		}			
		}
	</script>
	
	</head>
	
	<body>
		<div class="container" style="width:95%;max-width:400px;text-align:center;">
			<img src="<?php echo Util:: getHttpCorePath(); ?>/images/NB_horizontal_tagline_rgb.png" width="300" height="93" style="padding:20px 10px 20px 0px;"/>
			<p class="skyblue sponsorHeaderHeadline" style="font-size:150%;">Tech Grant for social justice leaders</p>

			<p style="margin:8px;" ><a class="pure-button button-link" style="width:190px;" href="<?php echo($applyLink); ?>" target="_blank" >2018 Grant Application</a></p>
			
			<!--
			<p style="margin:8px;" ><a class="pure-button button-link" style="width:190px;" href="javascript:void(0)" onclick="showEligibilityForm();">Am I Eligible?</a></p>
			-->
			
			<!--
			<p style="margin:6px;" ><a class="pure-button button-link" style="width:190px;" href="https://www.youtube.com/watch?v=8jyCYAZLwao&feature=youtu.be" target="_blank"><span class="fa fa-youtube-play fa-lg" style="margin-right:4px;" ></span>Overview</a></p>
			-->
				
			<!--
			<p style="margin:6px;" ><a class="pure-button button-link" style="width:190px;" href="javascript:void(0)" onclick="showInfoEmailField();">Information Packet</a></p>
			-->

			<!--<p style="margin:6px;" ><a class="pure-button button-link" style="width:190px;" href="https://www.youtube.com/watch?v=tk-QNJruZgM&feature=youtu.be" target="_blank"><span class="fa fa-youtube-play fa-lg" style="margin-right:4px;" ></span>Testimonial</a></p>		-->
				
			<!--
			<p style="margin:10px;" ><a class="pure-button button-link" style="width:190px;" href="http://nexus.northbridgetech.org/demo" target="_blank" ><span class="fa fa-wrench" style="margin-right:4px;" ></span>Try Nexus</a></p>
			-->


			<!--
			<div id="special-offer" class="white_content" style="position:absolute;top:115px;left:0px;width:95%;max-width:400px;border-radius:8px;display:block;">
			-->
			
			<!--
			<p style="font-size:120%;font-weight:bold;margin-top:10px;">
				Special Offer! Benefits Tech Pack<a href="https://twitter.com/intent/tweet?text=Social%20justice%20peeps...%204%20u!%20Like%20a%20food%20coop,%20for%20technology%2E&via=NorthbridgeNFP&url=http://ow.ly/oNlQ301iDDr" target="_blank"><img src="../core/images/tweet.png" width="72" height="28" style="vertical-align:text-top;margin-left:10px;" alt="Tweet"/>
				</a>
			</p>
			-->
			<!--
			<p style="width:100%;text-align:left;">
				<a class="pure-button button-link" href="https://www.eventbrite.com/e/mini-training-virtual-collaboration-for-social-activists-tickets-25983638763" target="_blank" style="width:46px;border-radius:4px;background:#f68620;">
					<span class="fa fa-external-link" style="margin-right:4px;" ></span>RSVP</a>
				Sample Training: Virtual collaboration
			</p>
			<p style="margin-top:10px;width:100%;text-align:left;">
				<a class="pure-button button-link" href="http://nexus.northbridgetech.org/demo" target="_blank" style="width:46px;border-radius:4px;background:#f68620;">
					<span class="fa fa-wrench" style="margin-right:4px;" ></span>Try</a>
				Sample Nexus: Take it for a spin!
			</p>
			<p style="margin-top:10px;width:100%;text-align:left;">
				<a class="pure-button button-link" href="<?php echo($applyLink); ?>" target="_blank" style="width:110px;border-radius:4px;background:#f68620;">
					<span class="fa fa-paper-plane" style="margin-right:4px;" ></span>Pre-approved!</a>
				Your work deserves our best...
			</p>

			<p style="margin-top:10px;text-align:left;" >
				<a class="pure-button button-link" style="width:110px;border-radius:4px;" href="javascript:void(0)" onclick="showInfoEmailField();">Information Packet</a>
				We'll send you some more info.
			</p>
			-->
			<!--</div>-->

			<div id="info-email" class="white_content" style="min-height:172px;display:none;position:absolute;top:140px;left:0px;width:95%;max-width:400px;border:0px;border-radius:0px;">
				<form id="info-email-form" action="<?php echo Util::getHttpApplyPath(); ?>/mod-info-processor.php" method="POST">
					<a id="info-email-close-box" class="pure-button button-link" onclick="hideInfoEmailField();" style="width:46px;border-radius:4px;float:right;margin-top:5px;"><span class="fa fa-times" style="margin-right:4px;" ></span> Back</a><p>We will send a packet of information to your email address. Your address will be used respectfully. We will not spam you.</p>
					<span id="info-email-input"><input type="email" name="email_1" placeholder="Recipient Email" maxlength="100" style="width:95%;margin-top:10px;margin-bottom:10px;height:30px;" required ></span>					
					<div id="info_buttons" style="position:absolute;bottom:0px;width:95%;">
						<p><a id="info-email-button" class="pure-button button-link" onclick="infoEmailValidateAndSubmit();" style="width:90%;border-radius:4px;background:#f68620;float:right;"><span class="fa fa-play" style="margin-right:4px;" ></span>Send Information</a></p>
					</div>				
				</form>
			</div>	

			<div id="info-eligible" class="white_content" style="min-height:325px;display:none;border-radius:8px;position:absolute;top:125px;left:0px;width:95%;max-width:400px;">
				<form id="info-eligibility-form" action="" method="POST">
					<a id="info-eligibility-close-box" class="pure-button button-link" onclick="hideEligibilityForm();" style="width:46px;border-radius:4px;float:right;margin-top:5px;"><span class="fa fa-times" style="margin-right:4px;" ></span>Close</a>
					<p id="user-message4">No-obligation. Check eligibility for yourself, your Board, committee, work group or task force.</p>
					<div id="eligibility_body" style="margin-top:15px;width:94%;">
						<select id="services" name="services" style="width:92%;margin-top:5px;margin-left:20px;margin-right:20px;height:30px;">
							<option value="0" selected>Service Area</option>
							<option value="9"> Human Services</option>
							<option value="4"> Restorative Justice</option>
							<option value="5"> Human Rights</option>
							<option value="6"> Health Equity</option>
							<option value="7"> Education Equity</option>
							<option value="8"> Environment Equity</option>
							<option value="8"> Housing Equity</option>
							<option value="8"> Intersectional Systemic Equity</option>
							<option value="1"> Underserved Community Development</option>
							<option value="3"> Other Social Justice or Community Building Focus</option>
						</select>
      			<select id="country" name="country" style="width:92%;margin-top:10px;margin-left:20px;height:30px;">
	      			<option value="0" selected>Governing Country</option>
							<?php include("../volunteer/countryDropdownOptions.html"); ?>
      			</select>		
      			<select id="budget" name="budget" style="width:92%;margin-top:10px;margin-left:20px;height:30px;">
	      			<option value="0" selected>Organizational Budget</option>
      				<option value="1">$0 - $10,000</option>
      				<option value="2">$10,001 - $100,000</option>
      				<option value="3">$100,001 - $250,000</option>
      				<option value="4">$250,001 - $500,000</option>
      				<option value="5">$500,001 - $1,000,000</option>
       				<option value="6"> &gt; $1,000,000</option>
	      		</select>					
						<p style="font-weight:normal;margin-left:20px;margin-top:10px;">Please describe your organizational structure</p>
						<div id="structure-area" style="margin-left:20px;">
			      	<input type="checkbox" name="structure[]" value="0" /> <span style="font-weight:normal;">Grass roots, community organized</span><br/>
       				<input type="checkbox" name="structure[]" value="1" /> <span style="font-weight:normal;">Incorporated or regulated nonprofit</span><br/>
       				<input type="checkbox" name="structure[]" value="2" /> <span style="font-weight:normal;">Tax-exempt nonprofit/charity</span>					
       			</div>
       		</div>
					<div id="eligibility_buttons" style="margin-top:15px;width:94%;">
						<a id="info-eligibility-check-button" class="pure-button button-link" onclick="eligibilityValidate();" style="border-radius:4px;margin-top:10px;margin-right:0px;margin-left:0px;width:108px;float:left"><span class="fa fa-play" style="margin-right:4px;" ></span>Check Eligibility</a>						
						<a id="info-eligibility-apply-disabled" class="pure-button pure-button-disabled button-link" href="#" onclick="alert('Please confirm your eligibility before viewing the registration form.');" style="border-radius:4px;background-color:#d27b4b;display:none;margin-top:10px;margin-left:0px;margin-right:0px;width:114px;float:right"><span class="fa fa-paper-plane" style="margin-right:4px;" ></span>View Applications</a>
						<a id="info-eligibility-apply-link" class="pure-button button-link" href="<?php echo($applyLink); ?>" target="_blank"" style="border-radius:4px;background-color:#d27b4b;display:inline-block;margin-top:10px;margin-left:0px;margin-right:0px;width:114px;float:right"><span class="fa fa-paper-plane" style="margin-right:4px;" ></span>View Applications</a>
					</div>

				</form>
			</div>

			<hr style="color:#dae0bc;"/>
			<p>
				<a href='https://twitter.com/NorthbridgeNFP' target='_blank'><img style="margin-right:10px;" src='http://northbridgetech.org/images/twitter_dae0bc_32.png' width='32' height='32' /></a>
				<a href='//plus.google.com/u/0/101145194341428988499?prsrc=3' rel='publisher' target='_blank' style='text-decoration:none;'><img style="margin-right:10px;" src='http://northbridgetech.org/images/google-plus-square_dae0bc_32.png' width='32' height='32' /></a>
				<a href='https://www.linkedin.com/company/2232384' target='_blank'><img style="margin-right:10px;" src='http://northbridgetech.org/images/linkedin_dae0bc_32.png' width='32' height='32' /></a>
				<a href='https://www.facebook.com/northbridgenfp#' target='_blank'><img style="margin-right:10px;" src='http://northbridgetech.org/images/facebook-square_dae0bc_32.png' width='32' height='32' /></a>
				<a href='https://github.com/NorthBridge/playbook/wiki/1.How-We-Do' target='_blank'><img style="margin-right:10px;" src='http://northbridgetech.org/images/github_dae0bc_32.png' width='32' height='32' /></a>
				<a href="http://members.northbridgetech.org/donate" target="_blank"><img src="http://northbridgetech.org/images/donate-now.png" width="75" height="29" /></a>

			</p>
			<p style="text-align:left;">Northbridge Technology Alliance creates software solutions for organizations who are engaged in social justice and community-building efforts.</p>
			<?php if (!$ie8) { ?>
				<a href="<?php echo $coreHttpPath; ?>/index.php?context=desktop&view=membership&c=2&m=1" style="color:#d27b4b;float:right;">Desktop Site</a>
			<?php } ?>
		</div>
	</body>
</html>

<!--
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank"> 
	<input type="hidden" name="cmd" value="_s-xclick"> <input type="hidden" name="notify_url" value="http://northbridgetech.org/paypalIpnListener.php"> <input type="hidden" name="hosted_button_id" value="CR3GPPFSE7ARW">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="position:absolute;margin-left:20px;"> 
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"> 
</form> 
-->

