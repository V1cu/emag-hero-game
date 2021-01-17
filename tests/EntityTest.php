<?php

namespace Tests;

use App\Entities\Entity;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityTest
 *
 * @package Tests\Entities
 */
class EntityTest extends TestCase
{
    /**
     * @var \App\Entities\Entity|null
     */
    protected ?Entity $entity = null;

    /**
     * @return void
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->entity = $this->getMockBuilder(Entity::class)
                             ->setConstructorArgs(['Test Entity'])
                             ->getMockForAbstractClass();

        $this->entity->setHealth(50);
        $this->entity->setStrength(50);
        $this->entity->setDefence(50);
        $this->entity->setSpeed(50);
        $this->entity->setLuck(50);
        $this->entity->addSkill(new \App\Entities\Skills\RapidStrikeSkill(50));
        $this->entity->addSkill(new \App\Entities\Skills\MagicShieldSkill(50));
    }

    /**
     * @return void
     */
    public function testHealth()
    {
        $this->assertEquals(50, $this->entity->getHealth());
    }

    /**
     * @return void
     */
    public function testStrength()
    {
        $this->assertEquals(50, $this->entity->getStrength());
    }

    /**
     * @return void
     */
    public function testDefence()
    {
        $this->assertEquals(50, $this->entity->getDefence());
    }

    /**
     * @return void
     */
    public function testSpeed()
    {
        $this->assertEquals(50, $this->entity->getSpeed());
    }

    /**
     * @return void
     */
    public function testLuck()
    {
        $this->assertEquals(50, $this->entity->getLuck());
    }

    /**
     * @return void
     */
    public function testSkillNum()
    {
        $this->assertCount(2, $this->entity->getSkills());
    }

    /**
     * @return void
     */
    public function testSkillsLuck()
    {
        $hasRightLuck = true;

        foreach ($this->entity->getSkills() as $skill) {
            if ($skill->getLuck() != 50) {
                $hasRightLuck = false;
                break;
            }
        }

        $this->assertTrue($hasRightLuck);
    }
}