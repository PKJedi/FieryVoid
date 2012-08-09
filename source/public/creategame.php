<?php
    include_once 'global.php';
    
	if (!isset($_SESSION["user"]) || $_SESSION["user"] == false){
		header('Location: index.php');
	}
	if (!Manager::canCreateGame($_SESSION["user"])){
		header('Location: games.php');
	}
	
	$maps = Manager::getMapBackgrounds();
	
	if (isset($_POST["docreate"]) && isset($_POST["data"])){
		
		$id = Manager::createGame($_SESSION["user"], $_POST["data"]);
		if ($id){
			header("Location: gamelobby.php?gameid=$id");
		}
		
	}
	
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FieryVoid</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="styles/base.css" rel="stylesheet" type="text/css">
        <link href="styles/confirm.css" rel="stylesheet" type="text/css">
        <link href="styles/lobby.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="client/UI/confirm.js"></script>
        <script src="client/UI/createGame.js"></script>
	</head>
	<body class="creategame">
	
		<div class="panel large">
			<div class="panelheader">	<span>CREATE GAME</span>	</div>
			<form id="createGameForm" method="post">
			
				<div><span>Name:</span></div>
				<input id="gamename" type="text" name="gamename" value="GAME NAME">
						
				<div><span>Background:</span></div>
				<select id="mapselect" name="background">
					<!--<option id="default_option" value="default">select ...</option>-->
					<?php
						
						foreach ($maps as $name){
							
							print("<option value=\"".$name."\">".$name."</option>");
						}
					
					?>
				</select>
				
                <div style="margin-top:20px;"><h3>TEAM 1</h3></div>
                <div id="team1" class="subpanel slotcontainer">
                    
                </div>
                <div><span class="clickable addslotbutton team1">ADD SLOT</span></div>
                
                <div><h3>TEAM 2</h3></div>
                <div id="team2" class="subpanel slotcontainer">
                    
                </div>
                <div><span class="clickable addslotbutton team2">ADD SLOT</span></div>
                
				
				<input type="hidden" name="docreate" value="true">
                <input id="createGameData" type="hidden" name="data" value="">
				<input type="submit" value="Create">
				
				
			</form>
			
		</div>

        <div id="slottemplatecontainer" style="display:none;">
            <div class="slot" >
                <div class="close"></div>
                <div>
                    <span class="smallSize headerSpan">NAME:</span>
                    <input class ="name mediumSize" type="text" name="name" value="BLUE">
                    <span class="smallSize headerSpan">POINTS:</span>
                    <input class ="points smallSize" type="text" name="points" value="0">
                </div>
                <div>
                    <span class="smallSize headerSpan">DEPLOYMENT:</span>
                    <span>X:</span>
                    <input class ="depx tinySize" data-validation="^-{0,1}[0-9]+$" data-default ="0" type="text" name="depx" value="0">
                    <span>Y:</span>
                    <input class ="depy tinySize" type="text" name="depy" value="0">
                    <span>Type</span>
                    <select class="deptype" name="deptype">
                        <option value="box">box</option>
                        <option value="circle">circle</option>
                        <option value="distance">distance</option>
                    </select>
                    <span class="depwidthheader">Width:</span>
                    <input class ="depwidth tinySize" type="text" name="depwidth" value="0">
                    <span class="depheightheader">Height:</span>
                    <input class ="depheight tinySize" type="text" name="depheight" value="0">
                    <span>Turn available:</span>
                    <input class ="depavailable tinySize" type="text" name="depavailable" value="0">
                </div>
            </div>
        </div>
	</body>
</html>