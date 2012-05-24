<?php
class Nova extends BaseShip{
    
    function __construct($id, $userid, $name, $movement){
        parent::__construct($id, $userid, $name, $movement);
        
		$this->pointCost = 1350;
		$this->faction = "EA";
        $this->phpclass = "Nova";
        $this->imagePath = "ships/nova.png";
        $this->shipClass = "Nova Dreadnought";
        $this->shipSizeClass = 3;
        $this->canvasSize = 280;
		
        $this->forwardDefense = 16;
        $this->sideDefense = 17;
        
        $this->turncost = 1;
        $this->turndelaycost = 1.33;
        $this->accelcost = 3;
        $this->rollcost = 2;
        $this->pivotcost = 3;

        
        $this->addPrimarySystem(new Reactor(6, 40, 0, 0));
        $this->addPrimarySystem(new CnC(6, 16, 0, 0));
        $this->addPrimarySystem(new Scanner(6, 20, 4, 8));
        $this->addPrimarySystem(new Engine(6, 20, 0, 6, 3));
		$this->addPrimarySystem(new JumpEngine(6, 20, 3, 24));
		$this->addPrimarySystem(new Hangar(12, 26));
        
    
        
        $this->addFrontSystem(new Thruster(3, 10, 0, 4, 1));
        $this->addFrontSystem(new Thruster(3, 10, 0, 4, 1));
        
    
		//aft
		          

		
        $this->addAftSystem(new Thruster(4, 9, 0, 2, 2));
        $this->addAftSystem(new Thruster(4, 9, 0, 2, 2));
        $this->addAftSystem(new Thruster(4, 9, 0, 2, 2));
        $this->addAftSystem(new Thruster(4, 9, 0, 2, 2));
        
		//left
		
		$this->addLeftSystem(new Thruster(3, 15, 0, 5, 3));
              

		//right
		
		$this->addRightSystem(new Thruster(3, 15, 0, 5, 4));
        
		
		//structures
        $this->addFrontSystem(new Structure(6, 108));
        $this->addAftSystem(new Structure(5, 87));
        $this->addLeftSystem(new Structure(5, 96));
        $this->addRightSystem(new Structure(5, 96));
        $this->addPrimarySystem(new Structure(6, 50));
        
    }

}



