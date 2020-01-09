<?php
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

    $red   = $_GET['r'];
    $green = $_GET['g'];
    $blue  = $_GET['b'];

    $msg = "$red:$green:$blue";
    $len = strlen($msg);

    $tmp = socket_sendto($sock, $msg, $len, 0, '192.168.50.205', 7777);
    socket_close($sock);
?>
<!DOCTYPE html>
<html>
	<head>
	<style>
		#color {
		  background-color: <?php echo "rgb(" . ($_GET['r'] * 2.55) . "," . ($_GET['g'] * 2.55) . "," . ($_GET['b'] * 2.55);  ?>); 
		  width: 100%;
		  height: 100px;
		}

		#color h1 {
			display: inline-block;
			text-shadow: 2px 1px #ffffff;
		}

		.box {
			width: 50%;
			padding: 20px;
			border: 2px solid black;
			margin: 0 auto;
		}

		.minibox {
			width: 32%;
			margin-right: 0.35%;
			margin-left: 0.35%;
			display: inline-block;
		}

		.minibox input {
			width: 100%;
		}

		.minibox h1 {
			text-align: center;
		}

	</style>
	</head>
	<body>
		<h1 style="text-align: center;">Projekt na PV198</h1>
		<h4 style="text-align: center;">Marek Pastierik</h4>

		<div class="box">
			<div class="minibox">
				<h1 style="color: red;">RED</h1>
				<input oninput="changeRed(this.value)" value="<?php echo $_GET['r'];?>" type="range" name="red" min="0" max="100">
			</div>
			<div class="minibox">
				<h1 style="color: green;">GREEN</h1>
				<input oninput="changeGreen(this.value)" value="<?php echo $_GET['g'];?>" type="range" name="green" min="0" max="100">
			</div>
			<div class="minibox">
				<h1 style="color: blue;">BLUE</h1>
				<input oninput="changeBlue(this.value)" value="<?php echo $_GET['b'];?>" type="range" name="blue" min="0" max="100">
			</div>
		
			<div id="color"></div>
		</div>
	</body>
	<script type="text/javascript">
		function changeRed(value) {
                        var newUrl = '/pv180.php?'
			window.location.href = newUrl.concat(`r=${value}<?php echo "&g=" . $_GET['g'] . "&b=" . $_GET['b']; ?>`);
		}

		function changeGreen(value) {
                        var newUrl = '/pv180.php?'
                        window.location.href = newUrl.concat(`g=${value}<?php echo "&r=" . $_GET['r'] . "&b=" . $_GET['b']; ?>`);

		}

		function changeBlue(value) {
                        var newUrl = '/pv180.php?'
                        window.location.href = newUrl.concat(`b=${value}<?php echo "&g=" . $_GET['g'] . "&r=" . $_GET['r']; ?>`);
		}
	</script>
</html>
