<?php

	namespace nikipuh;

	use pocketmine\plugin\PluginBase;
	use pocketmine\math\Vector3;
	use pocketmine\item\Item;
	use pocketmine\inventory\Inventory;
	use pocketmine\command\Command;
	use pocketmine\command\CommandSender;
	use pocketmine\block\Block;
        use pocketmine\block\BlockFactory;
	use pocketmine\event\Listener;
	use pocketmine\event\block\BlockPlaceEvent;
	use pocketmine\Player;
        use pocketmine\item\ItemFactory;
        use pocketmine\nbt\tag\CompoundTag;
        use onebone\economyapi\EconomyAPI;

	class Main extends PluginBase implements Listener {

	public function onEnable() {
        $this->getServer()->getLogger()->info("§f===============§eWelcome to ChestHouse by LeNick!§f===============");
        $this->getServer()->getLogger()->info("§ePlease install EconomyS for this Plugin to work! - Else it will cause Errors...");
        $this->saveDefaultConfig();
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
        //Command
        public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch(strtolower($command->getName())) {
            case "buyhouse":
		if (count($args) == 2) {
		$houseprice = $this->getConfig()->get("price-for-a-house");
                $housecost = $houseprice * $args[1];
                $money = EconomyAPI::getInstance()->myMoney($sender);
                $name = mb_strtolower($args[0]);
                $count = mb_strtolower($args[1]);
                $item = ItemFactory::get(Item::CHEST, 0, $count);
                $item->setCustomName("§dChest§bHouse");
                $item->setNamedTagEntry(new CompoundTag("ChestHouse", []));
		$player = $this->getServer()->getPlayer($name);
                if ($player instanceof Player){
		if($money < $housecost){
			$sender->sendMessage("§cNot enough money!");
		}else{
			$sender->sendMessage("§aYou bought $count chesthouse/s!");
			EconomyAPI::getInstance()->reduceMoney($sender, $housecost);
			$player->getInventory()->addItem($item);
                }
                }
                }else{ 
			$houseprice = $this->getConfig()->get("price-for-a-house");
                        $sender->sendMessage("§ePrice: $houseprice\n§fUse: /buyhouse <player> <amount>");
		}
			break;
			
                case "info":
                    $sender->sendMessage(" §eChesthouse Plugin by LeNick ");
                    $sender->sendMessage(" §aEconomy-Version for awaken.play-skyblock.tk");
                    $sender->sendMessage(" §ehttps://github.com/nikipuh ");
                break;
        }
       return true; }
ic function onBlockPlace(BlockPlaceEvent $event){
            $item = mb_strtolower($event->getItem()->getCustomName());
            if ($event->getItem()->getNamedTagEntry("ChestHouse")) {
                $event->setCancelled(true);
                $player = $event->getPlayer();
                $user = mb_strtolower($player->getName());
                $block = $event->getBlock();
                $item = $event->getItem();
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item);
                $x = $player->getFloorX() - 1;
                $y = $player->getFloorY() - 1;
                $z = $player->getFloorZ() - 1;
                $level = $block->level;
// REPLACE
				$x     = $player->getFloorX() - 1;
				$y     = $player->getFloorY() - 1;
				$z     = $player->getFloorZ() - 1;
				$level = $block->level;

				for($x1 = $x - 1; $x1 < $x + 4; $x1++)
					for($y1 = $y; $y1 < $y + 3; $y1++)
						for($z1 = $z - 1; $z1 < $z + 4; $z1++) {
							$v3 = new Vector3($x1, $y1, $z1);
							$saveBlock = $level->getBlock(new Vector3($x1, $y1, $z1));
							$this->open[$user]['coords'][] = $saveBlock;
						        $this->open[$user]['coords']["$x1:$y1:$z1"] = $saveBlock;
						}

				$newBlock = BlockFactory::get(5);
				for($x1 = $x; $x1 < $x + 3; $x1++) {
					for($z1 = $z; $z1 < $z + 3; $z1++) {
						$v3 = new Vector3($x1, $y, $z1);
						$level->setBlock($v3, $newBlock);
					}
					$z1 = $z;
				}

				$y++;
				$air = BlockFactory::get(0);
				for($x1 = $x; $x1 < $x + 3; $x1++)
					for($z1 = $z; $z1 < $z + 3; $z1++)
						$level->setBlock(
							new Vector3($x1, $y, $z1),
							$air
						);

				$y++;
				$air = BlockFactory::get(0);
				for($x1 = $x - 1; $x1 < $x + 4; $x1++)
					for($z1 = $z - 1; $z1 < $z + 4; $z1++)
						$level->setBlock(
							new Vector3($x1, $y, $z1),
							$air
						);
			    $y--;$x--; $z--;
                            $wall = 5;
                            $roof = 17;
                            $floor = 1;
                            $cobble = 4;
                            $level->setBlockIdAt($x, $y, $z, $cobble);
                            $level->setBlockIdAt($x, $y + 1, $z, $cobble);
                            $level->setBlockIdAt($x + 1, $y, $z, $wall);
                            $level->setBlockIdAt($x + 1, $y + 1, $z, $wall);

                            $level->setBlockIdAt($x + 2, $y, $z, 64); //door
                            $level->setBlockIdAt($x + 2, $y+1, $z, 64,8); //upperdoor

                            $level->setBlockIdAt($x + 3, $y, $z, $wall);
                            $level->setBlockIdAt($x + 3, $y + 1, $z, $wall);
                            $level->setBlockIdAt($x, $y, $z + 1, $wall);
                            $level->setBlockIdAt($x, $y + 1, $z + 1, $wall);
                            $level->setBlockIdAt($x, $y, $z + 2, $wall);
                            $level->setBlockIdAt($x, $y + 1, $z + 2, 102);
                            $level->setBlockIdAt($x, $y, $z + 3, $wall);
                            $level->setBlockIdAt($x, $y + 1, $z + 3, $wall);
                            $level->setBlockIdAt($x + 4, $y, $z, $cobble);
                            $level->setBlockIdAt($x + 4, $y + 1, $z, $cobble);
                            $level->setBlockIdAt($x + 4, $y, $z + 1, $wall);
                            $level->setBlockIdAt($x + 4, $y + 1, $z + 1, $wall);
                            $level->setBlockIdAt($x + 4, $y, $z + 2, $wall);
                            $level->setBlockIdAt($x + 4, $y + 1, $z + 2, 102);
                            $level->setBlockIdAt($x + 4, $y, $z + 3, $wall);
                            $level->setBlockIdAt($x + 4, $y + 1, $z + 3, $wall);
                            $level->setBlockIdAt($x, $y, $z + 4, $cobble);
                            $level->setBlockIdAt($x, $y + 1, $z + 4, $cobble);
                            $level->setBlockIdAt($x + 1, $y, $z + 4, $wall);
                            $level->setBlockIdAt($x + 1, $y + 1, $z + 4, $wall);
                            $level->setBlockIdAt($x + 2, $y, $z + 4, $wall);
                            $level->setBlockIdAt($x + 2, $y + 1, $z + 4, 102);
                            $level->setBlockIdAt($x + 3, $y, $z + 4, $wall);
                            $level->setBlockIdAt($x + 3, $y + 1, $z + 4, $wall);
                            $level->setBlockIdAt($x + 4, $y, $z + 4, $cobble);
                            $level->setBlockIdAt($x + 4, $y + 1, $z + 4, $cobble);

                            //EBENE2
                            $level->setBlockIdAt($x, $y+2, $z, $cobble);
                            $level->setBlockIdAt($x+1, $y+2, $z, $wall);
                            $level->setBlockIdAt($x+2, $y+2, $z, $wall);
                            $level->setBlockIdAt($x+3, $y+2, $z, $wall);
                            $level->setBlockIdAt($x+4, $y+2, $z, $cobble);

                            $level->setBlockIdAt($x, $y+2, $z+4, $cobble);
                            $level->setBlockIdAt($x+1, $y+2, $z+4, $wall);
                            $level->setBlockIdAt($x+2, $y+2, $z+4, $wall);
                            $level->setBlockIdAt($x+3, $y+2, $z+4, $wall);
                            $level->setBlockIdAt($x+4, $y+2, $z+4, $cobble);

                            $level->setBlockIdAt($x, $y+2, $z+1, $wall);
                            $level->setBlockIdAt($x+4, $y+2, $z+1, $wall);
                            $level->setBlockIdAt($x, $y+2, $z+2, $wall);
                            $level->setBlockIdAt($x+4, $y+2, $z+2, $wall);
                            $level->setBlockIdAt($x, $y+2, $z+3, $wall);
                            $level->setBlockIdAt($x+4, $y+2, $z+3, $wall);

                            //DACH oder EBENE3
                            $level->setBlockIdAt($x, $y+3, $z, $roof);
                            $level->setBlockIdAt($x+1, $y+3, $z, $roof);
                            $level->setBlockIdAt($x+2, $y+3, $z, $roof);
                            $level->setBlockIdAt($x+3, $y+3, $z, $roof);
                            $level->setBlockIdAt($x+4, $y+3, $z, $roof);

                            $level->setBlockIdAt($x, $y+3, $z+1, $roof);
                            $level->setBlockIdAt($x+1, $y+3, $z+1, $wall);
                            $level->setBlockIdAt($x+2, $y+3, $z+1, $wall);
                            $level->setBlockIdAt($x+3, $y+3, $z+1, $wall);
                            $level->setBlockIdAt($x+4, $y+3, $z+1, $roof);

                            $level->setBlockIdAt($x, $y+3, $z+2, $roof);
                            $level->setBlockIdAt($x+1, $y+3, $z+2, $wall);
                            $level->setBlockIdAt($x+2, $y+3, $z+2, $wall);
                            $level->setBlockIdAt($x+3, $y+3, $z+2, $wall);
                            $level->setBlockIdAt($x+4, $y+3, $z+2, $roof);

                            $level->setBlockIdAt($x, $y+3, $z+3, $roof);
                            $level->setBlockIdAt($x+1, $y+3, $z+3, $wall);
                            $level->setBlockIdAt($x+2, $y+3, $z+3, $wall);
                            $level->setBlockIdAt($x+3, $y+3, $z+3, $wall);
                            $level->setBlockIdAt($x+4, $y+3, $z+3, $roof);

                            $level->setBlockIdAt($x, $y+3, $z+4, $roof);
                            $level->setBlockIdAt($x+1, $y+3, $z+4, $roof);
                            $level->setBlockIdAt($x+2, $y+3, $z+4, $roof);
                            $level->setBlockIdAt($x+3, $y+3, $z+4, $roof);
                            $level->setBlockIdAt($x+4, $y+3, $z+4, $roof);
                            //ENDE DACH oder EBENE3

                            //COBBLEBODEN
                            $level->setBlockIdAt($x, $y-1, $z, $cobble);
                            $level->setBlockIdAt($x+1, $y-1, $z, $cobble);
                            $level->setBlockIdAt($x+2, $y-1, $z, $cobble);//under door
                            $level->setBlockIdAt($x+3, $y-1, $z, $cobble);
                            $level->setBlockIdAt($x+4, $y-1, $z, $cobble);

                            $level->setBlockIdAt($x, $y-1, $z+4, $cobble);
                            $level->setBlockIdAt($x+1, $y-1, $z+4, $cobble);
                            $level->setBlockIdAt($x+2, $y-1, $z+4, $cobble);
                            $level->setBlockIdAt($x+3, $y-1, $z+4, $cobble);
                            $level->setBlockIdAt($x+4, $y-1, $z+4, $cobble);

                            $level->setBlockIdAt($x, $y-1, $z+1, $cobble);
                            $level->setBlockIdAt($x+4, $y-1, $z+1, $cobble);
                            $level->setBlockIdAt($x, $y-1, $z+2, $cobble);
                            $level->setBlockIdAt($x+4, $y-1, $z+2, $cobble);
                            $level->setBlockIdAt($x, $y-1, $z+3, $cobble);
                            $level->setBlockIdAt($x+4, $y-1, $z+3, $cobble);

                        }
            }
        }

?>
