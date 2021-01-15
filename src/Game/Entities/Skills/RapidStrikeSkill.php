<?php

namespace Game\Entities\Skills;

use Game\Entities\Entity;
use Game\Helpers\LoggerHelper;

/**
 * Class RapidStrikeSkill
 *
 * @package Game\Entities\Skills
 */
class RapidStrikeSkill extends SkillAbstract
{
    /**
     * RapidStrikeSkill constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setName('Rapid Strike');
        $this->setDesc('Strike twice while it\'s his turn to attack.');
        $this->setLuck(10);
    }

    /**
     * @param  \Game\Entities\Entity  $entity
     * @param  \Game\Entities\Entity  $enemy
     *
     * @return bool
     */
    public function use(Entity $entity, Entity $enemy): bool
    {
        if ( !$entity->isAttacker() || !$this->hasLuck() ) {
            return false;
        }

        $initialDamage = $entity->getDamageDone();
        $skillDamage   = $initialDamage * 2;
        $entity->setDamageDone($skillDamage);
        $enemy->setDamageTaken($skillDamage);

        LoggerHelper::addMessage(
            '[SKILL] %s used %s and did %d damage to %s!',
            [
                $entity->getName(),
                $this->getName(),
                $initialDamage,
                $enemy->getName()
            ]
        );

        return true;
    }
}
