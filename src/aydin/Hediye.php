<?php

namespace aydin;

use pocketmine\{
  player\Player,
  Server,
  plugin\PluginBase,
  event\Listener,
  utils\Config,
  command\Command,
  command\CommandSender,
  item\Item,
  item\ItemFactory,
  item\ItemBlock,
  event\player\PlayerJoinEvent,
};
use jojoe77777\FormAPI\{
  SimpleForm,
  CustomForm
};
class Hediye extends PluginBase implements Listener{
  public static $cfg;
  public function onEnable():void{
    $this->getLogger()->info("Hediye Aktif");
    self::$cfg = new Config($this->getDataFolder(). "hediye.yml", Config::YAML);
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  public function onCommand(CommandSender $player, Command $kmt, string $lbl, array $args):bool{
   
    if($kmt->getName() == "hediye"){
if($player->getInventory()->getItemInHand()->getId() != 0){
    $this->hediye($player);
   }else{
       $player->sendMessage("§cElin boş");
      
    }
}
    return true;
  }
  public function hediye(Player $player){
      $list = []; foreach($this->getServer()->getOnlinePlayers() as $oyuncu){
          $list[] = $oyuncu->getName();
          $playerList = $list;
      }
    $form = new CustomForm(function(Player $player, $data) use ($list, $playerList){
      if($data === null) return true;
      
      $item = $player->getInventory()->getItemInHand();
      $oyuncu = $this->getServer()->getPlayerExact($playerList[$data[1]]);
      
        $d3 = $data[3];
      
      if($player->getName() != $oyuncu->getName()){
        
      $not = $data[3];
      $miktar = $item->getCount();
      $esyaname = $item->getName();
      $gm = $data[2];
      $this->hediyeyolla($player, $oyuncu, $not, $esyaname, $gm, $d3);
      $item->setCount($gm);
      $player->getInventory()->removeItem($item);
      $oyuncu->getInventory()->addItem($item);
      $oyname = $oyuncu->getName();
      $player->sendMessage("§2 » §a$oyname adlı oyuncuya hediye gönderildi!");
      }else{
        $player->sendMessage("§cKendine eşya gönderemezsin");
      }
    });
    $item = $player->getInventory()->getItemInHand();
    $miktar = $item->getCount();
      $esyaname = $item->getName();
    $form->setTitle("Hediye Menüsü");
    $form->addLabel("\n§7Elinizdeki Eşya: $esyaname");
    $form->addDropDown("\n§7Oyuncu Seç", $list);
    $form->addSlider("\nMiktar seç", 1, $miktar);
    $form->addInput("\n§7Not bırak (zorunlu değil)", "Örnek: Hediyem Olsun");
    $form->sendToPlayer($player);
  }
  public function hediyeyolla(Player $player, $oyuncu, $not, $esyaname, $gm, $d3){
    $form = new CustomForm(function(Player $player, $data) use ($oyuncu, $not, $gm, $d3){
      if($data === null) return true;
      
    });
       $ename = $player->getInventory()->getItemInHand()->getName();
    $pname = $player->getName();
    $oname = $oyuncu->getName();
    $form->setTitle("Hediye Menüsü");
    if($d3 === null){
      $form->addLabel("\n§7Hey! Bir hediye geldi.\n\n§f - §3Gönderen§f: $pname\n\n§f - §3Eşya§f: $ename\n\n§f - §3Miktar§f: $gm\n\n§f - §3Not§f: §cNot girilmemiş!");
    }else{
    $form->addLabel("\n§7Hey! Bir hediye geldi.\n\n§f - §3Gönderen§f: $pname\n\n§f - §3Eşya§f: $ename\n\n§f - §3Miktar§f: $gm\n\n§f - §3Not§f: $not");
    }
    $form->sendToPlayer($oyuncu);
  }
}
