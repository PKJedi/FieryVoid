<?php
	class Fighter extends ShipSystem{
		
		
		public $flightid;
		public $location = 0;
		public $id, $armour, $maxhealth, $powerReq, $output, $name, $displayName;
		public $damage = array();
		public $outputMod = 0;
		public $boostable = false;
		public $power = array();
		public $data = array();
		public $critData = array();
		public $fighter = true;
		public $systems = array();
		
		public $possibleCriticals = array();
		
			
		public $criticals = array();
		
		
		function __construct($name, $armour, $maxhealth, $flight){
			parent::__construct($armour, $maxhealth, 0, 0 );
			
                    $this->name = $name;
                    $this->flightid = $flight;
			
			
		}
        
        public function getSpecialAbilityList($list)
        {
            if ($this->isDestroyed())
                return $list;
            
            foreach ($this->systems as $system)
            {
                if ($system instanceof SpecialAbility)
                {
                    foreach ($system->specialAbilities as $effect)
                    {
                        if (!isset($list[$effect]))
                        {
                            $list[$effect] = $system->id;
                        }
                    }
                }
            }
            return $list;
        }
        
        public function getSystemById($id){
            foreach ($this->systems as $system){
                if ($system->id == $id){
                    return $system;
                }
            }
            return null;
        }
        
        public function isDisengaged($turn){
            if ($this->hasCritical("DisengagedFighter", $turn))
				return true;
        }
			
        public function isDestroyed($turn = false){
            if ($this->isDisengaged($turn))
                return true;
            
            return parent::isDestroyed();
        }
		
	public function addFrontSystem($system){
			$this->addSystem($system, 1);
        }
        
        public function addAftSystem($system){
		$this->addSystem($system, 2);
        }
		
		
	protected function addSystem($system, $loc){
            $system->location = $loc;
            $this->systems[] = $system;
        }
			
		public function setSystemDataWindow($turn){
			parent::setSystemDataWindow($turn);			
			foreach ($this->systems as $system){
				$system->setSystemDataWindow($turn);	
			}
		}
		
		public function onConstructed($ship, $turn, $phase){
			parent::onConstructed($ship, $turn, $phase);	
			foreach ($this->systems as $system){
				$system->onConstructed($ship, $turn, $phase);
			}
     
		}
		
	public function testCritical($ship, $gamedata, $crits, $add = 0){
		$d = Dice::d(10);
		
		$bonusCrit = 0;	//one-time penalty to dropout roll
		foreach($crits as $key=>$value) {
		  if($value instanceof NastierCrit){
			$bonusCrit+= $value->$outputMod;
			  unset($crits[$key]);
		  }
		}
		$crits = array_values($crits); //in case some criticals were deleted!
		
		$dropOutBonus = $gamedata->getShipById($this->flightid)->getDropOutBonus();
		if (($d + $dropOutBonus - $bonusCrit) > $this->getRemainingHealth()){
			$crit = new DisengagedFighter(-1, $ship->id, $this->id, "DisengagedFighter", $gamedata->turn);
			$crit->updated = true;
			$this->criticals[] =  $crit;
			$crits[] = $crit;
		}
		return $crits;
	}
		
		
		public function isOfflineOnTurn($turn = null){
			return false;
		}
		
		public function isOverloadingOnTurn($turn = null){
			return false;
		}
		
		
		
	public function getArmourPos($gamedata, $pos){ 
		$target = $gamedata->getShipById($this->flightid); 
		$loc = $target->doGetHitSectionPos($pos); //finds array with relevant data!
		return $loc["armour"];
	}
        
        public function getArmour($target, $shooter, $dmgType){ //for fighter no need to note where fire went, as all calculations are done on raw flight data
		$loc = $target->doGetHitSection($shooter); //finds array with relevant data!
		return $loc["armour"];
        }
		
		
		
        
		
		
        public function onAdvancingGamedata($ship)
        {
            foreach ($this->systems as $system)
            {
                $system->onAdvancingGamedata($ship);
            }
        }


        public function setInitialSystemData($ship)
        {
            foreach ($this->systems as $system)
            {
                $system->setInitialSystemData($ship);
            }
        }
        

	}

?>
