<?php
class TRakhArmedBP extends FighterFlight{
    /*T'Rakh Narn Armed Breaching Pod*/
    
    function __construct($id, $userid, $name,  $slot){
        parent::__construct($id, $userid, $name,  $slot);
        
		    $this->pointCost = 80*6;
		    $this->faction = "Narn";
        $this->phpclass = "TRakhArmedBP";
        $this->shipClass = "T'Rakh Armed Breaching Pods";
		    $this->imagePath = "img/ships/NarnTRakh.png";
        
        $this->forwardDefense = 9;
        $this->sideDefense = 10;
        $this->freethrust = 5;
        $this->offensivebonus = 2;
        $this->jinkinglimit = 0;
        $this->pivotcost = 2; //shuttles have pivot cost higher
        $this->turncost = 0.33;
        
		    $this->iniativebonus = 8*5;
      
        $this->populate();
    }
    
    
    public function populate(){
        $current = count($this->systems);
        $new = $this->flightSize;
        $toAdd = $new - $current;
        for ($i = 0; $i < $toAdd; $i++){
			
			$armour = array(4, 4, 4, 4);
			$fighter = new Fighter("TRakhArmedBP", $armour, 19, $this->id);
			$fighter->displayName = "T'Rakh Armed Breaching Pod";
			$fighter->imagePath = "img/ships/NarnTRakh.png";
			$fighter->iconPath = "img/ships/NarnTRakh_Large.png";
			
			
			$fighter->addFrontSystem(new PairedParticleGun(330, 30, 2, 5)); //2 guns d6+5
			
			
			$this->addSystem($fighter);
			
		}
		
		
    }
}
?>
