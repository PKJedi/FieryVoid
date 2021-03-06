<?php
class Ventrus extends BaseShip{
    
    function __construct($id, $userid, $name,  $slot){
        parent::__construct($id, $userid, $name,  $slot);
        
		$this->pointCost = 560;
        $this->faction = "Centauri (WotCR)";
        $this->phpclass = "Ventrus";
        $this->imagePath = "img/ships/kendari.png";
        $this->shipClass = "Ventrus Light Cruiser";
	$this->variantOf = "Kendari Fleet Scout";
        $this->occurence = "uncommon";
        $this->shipSizeClass = 3;
        $this->fighters = array("normal"=>6);
        
        $this->forwardDefense = 15;
        $this->sideDefense = 16;
        
        $this->turncost = 1;
        $this->turndelaycost = 1;
        $this->accelcost = 3;
        $this->rollcost = 2;
        $this->pivotcost = 3;
		       
        $this->addPrimarySystem(new Reactor(6, 18, 0, 0));
        $this->addPrimarySystem(new CnC(6, 16, 0, 0));
        $this->addPrimarySystem(new Scanner(5, 18, 4, 8));
        $this->addPrimarySystem(new Engine(5, 20, 0, 10, 2));
		$this->addPrimarySystem(new Hangar(4, 8));       
		
        $this->addFrontSystem(new Thruster(4, 8, 0, 3, 1));
        $this->addFrontSystem(new Thruster(4, 8, 0, 3, 1));
        $this->addFrontSystem(new LightParticleBeamShip(2, 2, 1, 270, 90));
        $this->addFrontSystem(new MediumPlasma(3, 5, 3, 240, 60));
        $this->addFrontSystem(new MediumPlasma(3, 5, 3, 300, 120));

        $this->addAftSystem(new Thruster(4, 15, 0, 5, 2));
        $this->addAftSystem(new Thruster(4, 15, 0, 5, 2));
        $this->addAftSystem(new JumpEngine(5, 25, 3, 20));
        $this->addAftSystem(new LightParticleBeamShip(2, 2, 1, 60, 300));
        $this->addAftSystem(new LightParticleBeamShip(2, 2, 1, 60, 300));
        
		$this->addLeftSystem(new Thruster(4, 15, 0, 5, 3));
        $this->addLeftSystem(new TacLaser(3, 5, 4, 240, 360));
        $this->addLeftSystem(new LightParticleBeamShip(2, 2, 1, 180, 360));
		
		$this->addRightSystem(new Thruster(4, 15, 0, 5, 4));
        $this->addRightSystem(new TacLaser(3, 5, 4, 0, 120));
        $this->addRightSystem(new LightParticleBeamShip(2, 2, 1, 0 , 180));
		        
        //0:primary, 1:front, 2:rear, 3:left, 4:right;
        $this->addFrontSystem(new Structure( 4, 40));
        $this->addAftSystem(new Structure( 4, 44));
        $this->addLeftSystem(new Structure( 4, 45));
        $this->addRightSystem(new Structure( 4, 45));
        $this->addPrimarySystem(new Structure( 5, 40));
    }
}
