window.ajaxInterface = {

    poll: null,
    pollcount: 0,
	submiting: false,

    submitGamedata: function(){
	
		if ( ajaxInterface.submiting )
			return;
			
		ajaxInterface.submiting = true;
	
        var gd = ajaxInterface.construcGamedata();
        
        
        
        $.ajax({
            type : 'POST',
            url : 'gamedata.php',
            dataType : 'json',
            data: gd,
            success : ajaxInterface.successSubmit,
            error : ajaxInterface.errorSubmit
        });
        
        gamedata.goToWaiting();
    },
    
    construcGamedata: function(){
        
        var tidyships = jQuery.extend(true, {}, gamedata.ships);
        
        for (var i in tidyships){
            var ship = tidyships[i];
            ship.htmlContainer = null;
            ship.shipclickableContainer = null;
            ship.shipStatusWindow = null;
            for (var a = ship.movement.length-1; a>=0; a--){
                var move = ship.movement[a];
                if (move.turn < gamedata.turn){
                    ship.movement.splice(a,1);
                }
            }
            
            for (var a = ship.EW.length-1; a>=0; a--){
                var ew = ship.EW[a];
                if (ew.turn < gamedata.turn){
                    ship.EW.splice(a,1);
                }
            }
            var systems = Array();
            
            for (var a in ship.systems){
                var system = ship.systems[a];
                
                if (ship.flight){
                    var fighterSystems = Array();
                    for (var c in system.systems){
                        var fightersystem = system.systems[c];
                        
                        for (var b = fightersystem.fireOrders.length-1; b>=0; b--){
                            var fire = fightersystem.fireOrders[b];
                            if (fire.turn < gamedata.turn){
                                fightersystem.fireOrders.splice(b,1);
                            }
                        }
                        fighterSystems[c] = {'id':fightersystem.id, 'fireOrders': fightersystem.fireOrders};
                    }
                    
                    systems[a] = {'id': system.id, 'systems': fighterSystems};
             
                    
                }else{
                    for (var b = system.fireOrders.length-1; b>=0; b--){
                        var fire = system.fireOrders[b];
                        if (fire.turn < gamedata.turn){
                            system.fireOrders.splice(b,1);
                        }
                    }

                    for (var b = system.power.length-1; b>=0; b--){
                        var power = system.power[b];
                        if (power.turn < gamedata.turn){
                            system.power.splice(b,1);
                        }
                    }
                    systems[a] = {'id': system.id, 'power': system.power, 'fireOrders': system.fireOrders};
                }
                
            }
            
            ship.systems = systems;
            
        }
       
        var gd = {
            turn: gamedata.turn,
            phase: gamedata.gamephase,
            activeship: gamedata.activeship,
            gameid: gamedata.gameid,
            playerid: gamedata.thisplayer,
            ships: JSON.stringify(tidyships)
        };
  
        return gd;
    },
    
    successSubmit: function(data){
		ajaxInterface.submiting = false;
        //console.log("success submit" + $(data));
		if (data.error){
			ajaxInterface.errorAjax(data);
		}else{
			//gamedata.parseServerData(data);
		}
    },
    
    errorAjax: function(data){
        console.log("error !");
        if (data.error == "Not logged in."){
			$(".notlogged").show();
			$(".waiting").hide();
			gamedata.waiting = false;
		}
    },
	
	errorSubmit: function(data){
		ajaxInterface.submiting = false;
        console.log("error " + data.error);
        if (data.error == "Not logged in."){
			$(".notlogged").show();
			$(".waiting").hide();
			gamedata.waiting = false;
		}
    },
    
    startPollingGamedata: function(){
        
        if (gamedata.poll != null){
			console.log("starting to poll, but poll is not null");
            return;
			}
           
        ajaxInterface.pollcount = 0;
            
        ajaxInterface.pollGamedata();
    },
    
    pollGamedata: function(){
        if (gamedata.waiting == false){
            return;
			
			ajaxInterface.poll = null;
			ajaxInterface.pollcount  = 0;
		}          
        if (!gamedata.animating){
            //console.log("polling for gamedata...");
            animation.animateWaiting();
        
            if (!ajaxInterface.submiting)
                ajaxInterface.requestGamedata();
			
        }
        
        ajaxInterface.pollcount++;
        
        var time = 6000;
        
        if (ajaxInterface.pollcount > 100){
            time = 30000;
        }
        
        if (ajaxInterface.pollcount > 200){
            time = 300000;
        }
        
        if (ajaxInterface.pollcount > 300){
            return;
        }   
       
		ajaxInterface.poll = setTimeout(ajaxInterface.pollGamedata, time);
    },
    
    startPollingGames: function(){
        ajaxInterface.pollGames();
    },
    
    pollGames: function(){
        if (gamedata.waiting == false)
            return;
        
        if (!gamedata.animating){

            animation.animateWaiting();
        
            ajaxInterface.requestAllGames();
        }
        
    },
    
    requestGamedata: function(){
        
        ajaxInterface.submiting = true;
        
        $.ajax({
            type : 'GET',
            url : 'gamedata.php',
            dataType : 'json',
            data: {
                turn: gamedata.turn,
                phase: gamedata.gamephase,
                activeship: gamedata.activeship,
                gameid: gamedata.gameid,
                playerid: gamedata.thisplayer,
                time: new Date().getTime()
            },
            success : ajaxInterface.successRequest,
            error : ajaxInterface.errorAjax
        });
    },
    
    requestAllGames: function(){
        
        ajaxInterface.submiting = true;
        
        $.ajax({
            type : 'GET',
            url : 'allgames.php',
            dataType : 'json',
            data: {},
            success : ajaxInterface.successRequest,
            error : ajaxInterface.errorAjax
        });
    },
    
    successRequest: function(data){
        ajaxInterface.submiting = false;
        //console.log("success request" + $(data));
        gamedata.parseServerData(data);
    }
}







