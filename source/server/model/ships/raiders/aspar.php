<?php
class Aspar extends HeavyCombatVessel{
    
    function __construct($id, $userid, $name,  $slot){
        parent::__construct($id, $userid, $name,  $slot);
        
        $this->pointCost = 360;
        $this->faction = "Raiders";
        $this->phpclass = "Aspar";
        $this->imagePath = "img/ships/brigantine.png"; //need to change
        $this->shipClass = "Aspar Corvette";
        $this->occurence = "common";
        $this->fighters = array("normal"=>12);
        
        $this->forwardDefense = 18;
        $this->sideDefense = 18;
        
        $this->turncost = 1;
        $this->turndelaycost = 1;
        $this->accelcost = 4;
        $this->rollcost = 999;
        $this->pivotcost = 999;
        $this->iniativebonus = 0;
        
        $this->addPrimarySystem(new Reactor(3, 20, 0, 0));
        $this->addPrimarySystem(new CnC(3, 8, 0, 0));
        $this->addPrimarySystem(new Scanner(3, 8, 4, 4));
        $this->addPrimarySystem(new Engine(3, 9, 0, 6, 4));
        $tempAdd = new CargoBay(2, 28);
        $tempAdd->displayName = "cargoBayA";
        $this->addPrimarySystem($tempAdd);
        $tempAdd2 = new CargoBay(2, 28);
        $tempAdd2->displayName = "cargoBayB";
		$this->addPrimarySystem($tempAdd2);       
        $this->addPrimarySystem(new Thruster(3, 11, 0, 4, 3));
        $this->addPrimarySystem(new Thruster(3, 11, 0, 4, 4));
		$this->addPrimarySystem(new LightParticleBeamShip(1, 2, 1, 240, 0));
		$this->addPrimarySystem(new LightParticleBeamShip(1, 2, 1, 0, 120));
		
        $this->addFrontSystem(new Thruster(3, 6, 0, 3, 1));
        $this->addFrontSystem(new Thruster(3, 6, 0, 3, 1));
		$this->addFrontSystem(new PlasmaTorch(1, 4, 2, 240, 0));
		$this->addFrontSystem(new PlasmaTorch(1, 4, 2, 0, 120));
		$this->addFrontSystem(new LightLaser(2, 4, 3, 270, 90));		
		$this->addFrontSystem(new MediumLaser(2, 6, 5, 240, 0));
		$this->addFrontSystem(new MediumLaser(2, 6, 5, 0, 120));
		$this->addFrontSystem(new LightParticleBeamShip(1, 2, 1, 240, 0));
		$this->addFrontSystem(new LightParticleBeamShip(1, 2, 1, 0, 120));
		
        $this->addAftSystem(new Thruster(3, 8, 0, 3, 2));
        $this->addAftSystem(new Thruster(3, 8, 0, 3, 2));
        $this->addAftSystem(new LightParticleBeamShip(1, 2, 1, 180, 300));
        $this->addAftSystem(new LightParticleBeamShip(1, 2, 1, 60, 180));
        $this->addAftSystem(new LightLaser(2, 4, 3, 9, 270));
        $this->addAftSystem(new Hangar(3, 3));
        $this->addAftSystem(new Hangar(3, 3));
        $this->addAftSystem(new Hangar(3, 3));
        
        //0:primary, 1:front, 2:rear, 3:left, 4:right;
        $this->addFrontSystem(new Structure( 3, 32));
        $this->addPrimarySystem(new Structure( 3, 36));
        $this->addAftSystem(new Structure( 3, 32));
        
        $this->hitChart = array(
        		0=> array(
        				1 => "structure",
        				2 => "structure",
        				3 => "structure",
        				4 => "structure",
        				5 => "structure",
        				6 => "thruster",
        				7 => "thruster",
        				8 => "Light Particle Beam",
        				9 => "cargoBayA",
        				10 => "cargoBayA",
        				11 => "cargoBayB",
        				12 => "cargoBayB",
        				13 => "scanner",
        				14 => "scanner",
        				15 => "engine",
        				16 => "engine",
        				17 => "reactor",
        				18 => "reactor",
        				19 => "C&C",
        				20 => "C&C",
        		),
        		1=> array(
        				1 => "thruster",
        				2 => "thruster",
        				3 => "thruster",
        				4 => "Plasma Torch",
        				5 => "Plasma Torch",
        				6 => "Light Particle Beam",
        				7 => "Light laser",
        				8 => "Medium laser",
        				9 => "Medium laser",
        				10 => "Medium laser",
        				11 => "structure",
        				12 => "structure",
        				13 => "structure",
        				14 => "structure",
        				15 => "structure",
        				16 => "structure",
        				17 => "structure",
        				18 => "primary",
        				19 => "primary",
        				20 => "primary",
        		),
        		2=> array(
        				1 => "thruster",
        				2 => "thruster",
        				3 => "thruster",
        				4 => "thruster",
        				5 => "Light Particle Beam",
        				6 => "Light Particle Beam",
        				7 => "Light laser",
        				8 => "Light laser",
        				9 => "hangar",
        				10 => "hangar",
        				11 => "structure",
        				12 => "structure",
        				13 => "structure",
        				14 => "structure",
        				15 => "structure",
        				16 => "structure",
        				17 => "structure",
        				18 => "primary",
        				19 => "primary",
        				20 => "primary",
        		),
        );
    }
}


?>
