<?php

namespace Game\Entities;

use Game\Entities\Skills\SkillAbstract;
use Game\Helpers\LoggerHelper;
use Game\Helpers\StatsHelper;

/**
 * Class Entity
 *
 * @package Game\Entities
 */
class Entity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int|float
     */
    protected $health;

    /**
     * @var int|float
     */
    protected $strength;

    /**
     * @var int|float
     */
    protected $defence;

    /**
     * @var int|float
     */
    protected $speed;

    /**
     * @var int|float
     */
    protected $luck;

    /**
     * @var \Game\Entities\Skills\SkillAbstract[]
     */
    protected $skills = [];

    /**
     * @var bool
     */
    protected $isAttacker = false;

    /**
     * @var int|float
     */
    protected $damageDone = 0;

    /**
     * @var int|float
     */
    protected $damageTaken = 0;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setName($value): string
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @return int|float
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @param  int|float       $min
     * @param  int|float|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setHealth($min, $max = null): Entity
    {
        $this->health = StatsHelper::prepareValue($min, $max);

        return $this;
    }

    /**
     * @return int|float
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @param  int|float       $min
     * @param  int|float|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setStrength($min, $max = null): Entity
    {
        $this->strength = StatsHelper::prepareValue($min, $max);

        return $this;
    }

    /**
     * @return int|float
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * @param  int|float       $min
     * @param  int|float|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setDefence($min, $max = null): Entity
    {
        $this->defence = StatsHelper::prepareValue($min, $max);

        return $this;
    }

    /**
     * @return int|float
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param  int|float       $min
     * @param  int|float|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setSpeed($min, $max = null): Entity
    {
        $this->speed = StatsHelper::prepareValue($min, $max);

        return $this;
    }

    /**
     * @return float|int
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * @param  int|float       $min
     * @param  int|float|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setLuck($min, $max = null): Entity
    {
        $this->luck = StatsHelper::prepareValue($min, $max);

        return $this;
    }

    /**
     * @return \Game\Entities\Skills\SkillAbstract[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @param  \Game\Entities\Skills\SkillAbstract  $skill
     *
     * @return $this
     */
    public function addSkill(SkillAbstract $skill): Entity
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * @param  \Game\Entities\Entity  $enemy
     *
     * @return $this
     */
    public function useSkills(Entity $enemy): Entity
    {
        if ( empty($this->skills) ) {
            return $this;
        }

        foreach ( $this->skills as $skill ) {
            $skill->use($this, $enemy);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isAttacker(): bool
    {
        return $this->isAttacker;
    }

    /**
     * @param  bool  $isAttacker
     *
     * @return $this
     */
    public function setIsAttacker(bool $isAttacker): Entity
    {
        $this->isAttacker = $isAttacker;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getDamageDone()
    {
        return $this->damageDone;
    }

    /**
     * @param  int|float  $damageDone
     *
     * @return $this;
     */
    public function setDamageDone($damageDone): Entity
    {
        $this->damageDone = $damageDone;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getDamageTaken()
    {
        return $this->damageTaken;
    }

    /**
     * @param  float|int  $damageTaken
     *
     * @return $this
     */
    public function setDamageTaken($damageTaken): Entity
    {
        $this->damageTaken = $damageTaken;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->getHealth() > 0;
    }

    /**
     * @param  \Game\Entities\Entity  $enemy
     *
     * @return $this
     * @throws \Exception
     */
    public function attack(Entity $enemy): Entity
    {
        $this->setIsAttacker(true);
        $enemy->setIsAttacker(false);

        if ( !StatsHelper::hasLuck($this->getLuck()) ) {
            LoggerHelper::addMessage(
                '[ATTACK] %s missed his attack!',
                [
                    $this->getName()
                ]
            );
            return $this;
        }

        $damage = $this->getStrength() - $enemy->getDefence();

        if ( $damage <= 0 ) {
            LoggerHelper::addMessage(
                '[ATTACK] %s did no damage to %s',
                [
                    $this->getName(),
                    $enemy->getName()
                ]
            );
            return $this;
        }

        $this->setDamageDone($damage);
        $enemy->setDamageTaken($damage);

        LoggerHelper::addMessage(
            '[ATTACK] %s did %d damage to %s',
            [
                $this->getName(),
                $damage,
                $enemy->getName()
            ]
        );

        $this->useSkills($enemy);
        $enemy->useSkills($this);

        $health = max(0, $enemy->getHealth() - $enemy->getDamageTaken());
        $enemy->setHealth($health);

        LoggerHelper::addMessage(
            '[STATS] %s remained with %d health',
            [
                $enemy->getName(),
                $health
            ]
        );

        return $this;
    }
}
