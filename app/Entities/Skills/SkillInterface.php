<?php

namespace App\Entities\Skills;

use App\Entities\Entity;

/**
 * Interface SkillInterface
 *
 * @package App\Entities\Skills
 */
interface SkillInterface
{
    /**
     * @param  \App\Entities\Entity  $entity
     * @param  \App\Entities\Entity  $enemy
     *
     * @return bool
     */
    public function use(Entity $entity, Entity $enemy): bool;
}
