<?php

namespace Game\Entities\Skills;

use Game\Helpers\StatsHelper;

/**
 * Class SkillAbstract
 *
 * @package Game\Entities\Skills
 */
abstract class SkillAbstract implements SkillInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $desc;

    /**
     * @var int|float
     */
    protected $luck = 0;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     *
     * @return $this
     */
    public function setName(string $name): string
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * @param  string  $desc
     *
     * @return $this
     */
    public function setDesc(string $desc): string
    {
        $this->desc = $desc;

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
        return StatsHelper::hasLuck($this->getLuck());
    }

    /**
     * @param  float|int       $min
     * @param  float|int|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setLuck($min, $max = null): SkillAbstract
    {
        $this->luck = StatsHelper::prepareValue($min, $max);

        return $this;
    }
}
