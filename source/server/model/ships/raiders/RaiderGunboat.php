<?php
class RaiderGunboat extends MediumShip{
	/*Raider Gunboat LCV, from Raiders*/
	/*approximated as MCV, no EW restrictions*/
    function __construct($id, $userid, $name,  $slot){
        parent::__construct($id, $userid, $name,  $slot);
        
	$this->pointCost = 200;
        $this->faction = "Raiders";
	$this->phpclass = "RaiderGunboat";
	$this->shipClass = "Gunboat";
	$this->imagePath = "img/ships/RaiderLCV.png";
	$this->canvasSize = 100;
	$this->agile = true;
	$this->forwardDefense = 10;
	$this->sideDefense = 11;
	$this->isd = 2218;
	//$this->unofficial = true;
	$this->turncost = 0.25;
	$this->turndelaycost = 0.25;
	$this->accelcost = 1;
	$this->rollcost = 1;
	$this->pivotcost = 1;
	$this->iniativebonus = 14 *5;
  
	$this->addFrontSystem(new InvulnerableThruster(99, 99, 0, 99, 1)); //unhitable and with unlimited thrust allowance
	$this->addAftSystem(new InvulnerableThruster(99, 99, 0, 99, 3)); //unhitable and with unlimited thrust allowance
	$this->addAftSystem(new InvulnerableThruster(99, 99, 0, 99, 2)); //unhitable and with unlimited thrust allowance
	$this->addAftSystem(new InvulnerableThruster(99, 99, 0, 99, 4)); //unhitable and with unlimited thrust allowance
  
	$this->addPrimarySystem(new Reactor(4, 9, 0, 0));
	$this->addPrimarySystem(new CnC(99, 99, 0, 0)); //C&C should be unhittable anyway
	$this->addPrimarySystem(new Scanner(4, 12, 3, 4));
	$this->addPrimarySystem(new Engine(4, 13, 0, 6, 1));
	$this->addPrimarySystem(new StdParticleBeam(2, 4, 1, 180, 0));
	$this->addPrimarySystem(new ParticleCannon(3, 8, 7, 300, 60));
	$this->addPrimarySystem(new StdParticleBeam(2, 4, 1, 0, 180));
	$this->addPrimarySystem(new Structure( 5, 31));
  
        $this->hitChart = array(
        		0=> array( //should never happen
        				20 => "Structure",
        		),
        		1=> array( //PRIMARY hit table, effectively
        				11 => "Structure",
        				13 => "0:Particle Cannon",
        				16 => "0:Standard Particle Beam",
        				18 => "0:Engine",
        				19 => "0:Reactor",
        				20 => "0:Scanner",
        		),
        		2=> array( //same as Fwd
        				11 => "Structure",
        				13 => "0:Particle Cannon",
        				16 => "0:Standard Particle Beam",
        				18 => "0:Engine",
        				19 => "0:Reactor",
        				20 => "0:Scanner",
        		),
        		
        ); //end of hit chart
    }
}
?>
