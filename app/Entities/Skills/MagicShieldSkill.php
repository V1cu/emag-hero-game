<?php

namespace App\Entities\Skills;

use App\Entities\Entity;
use App\Log\Logger;

/**
 * Class MagicShieldSkill
 *
 * @package App\Entities\Skills
 */
class MagicShieldSkill extends SkillAbstract
{
    /**
     * @var string
     */
    protected string $name = 'Magic Shield';

    /**
     * @var string|null
     */
    protected ?string $desc = 'Takes only half of the usual damage when an enemy attacks.';

    /**
     * @param  \App\Entities\Entity  $entity
     * @param  \App\Entities\Entity  $enemy
     *
     * @return bool
     */
    public function use(Entity $entity, Entity $enemy): bool
    {
        if ($entity->isAttacker() || ! $this->hasLuck()) {
            return false;
        }

        $initialDamage = $entity->getDamageTaken();
        $skillDamage   = $initialDamage / 2;
        $entity->setDamageTaken($skillDamage);
        $enemy->setDamageDone($skillDamage);

        Logger::addMessage(
            '[SKILL] %s used %s and blocked %d damage out of %d from %s!',
            [
                $entity->getName(),
                $this->getName(),
                $skillDamage,
                $initialDamage,
                $enemy->getName(),
            ]
        );

        return true;
    }
}
