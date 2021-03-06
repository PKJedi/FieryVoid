<?php
class swAssaultFrigateSmallCap extends BaseShipNoAft{
    
    function __construct($id, $userid, $name,  $slot){
        parent::__construct($id, $userid, $name,  $slot);
        
	$this->pointCost = 775;
	$this->faction = "StarWars Galactic Empire";
        $this->phpclass = "swAssaultFrigateSmallCap";
        $this->imagePath = "img/starwars/assaultfrigate.png";
	    //$this->canvasSize = 100;
        $this->shipClass = "Rebel Assault Frigate";
	
	$this->fighters = array("superheavy" => 1);

	$this->unofficial = true;
        
        $this->forwardDefense = 15;
        $this->sideDefense = 17;
        
        $this->turncost = 1;
        $this->turndelaycost = 0.75;
        $this->accelcost = 4;
        $this->rollcost = 2;
        $this->pivotcost = 4;
	$this->iniativebonus = 10; 
        
	$this->addPrimarySystem(new CnC(5, 12, 0, 0));
        $this->addPrimarySystem(new Reactor(5, 18, 0, 0));
        $this->addPrimarySystem(new SWScanner(4, 12, 6, 6));
        $this->addPrimarySystem(new Engine(4, 15, 0, 10, 3));
	$this->addPrimarySystem(new Catapult(1, 6));   
        $this->addPrimarySystem(new Thruster(3, 12, 0, 4, 2));
        $this->addPrimarySystem(new Thruster(3, 12, 0, 4, 2));
	$this->addPrimarySystem(new Thruster(3, 12, 0, 4, 2));
	$hyperdrive = new JumpEngine(4, 16, 5, 15);
	$hyperdrive->displayName = 'Hyperdrive';
	$this->addPrimarySystem($hyperdrive);

        $this->addFrontSystem(new Thruster(3, 15, 0, 4, 1));
        $this->addFrontSystem(new Thruster(3, 15, 0, 4, 1));
	$this->addFrontSystem(new SWRayShield(3,15,9,3,300,60)); //$armour, $maxhealth, $powerReq, $shieldFactor, $startArc, $endArc
	$this->addFrontSystem(new SWLightTLaser(2, 240, 0, 4)); //armor, arc and number of weapon in common housing: structure and power data are calculated! 
	$this->addFrontSystem(new SWHeavyTLaser(2, 270, 30, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addFrontSystem(new SWHeavyTLaser(2, 300, 60, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addFrontSystem(new SWHeavyTLaser(2, 330, 90, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addFrontSystem(new SWLightTLaser(2, 0, 120, 4)); //armor, arc and number of weapon in common housing: structure and power data are calculated!

	$this->addLeftSystem(new Thruster(3, 14, 0, 6, 3));
	$this->addLeftSystem(new SWRayShield(2,12,6,2,180,300)); //$armour, $maxhealth, $powerReq, $shieldFactor, $startArc, $endArc
	$this->addLeftSystem(new SWLightTLaser(2, 240, 0, 4)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addLeftSystem(new SWLightTLaser(2, 240, 0, 4)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addLeftSystem(new SWHeavyLaser(3, 180, 0, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addLeftSystem(new SWHeavyLaser(3, 180, 0, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addLeftSystem(new SWHeavyTLaser(3, 240, 0, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!

	$this->addRightSystem(new Thruster(3, 14, 0, 6, 4));
	$this->addRightSystem(new SWRayShield(2,12,6,2,60,180)); //$armour, $maxhealth, $powerReq, $shieldFactor, $startArc, $endArc
	$this->addRightSystem(new SWLightTLaser(2, 0, 120, 4)); //armor, arc and number of weapon in common housing: structure and power data are calculated!	    
	$this->addRightSystem(new SWLightTLaser(2, 0, 120, 4)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addRightSystem(new SWHeavyLaser(2, 0, 180, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addRightSystem(new SWHeavyLaser(2, 0, 180, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
	$this->addRightSystem(new SWHeavyTLaser(2, 0, 120, 3)); //armor, arc and number of weapon in common housing: structure and power data are calculated!
    
	    
        $this->addFrontSystem(new Structure( 4, 50));
        $this->addLeftSystem(new Structure( 4, 40));
        $this->addRightSystem(new Structure( 4, 40));
        $this->addPrimarySystem(new Structure( 4, 50));
	    
	    
        $this->hitChart = array(
        		0=> array(
					10 => "Structure",
        				12 => "Thruster",
		        		13 => "Hyperdrive",
					14=> "Catapult",
        				16 => "Scanner",
        				18 => "Engine",
        				19 => "Reactor",
					20 => "C&C",
        		),
        		1=> array(
        				2 => "Thruster",
        				3 => "Ray Shield",
        				8 => "Heavy Turbolaser",
 					12 => "Light Turbolaser",
        				18 => "Structure",
        				20 => "Primary",
        		),
        		3=> array(
        				2 => "Thruster",
        				3 => "Ray Shield",
        				7 => "Heavy Turbolaser",	
					10 => "Light Turbolaser",
		        		13 => "Heavy Laser",
        				18 => "Structure",
        				20 => "Primary",
        		),
        		4=> array(
        				2 => "Thruster",
        				3 => "Ray Shield",
        				7 => "Heavy Turbolaser",	
					10 => "Light Turbolaser",
		        		13 => "Heavy Laser",
        				18 => "Structure",
        				20 => "Primary",
        		),
        );
	    
	    
    }
}
?>
