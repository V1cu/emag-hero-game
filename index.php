<?php

// Register autoloader
require_once __DIR__ . '/vendor/autoload.php';

try {
    $hero = new App\Entities\Entity('Orderus');
    $hero->setHealth(70, 100)
         ->setStrength(70, 80)
         ->setDefence(45, 55)
         ->setSpeed(40, 50)
         ->setLuck(10, 30)
         ->addSkill(new \App\Entities\Skills\RapidStrikeSkill(10))
         ->addSkill(new \App\Entities\Skills\MagicShieldSkill(20));

    $beast = new \App\Entities\Entity('Beast');
    $beast->setHealth(60, 90)
          ->setStrength(60, 90)
          ->setDefence(40, 60)
          ->setSpeed(40, 50)
          ->setLuck(25, 40);

    $battleground = new \App\Battleground\Battleground($hero, $beast, 20);

    \App\Log\Logger::showMessages();
} catch (Exception $exception) {
    printf('[ERROR] %s, %s on line %d', $exception->getMessage(), $exception->getFile(), $exception->getLine());
}

