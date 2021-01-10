<?php
require('data.php');
// echo '<pre>';
// print_r($_POST);
// print_r($_SESSION["formData"]);
// echo '</pre>';
$errors = '';
$notifications = '';

// ijungia sesijos funkcionaluma_NAUDOJAMAS VISUOMET,
// jei norima istraukti arba irasyti i sesija_automatiskai atsiranda cookie'is console'eje
session_start();

// tikrinama ar notification yra sesijoje
if (isset($_SESSION['formData']['notification'])) {
	// i kintamaji notification irasomas tai, kas buvo sesijos notification rakte
	$errors = $_SESSION['formData']['notification'];
	// istrinamas notification is sesijos(masyvas)
	unset($_SESSION['formData']['notification']);
}

/////////////PAVYZDYS SAU///////////////////////
// kaip gali atrodyti sesija (kaip masyvas):
// $session = [
// 	'formData' => [
// 		'email' => 'oeauaoe',
// 		'loginTime' => 123132321321,
// 		'notification' => 'please login',
// 	]
// ];

// unset f-ja veikia ant betkokio masyvo - istrina nurodyta rakta
// unset($session['formData']['notification']);

// masyvas po unset:
// $session = [
// 	'formData' => [
// 		'email' => 'oeauaoe',
// 		'loginTime' => 123132321321,
// 	]
// ];

// iškristus random skaičiui (0, 1) ir nustacius verte, - pratęsiamas sesijos ir timer’io laikas
// sukuriama sesijos trukme
// issijungines tarp 10 - 15 sekundziu
$sessionTime = 10;
// tikrinama ar prisijunges vartotojas
if (isset($_SESSION['formData']['loginTime'])) {
	// kuriamas sesijos pratesimas
	$extendSession = rand(0, 1);
	// tikrinama verte 0 ar 1
	if($extendSession === 1) {
		// nustatomas sesijos laiko trukme
		$sessionTime = 15;	
	}
	
}

// tikrinama kiekvienos uzklausos pradzioje ar buvo prisijungta anksciau nei pries 10 sek
// patikrina ar kazkas irasyta i formdata
// is dabartinio laiko atima prisijungimo laika
// tikrina ar daugiau nei 10 sek
// vietoje >=10 irasome $sessionTime
// time() php grazina sekundes
if (isset($_SESSION['formData']['loginTime']) && (time() - $_SESSION['formData']['loginTime'] >= $sessionTime)) {
	session_unset();     // unset $_SESSION variable for the run-time 
	session_destroy();   // destroy session data in storage
}

// forma issiusta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// htmlspecialchars - pavercia specialius simbolius i html entities'cius
	$email = htmlspecialchars($_POST['email']); 
	$password = htmlspecialchars($_POST['password']);
	// tikrinu ar suvesti duomenys sutampa su turimais masyve
	$found = false;
	foreach ($users as $key => $user) { 
		if($email === $user['email'] && $password === $user['password']) {
			// i sesija irasomas prisijungusio vartotojo email'as
			checkSaveToSession($email, "email");
			// irasomas prisijungimo laikas i sesija
			checkSaveToSession(time(), "loginTime");			
			// login redirect'inamas į home.php per header("Location: home.php")
			// uzkomentuoju $notifications = 'Username match';
			header("Location: home.php");
			$found = true;
			break;
		}
	} 
	if(!$found) {
		$errors = 'User not found or incorrect password';
	}  
}

// helper functions 
function feedbackElement() {
	global $errors, $notifications;
	if ($errors !== '') {
		return "<p class=\"feedback errors\"> $errors </p>";
	} 
	if ($notifications !== '') {
		return "<p class=\"feedback notifications\"> $notifications </p>";
	} 
}

function checkSaveToSession($input, $key)
{
	if ($input !== '') {
		$_SESSION['formData'][$key] = $input;
	}
}