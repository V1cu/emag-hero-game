<?php

namespace Game\Entities\Skills;

use Game\Entities\Entity;

/**
 * Interface SkillInterface
 *
 * @package Game\Entities\Skills
 */
interface SkillInterface
{
    /**
     * @param  \Game\Entities\Entity  $entity
     * @param  \Game\Entities\Entity  $enemy
     *
     * @return bool
     */
    public function use(Entity $entity, Entity $enemy): bool;
}
