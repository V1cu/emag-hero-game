<?php

namespace Tests;

use App\Battleground\Battleground;
use App\Entities\Entity;
use PHPUnit\Framework\TestCase;

/**
 * Class BattlegroundTest
 *
 * @package Tests
 */
class BattlegroundTest extends TestCase
{
    /**
     * @var \App\Battleground\Battleground|null
     */
    protected ?Battleground $battleground = null;

    /**
     * @return void
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $hero = $this->getMockBuilder(Entity::class)->setConstructorArgs(['Test Hero'])->getMockForAbstractClass();

        $hero->setHealth(70, 100);
        $hero->setStrength(70, 80);
        $hero->setDefence(45, 55);
        $hero->setSpeed(40, 50);
        $hero->setLuck(10, 30);
        $hero->addSkill(new \App\Entities\Skills\RapidStrikeSkill(10));
        $hero->addSkill(new \App\Entities\Skills\MagicShieldSkill(20));

        $beast = $this->getMockBuilder(Entity::class)->setConstructorArgs(['Test Beast'])->getMockForAbstractClass();

        $beast->setHealth(60, 90);
        $beast->setStrength(60, 90);
        $beast->setDefence(40, 60);
        $beast->setSpeed(40, 50);
        $beast->setLuck(25, 40);

        $this->battleground = $this->getMockBuilder(Battleground::class)
                                   ->setConstructorArgs([$hero, $beast, 20])
                                   ->getMockForAbstractClass();
    }

    /**
     * @return void
     */
    public function testHasAttacker()
    {
        $this->assertNotEmpty($this->battleground->getAttacker());
    }

    /**
     * @return void
     */
    public function testHasDefender()
    {
        $this->assertNotEmpty($this->battleground->getDefender());
    }

    /**
     * @return void
     */
    public function testRoundIncreasing()
    {
        $this->assertGreaterThan(0, $this->battleground->getRound());
    }

    /**
     * @return void
     */
    public function testMaxRounds()
    {
        $this->assertEquals(20, $this->battleground->getMaxRounds());
    }
}