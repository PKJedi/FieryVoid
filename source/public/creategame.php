<?php
    include_once 'global.php';
    
	if (!isset($_SESSION["user"]) || $_SESSION["user"] == false){
		header('Location: index.php');
	}
	if (!Manager::canCreateGame($_SESSION["user"])){
		header('Location: games.php');
	}
	
	$maps = Manager::getMapBackgrounds();
	
	if (isset($_POST["docreate"]) && isset($_POST["name"])){
		
		$points = $_POST["points"];
		if (!is_numeric($points))
			$points = 1000;
			
		if ($points >20000)
			$points = 20000;
	
		$id = Manager::createGame($_POST["name"], $_POST["background"], 2, $points, $_SESSION["user"]);
		if ($id){
			header('Location: gamelobby.php');
		}
		
	}
	
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FieryVoid</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="styles/base.css" rel="stylesheet" type="text/css">
        <link href="client/UI/confirm.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="client/UI/confirm.js"></script>        
		<script>
		
			function mapSelect(){
				
				$("#default_option").remove();
				var val = $("#mapselect").val();
				$("body").css("background-image", "url(img/maps/"+val+")");
	
			}
			
			jQuery(function($){
            
				mapSelect();
            
			});
		
		</script>
	</head>
	<body>
	
		<div class="panel large">
			<div class="panelheader">	<span>CREATE GAME</span>	</div>
			<form method="post">
			
				<div><span>Name:</span></div>
				<input type="text" name="name" value="">
						
				<div><span>Background:</span></div>
				<select id="mapselect" name="background" onChange="mapSelect();">
					<!--<option id="default_option" value="default">select ...</option>-->
					<?php
						
						foreach ($maps as $name){
							
							print("<option value=\"".$name."\">".$name."</option>");
						}
					
					?>
				</select>
				<!--
				<div><span>Max players per side:</span></div>
				<select name="maxplayers">
					<option id="1" value="default">1</option>
					<option id="2" value="default">2</option>
					<option id="3" value="default">3</option>
					<option id="4" value="default">4</option>
					
				</select>
				-->
				
				<div><span>Points</span></div>
				<input type="text" name="points" value="1000">
				
				<input type="hidden" name="docreate" value="true">
				<input type="submit" value="Create">
				
				
			</form>
			
		</div>

	</body>
</html>