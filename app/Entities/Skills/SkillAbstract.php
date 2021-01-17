<?php

namespace App\Entities\Skills;

use App\Helpers\StatsHelper;

/**
 * Class SkillAbstract
 *
 * @package App\Entities\Skills
 */
abstract class SkillAbstract implements SkillInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string|null
     */
    protected ?string $desc = null;

    /**
     * @var int
     */
    protected int $luck = 0;

    /**
     * SkillAbstract constructor.
     *
     * @param  int       $minLuck
     * @param  int|null  $maxLuck
     *
     * @throws \Exception
     */
    public function __construct(int $minLuck, ?int $maxLuck = null)
    {
        if (empty($this->getName())) {
            throw new \Exception('Missing skill name!');
        }

        $this->setLuck($minLuck, $maxLuck);

        if (empty($this->getLuck())) {
            throw new \Exception('Skill luck must be a positive integer!');
        }
    }

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
    public function setName(string $name): SkillAbstract
    {
        if ( ! empty($name)) {
            $this->name = $name;
        }

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
    public function setDesc(string $desc): SkillAbstract
    {
        if ( ! empty($desc)) {
            $this->desc = $desc;
        }

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
     * @param  int       $min
     * @param  int|null  $max
     *
     * @return $this
     * @throws \Exception
     */
    public function setLuck(int $min, $max = null): SkillAbstract
    {
        if ( ! empty($min)) {
            $this->luck = StatsHelper::prepareValue($min, $max);
        }

        return $this;
    }
}
