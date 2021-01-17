<?php

namespace App\Entities\Skills;

use App\Entities\Entity;
use App\Log\Logger;

/**
 * Class RapidStrikeSkill
 *
 * @package App\Entities\Skills
 */
class RapidStrikeSkill extends SkillAbstract
{
    /**
     * @var string
     */
    protected string $name = 'Rapid Strike';

    /**
     * @var string|null
     */
    protected ?string $desc = 'Strike twice while it\'s his turn to attack.';

    /**
     * @param  \App\Entities\Entity  $entity
     * @param  \App\Entities\Entity  $enemy
     *
     * @return bool
     */
    public function use(Entity $entity, Entity $enemy): bool
    {
        if ( ! $entity->isAttacker() || ! $this->hasLuck()) {
            return false;
        }

        $initialDamage = $entity->getDamageDone();
        $skillDamage   = $initialDamage * 2;
        $entity->setDamageDone($skillDamage);
        $enemy->setDamageTaken($skillDamage);

        Logger::addMessage(
            '[SKILL] %s used %s and did %d damage to %s!',
            [
                $entity->getName(),
                $this->getName(),
                $initialDamage,
                $enemy->getName(),
            ]
        );

        return true;
    }
}
