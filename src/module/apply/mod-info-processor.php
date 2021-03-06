<?php

require_once("../core/domain/Util.php");

$application;
$testMode = FALSE;
$env_host = ENV_HOST;

if (isset($_POST['testMode']) && !strcmp($_POST['testMode'], "true")) {
	require_once("TestApplication.php");
	$application = new TestApplication();
	$testMode = TRUE;
} else {
	require_once(Util::getFileDomainPath() . "/Application.php");
	$application = new Application();
}

if (isset($application)) {
	
	try {
		$application->setEmail($_POST['email_1']);
	} catch(Exception $e) { logErrorAndReturn($e, $testMode, null); }
			
	$application->sendInformation();
	
} else { 
	
	logErrorAndReturn("Application object is null.", $testMode, null);
}

logNormalAndReturn($testMode, $application);

function logErrorAndReturn($error, $test, $app) {
	if ($test) {
		echo $error->getMessage();
	} else {
		//header("location:" . Util::getHttpCorePath() . "/index.php?context=nexus&view=apply");
	}
	exit(0);
}

function logNormalAndReturn($test, $app) {
	if ($test) {
		//header("location:" . Util::getHttpApplyPath() . "/tester.php?context=nexus&view=apply");
	} else {
		//header("location:" . Util::getHttpCorePath() . "/index.php?context=nexus&view=apply");
	}
	exit(0);
}	

?>