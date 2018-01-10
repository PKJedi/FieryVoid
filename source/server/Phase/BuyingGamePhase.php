<?php

class BuyingGamePhase implements Phase
{

    public function advance(TacGamedata $gameData, DBManager $dbManager)
    {
        $servergamedata = $dbManager->getTacGamedata($gameData->forPlayer, $gameData->id);


        $t1 = 0;
        $t2 = 0;

        foreach ($servergamedata->ships as $ship){

            $h = 3;
            if ($ship->team == 1){
                $t1++;
                $t = $t1;
                $h = 0;
            }else{
                $t2++;
                $t = $t2;
            }

            if ($t % 2 == 0){
                $y = $t/2;
            }else{
                $y = (($t-1)/2)*-1;
            }

            $x = -30;

            if ($ship->team == 2){
                $x=30;
            }



            $move = new MovementOrder(-1, "start", new OffsetCoordinate($x, $y), 0, 0, 5, $h, $h, true, 1, 0, 0);
            $ship->movement = array($move);

            foreach ($ship->systems as $system)
            {
                $system->setInitialSystemData($ship);
            }

        }

        $dbManager->insertShips($servergamedata->id, $servergamedata->ships);
        $dbManager->insertSystemData(SystemData::getAndPurgeAllSystemData());
    }

    public function process(TacGamedata $gameData, DBManager $dbManager, Array $ships)
    {
        $seenSlots = array();
        foreach($gameData->slots as $slot)
        {
            if ($gameData->hasAlreadySubmitted($gameData->forPlayer, $slot->slot))
                continue;

            $points = 0;
            foreach ($ships as $ship){

                if ($ship->slot != $slot->slot)
                    continue;

                $seenSlots[$slot->slot] = true;

                if (!$ship instanceof FighterFlight){
                    $points += $ship->pointCost;
                }


                else {
                    $points += ($ship->pointCost / 6) * $ship->flightSize;
                }

                if ($ship->userid == $gameData->forPlayer){
                    $id = $dbManager->submitShip($gameData->id, $ship, $gameData->forPlayer);

                    // Check if ship uses variable flight size
                    if($ship instanceof FighterFlight){
                        $dbManager->submitFlightSize($gameData->id, $id, $ship->flightSize);

                        $firstFighter = $ship->systems[1];
                        $ammo = false;

                        foreach ($firstFighter->systems as $weapon){
                            if(isset($weapon->missileArray)){
                                $ammo = $weapon->missileArray[1]->amount;
                                break;
                            }
                        }

                        if ($ammo){
                            foreach($ship->systems as $fighter){
                                foreach ($fighter->systems as $weapon){
                                    if(isset($weapon->missileArray)){
                                        $weapon->missileArray[1]->amount = $ammo;
                                        $dbManager->submitAmmo($id, $weapon->id, $gameData->id, $weapon->firingMode, $ammo);
                                    }
                                }
                            }
                        }
                        else if ($ship instanceof Templar){
                            foreach($ship->systems as $fighter){
                                foreach($fighter->systems as $weapon){
                                    if($weapon instanceof PairedGatlingGun){
                                        $dbManager->submitAmmo($id, $weapon->id, $gameData->id, $weapon->firingMode, $weapon->ammunition);
                                    }
                                }
                            }
                        }
                    }
                    else{
                        if (isset($ship->adaptiveArmour)){
                            $dbManager->submitAdaptiveArmour($gameData->id, $id);
                        }

                        foreach($ship->systems as $systemIndex=>$system){
                            if(isset($system->missileArray)){
                                // this system has a missileArray. It uses ammo
                                foreach($system->missileArray as $firingMode=>$ammo){
                                    $dbManager->submitAmmo($id, $system->id, $gameData->id, $firingMode, $ammo->amount);
                                }
                            }
                        }
                    }
                }
            }

            if ($points > $slot->points)
                throw new Exception("Fleet too expensive.");
        }

        $dbManager->updatePlayerStatus($gameData->id, $gameData->forPlayer, $gameData->phase, $gameData->turn, $seenSlots);
    }
}