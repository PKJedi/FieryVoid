<?php


class JaStat extends StarBaseTwoSides{

	function __construct($id, $userid, $name,  $slot){
		parent::__construct($id, $userid, $name,  $slot);

		$this->pointCost = 5000;
		$this->faction = "Narn";
		$this->phpclass = "JaStat";
		$this->shipClass = "Ja'Stat Warbase";
		$this->fighters = array("heavy"=>36); 

		$this->shipSizeClass = 3; //Enormous is not implemented
		$this->iniativebonus = -200; //no voluntary movement anyway
		$this->turncost = 0;
		$this->turndelaycost = 0;

		$this->forwardDefense = 20;
		$this->sideDefense = 20;

		$this->imagePath = "img/ships/jastat.png";
		$this->canvasSize = 280; //Enormous Starbase

		$this->locations = array(41, 42, 2, 32, 31, 1);
		$this->hitChart = array(			
			0=> array(
				10 => "Structure",
				12 => "Energy Mine",
				14 => "Energy Mine",
				16 => "Scanner",
				18 => "Reactor",
				20 => "C&C",
			)
		);

		$this->addPrimarySystem(new Reactor(6, 25, 0, 0));
		$this->addPrimarySystem(new CnC(6, 25, 0, 0)); 
		$this->addPrimarySystem(new CnC(6, 25, 0, 0)); 
		$this->addPrimarySystem(new Scanner(6, 28, 4, 8));
		$this->addPrimarySystem(new Scanner(6, 28, 4, 8));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));
		$this->addPrimarySystem(new EnergyMine(6, 5, 4, 0, 360));

		$this->addPrimarySystem(new Structure(6, 155));


		for ($i = 0; $i < sizeof($this->locations); $i++){

			$min = 0 + ($i*60);
			$max = 120 + ($i*60);

			$systems = array(
				new MagGun(5, 9, 8, $min, $max),
				new HeavyPulse(5, 6, 4, $min, $max),
				new HeavyLaser(5, 8, 6, $min, $max),
				new IonTorpedo(5, 5, 4, $min, $max),
				new TwinArray(5, 6, 2, $min, $max),
				new TwinArray(5, 6, 2, $min, $max),
				new LightPulse(5, 4, 2, $min, $max),
				new LightPulse(5, 4, 2, $min, $max),
				new CargoBay(5, 36),
				new SubReactor(5, 35, 0, 0),
				new Hangar(5, 7, 6),
				new Structure(5, 90)
			);

			$loc = $this->locations[$i];

			$this->hitChart[$loc] = array(
				1 => "Mag Gun",
				2 => "Heavy Pulse Cannon",
				3 => "Heavy Laser",
				4 => "Ion Torpedo",
				5 => "Twin Array",
				6 => "Light Pulse Cannon",
				8 => "Cargo Bay",
				9 => "Reactor",
				10 => "Hangar",
				18 => "Structure",
				20 => "Primary",
			);

			foreach ($systems as $system){
				$this->addSystem($system, $loc);
			}
		}
    }
}