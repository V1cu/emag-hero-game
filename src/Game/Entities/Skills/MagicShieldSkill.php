<?php

namespace Game\Entities\Skills;

use Game\Entities\Entity;
use Game\Helpers\LoggerHelper;

/**
 * Class MagicShieldSkill
 *
 * @package Game\Entities\Skills
 */
class MagicShieldSkill extends SkillAbstract
{
    /**
     * MagicShieldSkill constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setName('Magic Shield');
        $this->setDesc('Takes only half of the usual damage when an enemy attacks.');
        $this->setLuck(20);
    }

    /**
     * @param  \Game\Entities\Entity  $entity
     * @param  \Game\Entities\Entity  $enemy
     *
     * @return bool
     */
    public function use(Entity $entity, Entity $enemy): bool
    {
        if ( $entity->isAttacker() || !$this->hasLuck() ) {
            return false;
        }

        $initialDamage = $entity->getDamageTaken();
        $skillDamage   = $initialDamage / 2;
        $entity->setDamageTaken($skillDamage);
        $enemy->setDamageDone($skillDamage);

        LoggerHelper::addMessage(
            '[SKILL] %s used %s and blocked %d damage out of %d from %s!',
            [
                $entity->getName(),
                $this->getName(),
                $skillDamage,
                $initialDamage,
                $enemy->getName()
            ]
        );

        return true;
    }
}
