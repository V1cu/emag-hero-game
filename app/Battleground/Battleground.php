<?php

namespace App\Battleground;

use App\Entities\Entity;
use App\Log\Logger;

/**
 * Class Battleground
 *
 * @package App\Battleground
 */
class Battleground
{
    /**
     * @var Entity
     */
    private Entity $attacker;

    /**
     * @var Entity
     */
    protected Entity $defender;

    /**
     * @var int
     */
    protected int $round = 0;

    /**
     * @var int
     */
    protected int $maxRounds = 10;

    /**
     * Battleground constructor.
     *
     * @param  \App\Entities\Entity  $attacker
     * @param  \App\Entities\Entity  $defender
     * @param  int|null              $rounds
     *
     * @throws \Exception
     */
    public function __construct(Entity $attacker, Entity $defender, ?int $rounds = null)
    {
        $this->setAttacker($attacker);
        $this->setDefender($defender);

        if ( ! empty($rounds)) {
            $this->setMaxRounds($rounds);
        }

        $this->startFight();
    }

    /**
     * @return \App\Entities\Entity
     */
    public function getAttacker(): Entity
    {
        return $this->attacker;
    }

    /**
     * @param  \App\Entities\Entity  $attacker
     *
     * @return Battleground
     */
    public function setAttacker(Entity $attacker): Battleground
    {
        $this->attacker = $attacker;

        return $this;
    }

    /**
     * @return \App\Entities\Entity
     */
    public function getDefender(): Entity
    {
        return $this->defender;
    }

    /**
     * @param  \App\Entities\Entity  $defender
     *
     * @return Battleground
     */
    public function setDefender(Entity $defender): Battleground
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * @return int
     */
    public function getRound(): int
    {
        return $this->round;
    }

    /**
     * @return $this
     */
    public function increaseRound(): Battleground
    {
        $this->round++;

        return $this;
    }

    /**
     * @param  int  $round
     *
     * @return Battleground
     */
    public function setRound(int $round): Battleground
    {
        $this->round = $round;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxRounds(): int
    {
        return $this->maxRounds;
    }

    /**
     * @param  int  $value
     *
     * @return $this
     * @throws \Exception
     */
    public function setMaxRounds(int $value): Battleground
    {
        if ( ! is_int($value) || $value <= 0) {
            throw new \Exception('Max rounds param must contain a positive int greater than zero!');
        }

        $this->maxRounds = $value;

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function startFight(): Battleground
    {
        $this->prepareFight();

        while ($this->canFight()) {
            $this->increaseRound();

            Logger::addMessage(
                '[INFO] ----- ROUND %d of %d -----',
                [
                    $this->getRound(),
                    $this->getMaxRounds(),
                ]
            );

            $this->getAttacker()->setIsAttacker(true);
            $this->getDefender()->setIsAttacker(false);

            Logger::addMessage(
                '[INFO] %s has %d health and %s has %d health.',
                [
                    $this->getAttacker()->getName(),
                    $this->getAttacker()->getHealth(),
                    $this->getDefender()->getName(),
                    $this->getDefender()->getHealth(),
                ]
            );

            if ( ! $this->getAttacker()->hasLuck()) {
                Logger::addMessage(
                    '[ATTACK] %s missed his attack!',
                    [
                        $this->getAttacker()->getName(),
                    ]
                );

                $this->switchParticipants();

                continue;
            }

            $damage = $this->getAttacker()->getStrength() - $this->getDefender()->getDefence();

            if ($damage <= 0) {
                Logger::addMessage(
                    '[ATTACK] %s did no damage to %s',
                    [
                        $this->getAttacker()->getName(),
                        $this->getDefender()->getName(),
                    ]
                );

                $this->switchParticipants();

                continue;
            }

            $this->getAttacker()->setDamageDone($damage);
            $this->getDefender()->setDamageTaken($damage);

            Logger::addMessage(
                '[ATTACK] %s did %d damage to %s',
                [
                    $this->getAttacker()->getName(),
                    $damage,
                    $this->getDefender()->getName(),
                ]
            );

            $this->getAttacker()->useSkills($this->getDefender());
            $this->getDefender()->useSkills($this->getAttacker());

            $health = max(0, $this->getDefender()->getHealth() - $this->getDefender()->getDamageTaken());
            $this->getDefender()->setHealth($health);

            Logger::addMessage(
                '[STATS] %s remained with %d health',
                [
                    $this->getDefender()->getName(),
                    $health,
                ]
            );

            $this->switchParticipants();
        }

        return $this->addVerdictLogMessages();
    }

    /**
     * @return $this|\App\Battleground\Battleground
     */
    public function prepareFight(): Battleground
    {
        if ($this->getAttacker()->getSpeed() < $this->getDefender()->getSpeed()
            || $this->getAttacker()->getLuck() < $this->getDefender()->getLuck()
        ) {
            return $this->switchParticipants();
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function canFight(): bool
    {
        return $this->getRound() <= $this->getMaxRounds() && $this->attacker->isAlive() && $this->defender->isAlive();
    }

    /**
     * @return $this
     */
    private function switchParticipants(): Battleground
    {
        [$this->attacker, $this->defender] = [$this->defender, $this->attacker];

        return $this;
    }

    /**
     * @return $this
     */
    private function addVerdictLogMessages(): Battleground
    {
        if ( ! $this->getDefender()->isAlive()) {
            Logger::addMessage('[VERDICT] %s won!', $this->getAttacker()->getName());

            return $this;
        }

        if ( ! $this->getAttacker()->isAlive()) {
            Logger::addMessage('[VERDICT] %s won!', $this->getDefender()->getName());

            return $this;
        }

        if ($this->getAttacker()->getHealth() > $this->getDefender()->getHealth()) {
            Logger::addMessage(
                '[VERDICT] %s won because he has more life (%d)!',
                [
                    $this->getAttacker()->getName(),
                    $this->getAttacker()->getHealth(),
                ]
            );

            return $this;
        }

        if ($this->getAttacker()->getHealth() < $this->getDefender()->getHealth()) {
            Logger::addMessage(
                '[VERDICT] %s won because he has more life (%d)!',
                [
                    $this->getDefender()->getName(),
                    $this->getDefender()->getHealth(),
                ]
            );

            return $this;
        }

        Logger::addMessage('[VERDICT] TIE!');

        return $this;
    }
}
