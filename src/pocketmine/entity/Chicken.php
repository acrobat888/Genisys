<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\entity;

use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item as ItemItem;

class Chicken extends Animal
{
    const NETWORK_ID = 10;
    const STEPS_FORWARD = 20;

    public $width = 0.6;
    public $length = 0.6;
    public $height = 1.8;
    public $isFollowing = false;
    public $stepsForward = Chicken::STEPS_FORWARD;

    public $dropExp = [1, 3];

    public function getName() : string
    {
        return "Chicken";
    }

    public function spawnTo(Player $player)
    {
        $pk = new AddEntityPacket();
        $pk->eid = $this->getId();
        $pk->type = Chicken::NETWORK_ID;
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->metadata = $this->dataProperties;
        $player->dataPacket($pk);
        parent::spawnTo($player);
    }

    public function getDrops()
    {
        $drops = [];
        if ($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player) {

            switch (\mt_rand(0, 2)) {
                case 0:
                    $drops[] = ItemItem::get(ItemItem::RAW_CHICKEN, 0, 1);
                    break;
                case 1:
                    $drops[] = ItemItem::get(ItemItem::FEATHER, 0, 1);
                    break;
                case 2:
                    $drops[] = ItemItem::get(ItemItem::FEATHER, 0, 2);
                    break;
            }
        }
        return $drops;
    }
/*
    public function onUpdate($tick)
    {
        if ($this->attackingTick > 0) {
            $this->attackingTick--;
        }
        if (!$this->isAlive() and $this->hasSpawned) {
            ++$this->deadTicks;
            if ($this->deadTicks >= 20) {
                $this->despawnFromAll();
            }
            return true;
        }
        if ($this->isAlive()) {
            $this->motionY -= $this->gravity;

            // $level->getServer()->getLogger()->info("生成岩浆中 " . "floor($x)" . ", " . "floor($y)" . ", " . floor($z));
            // $this->server->getLogger()->info("moving ".get_class($this)." x:($this->motionX) y:($this->motionY) z:($this->motionZ)");
            $this->move($this->motionX, $this->motionY, $this->motionZ);

            $friction = 1 - $this->drag;

            if($this->onGround and (abs($this->motionX) > 0.00001 or abs($this->motionZ) > 0.00001)){
                $friction = $this->getLevel()->getBlock($this->temporalVector->setComponents((int) floor($this->x), (int) floor($this->y - 1), (int) floor($this->z) - 1))->getFrictionFactor() * $friction;
            }

            // $this->server->getLogger()->info("friction = {$friction}\tdrag = {$this->drag}");

            if ($this->stepsForward > 0) {
                $this->motionX = 0.1;
                $this->motionY *= 1 - $this->drag;
                $this->motionZ = 0.1;

                if($this->onGround){
                    $this->motionY *= -0.5;
                }

               $this->stepsForward -= 1;

            } else {
                $this->motionX += 0.5;
                $this->motionY *= 1 - $this->drag;
                $this->motionZ += 0.5;

                if($this->onGround){
                    $this->motionY *= -0.5;
                }

                $this->stepsForward = Chicken::STEPS_FORWARD;
            }

            $this->updateMovement();
        }

        parent::entityBaseTick();
        // return parent::onUpdate($tick);
        return true;
    }
*/
}
