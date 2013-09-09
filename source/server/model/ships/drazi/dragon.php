<?php
class Dragon extends FighterFlight{
    
    function __construct($id, $userid, $name,  $slot){
        parent::__construct($id, $userid, $name,  $slot);
        
	$this->pointCost = 210;
	$this->faction = "Drazi";
        $this->phpclass = "Dragon";
        $this->shipClass = "Dragon Light Fighters";
	$this->imagePath = "img/ships/dragon.png";
        
        $this->forwardDefense = 6;
        $this->sideDefense = 7;
        $this->freethrust = 12;
        $this->offensivebonus = 3;
        $this->jinkinglimit = 10;
        $this->turncost = 0.33;
        $this->iniativebonus = 110;
        
        for ($i = 0; $i<6; $i++){
            $armour = array(1, 1, 1, 1);
            $fighter = new Fighter("dragon", $armour, 9, $this->id);
            $fighter->displayName = "Dragon Light Fighter";
            $fighter->imagePath = "img/ships/dragon.png";
            $fighter->iconPath = "img/ships/dragon_large.png";


            $fighter->addFrontSystem(new LightParticleBeam(330, 30, 3));

            $this->addSystem($fighter);
        }
    }
}
?>