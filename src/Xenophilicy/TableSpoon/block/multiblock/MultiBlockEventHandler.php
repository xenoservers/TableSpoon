<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace Xenophilicy\TableSpoon\block\multiblock;

use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;

/**
 * Class MultiBlockEventHandler
 * @package Xenophilicy\TableSpoon\block\multiblock
 */
final class MultiBlockEventHandler implements Listener {
    
    /**
     * @param BlockUpdateEvent $event
     * @priority NORMAL
     */
    public function onBlockUpdate(BlockUpdateEvent $event): void{
        $block = $event->getBlock();
        $multiBlock = MultiBlockFactory::get($block);
        if($multiBlock !== null && $multiBlock->update($block)){
            $event->setCancelled();
        }
    }
    
    /**
     * @param PlayerInteractEvent $event
     * @priority NORMAL
     */
    public function onPlayerInteract(PlayerInteractEvent $event): void{
        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            $block = $event->getBlock();
            $multiBlock = MultiBlockFactory::get($block);
            if($multiBlock !== null && $multiBlock->interact($block, $event->getPlayer(), $event->getItem(), $event->getFace())){
                $event->setCancelled();
            }
        }
    }
    
    /**
     * @param PlayerMoveEvent $event
     * @priority MONITOR
     */
    public function onPlayerMove(PlayerMoveEvent $event): void{
        $from = $event->getFrom();
        $fromFloor = $from->floor();
        $to = $event->getTo();
        $toFloor = $to->floor();
        if($fromFloor->equals($toFloor)) return;
        $player = $event->getPlayer();
        $fromBlock = MultiBlockFactory::get($block = $from->level->getBlockAt($fromFloor->x, $fromFloor->y, $fromFloor->z));
        if($fromBlock !== null){
            $fromBlock->onPlayerMoveOutside($player, $block);
        }
        $toBlock = MultiBlockFactory::get($block = $to->level->getBlockAt($toFloor->x, $toFloor->y, $toFloor->z));
        if($toBlock !== null){
            $toBlock->onPlayerMoveInside($player, $block);
        }
    }
}