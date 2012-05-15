<?php
class Haven extends MediumShip{
    
    function __construct($id, $userid, $name,  $movement){
        parent::__construct($id, $userid, $name,  $movement);
        
		$this->pointCost = 325;
		$this->faction = "Centauri";
        $this->phpclass = "Haven";
        $this->imagePath = "ships/haven.png";
        $this->shipClass = "Haven";
        $this->agile = true;
        $this->canvasSize = 100;
        
        $this->forwardDefense = 11;
        $this->sideDefense = 12;
        
        $this->turncost = 0.33;
        $this->turndelaycost = 0.33;
        $this->accelcost = 2;
        $this->rollcost = 1;
        $this->pivotcost = 2;
		$this->iniativebonus = 70;
        
         
        $this->addPrimarySystem(new Reactor(4, 10, 0, 0));
        $this->addPrimarySystem(new CnC(4, 8, 0, 0));
        $this->addPrimarySystem(new Scanner(3, 10, 3, 7));
        $this->addPrimarySystem(new Engine(3, 9, 0, 10, 2));
		$this->addPrimarySystem(new Hangar(4, 1));
		$this->addPrimarySystem(new Thruster(2, 10, 0, 4, 3));
		$this->addPrimarySystem(new Thruster(2, 10, 0, 4, 4));
		
        
		
        $this->addFrontSystem(new Thruster(2, 8, 0, 2, 1));
        $this->addFrontSystem(new Thruster(2, 8, 0, 2, 1));
        $this->addFrontSystem(new TwinArray(3, 6, 2, 180, 60));
        $this->addFrontSystem(new TwinArray(3, 6, 2, 300, 180));
		$this->addFrontSystem(new MatterCannon(3, 7, 4, 300, 60));
		
		
		
        $this->addAftSystem(new Thruster(2, 10, 0, 5, 2));
        $this->addAftSystem(new Thruster(2, 10, 0, 5, 2));
		$this->addAftSystem(new TwinArray(3, 6, 2, 120, 0));
        $this->addAftSystem(new TwinArray(3, 6, 2, 0, 240));
		

        
        
        
       
        $this->addPrimarySystem(new Structure( 4, 33));
		
		
    }

}



?>
