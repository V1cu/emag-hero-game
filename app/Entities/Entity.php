<?php

namespace App\Entities;

use App\Entities\Skills\SkillAbstract;
use App\Helpers\StatsHelper;

/**
 * Class Entity
 *
 * @package App\Entities
 */
class Entity
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var int
     */
    private int $health = 0;

    /**
     * @var int
     */
    private int $strength = 0;

    /**
     * @var int
     */
    private int $defence = 0;

    /**
     * @var int
     */
    private int $speed = 0;

    /**
     * @var int
     */
    private int $luck = 0;

    /**
     * @var \App\Entities\Skills\SkillAbstract[]
     */
    private array $skills = [];

    /**
     * @var bool
     */
    private bool $isAttacker = false;

    /**
     * @var int
     */
    private int $damageDone = 0;

    /**
     * @var int
     */
    private int $damageTaken = 0;

    /**
     * Entity constructor.
     *
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $value
     *
     * @return $this
     */
    public function setName(string $value): Entity
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
     * @return bool
     */
    public function hasLuck(): bool
    {
        return mt_rand(0, 100) <= $this->getLuck();
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
     * @return \App\Entities\Skills\SkillAbstract[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @param  \App\Entities\Skills\SkillAbstract  $skill
     *
     * @return $this
     */
    public function addSkill(SkillAbstract $skill): Entity
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * @param  \App\Entities\Entity  $enemy
     *
     * @return $this
     */
    public function useSkills(Entity $enemy): Entity
    {
        if (empty($this->skills)) {
            return $this;
        }

        foreach ($this->skills as $skill) {
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
}
