<?php
require('./process/loginProcess.php');
require('./include/head.php');
require('./include/nav.php');

//sukuriamas sveikinimo pranesimas
$welcomeMessage = "";
if(!empty($_SESSION['formData']["email"])) {
	$welcomeMessage = "<h2> Welcome back, " . $_SESSION['formData']["email"] . "</h2>";
} else {
	// i sesija irasomas pranesimas ir nukreipiamas i index.php
	$_SESSION["formData"]["notification"] = "Please login!";
	header("Location: index.php");	
}

?>
<div class="container">

	<h1>login &amp; logout + JS</h1>

	<?php echo $welcomeMessage; ?>

	<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum architecto voluptates cupiditate expedita voluptate. Perspiciatis, ad! Molestias, vero ab. Id architecto repudiandae dolore, provident reprehenderit mollitia veniam vero corporis voluptates?</p>  

</div>

<!-- skaičiuojamas laikas (10 sekundžių) ir
po praėjusio laiko automatiškai išloginamas (nukreipiamas i index.php)
-->
<script type="text/javascript">
	// per JS negalima tiesiogiai atsijungti, todel vykdomas nukreipimas i PHP
	// setTimeout() iskviecia nurodyta f-ja po nurodyto laiko
	// 10000 mls = 10 sek (JS)
	// location.reload() - refreshina ta pati adresa (index.php)
	// vietoje 10000 irasoma php ir $sessionTime * 1000
	setTimeout(function() {
		location.reload();
	}, <?php echo $sessionTime * 1000; ?>)
</script>

</body>

</html>