<?php

require_once 'vendor/autoload.php';

try {
    $hero = new Game\Entities\Entity();
    $hero->setName('Orderus')
         ->setHealth(70, 100)
         ->setStrength(70, 80)
         ->setDefence(45, 55)
         ->setSpeed(40, 50)
         ->setLuck(10, 30)
         ->addSkill(new \Game\Entities\Skills\RapidStrikeSkill())
         ->addSkill(new \Game\Entities\Skills\MagicShieldSkill());

    $beast = new \Game\Entities\Entity();
    $beast->setName('Beast')
          ->setHealth(60, 90)
          ->setStrength(60, 90)
          ->setDefence(40, 60)
          ->setSpeed(40, 50)
          ->setLuck(25, 40);

    $battleground = new \Game\Battleground\Battleground();
    $battleground->addParticipant($hero)
                 ->addParticipant($beast)
                 ->setMaxRounds(20)
                 ->startFighting();

    \Game\Helpers\LoggerHelper::showMessages();
} catch (Exception $exception) {
    printf('[ERROR] %s, %s on line %d', $exception->getMessage(), $exception->getFile(), $exception->getLine());
}

