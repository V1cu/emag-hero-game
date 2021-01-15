<?php

namespace Game\Battleground;

use Game\Entities\Entity;
use Game\Helpers\LoggerHelper;

/**
 * Class Battleground
 *
 * @package Game\Battleground
 */
class Battleground
{
    /**
     * @var Entity
     */
    protected $firstParticipant;

    /**
     * @var Entity
     */
    protected $secondParticipant;

    /**
     * @var int
     */
    protected $currentRound;

    /**
     * @var int
     */
    protected $maxRounds = 10;

    /**
     * @param  \Game\Entities\Entity  $participant
     *
     * @return $this
     * @throws \Exception
     */
    public function addParticipant(Entity $participant): Battleground
    {
        if ( !$this->firstParticipant ) {
            $this->firstParticipant = $participant;

            return $this;
        }

        if ( !empty($this->secondParticipant) ) {
            throw new \Exception('Only two participants are allowed to participate in the fight!');
        }

        $this->secondParticipant = $participant;

        if ( $this->firstParticipant->getSpeed() < $this->secondParticipant->getSpeed()
            || $this->firstParticipant->getLuck() < $this->secondParticipant->getLuck() ) {
            $this->switchParticipants();
        }

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
        if ( !is_int($value) || $value <= 0 ) {
            throw new \Exception('Max rounds param must contain a positive int greater than zero!');
        }

        $this->maxRounds = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentRound(): int
    {
        return $this->currentRound;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function startFighting(): Battleground
    {
        if ( empty($this->firstParticipant) ) {
            throw new \Exception('The fight cannot start without participants!');
        }

        if ( empty($this->secondParticipant) ) {
            throw new \Exception('It takes two participants to start the fight!');
        }

        $this->currentRound = 1;


        while ( $this->currentRound <= $this->maxRounds
            && $this->firstParticipant->isAlive()
            && $this->secondParticipant->isAlive() ) {
            $this->addRoundLogMessages();
            $this->firstParticipant->attack($this->secondParticipant);
            $this->switchParticipants();
            $this->currentRound++;
        }

        $this->addVerdictLogMessages();

        return $this;
    }

    /**
     * @return $this
     */
    private function switchParticipants(): Battleground
    {
        [$this->firstParticipant, $this->secondParticipant] = [$this->secondParticipant, $this->firstParticipant];

        return $this;
    }

    /**
     * @return $this
     */
    private function addRoundLogMessages(): Battleground
    {
        LoggerHelper::addMessage(
            '[INFO] ----- ROUND %d of %d-----',
            [
                $this->getCurrentRound(),
                $this->getMaxRounds()
            ]
        );
        LoggerHelper::addMessage(
            '[INFO] %s has %d health',
            [
                $this->firstParticipant->getName(),
                $this->firstParticipant->getHealth()
            ]
        );
        LoggerHelper::addMessage(
            '[INFO] %s has %d health',
            [
                $this->secondParticipant->getName(),
                $this->secondParticipant->getHealth()
            ]
        );

        return $this;
    }

    /**
     * @return $this
     */
    private function addVerdictLogMessages(): Battleground
    {
        if ( !$this->firstParticipant->isAlive() ) {
            LoggerHelper::addMessage('[VERDICT] %s won!', $this->secondParticipant->getName());

            return $this;
        }

        if ( !$this->secondParticipant->isAlive() ) {
            LoggerHelper::addMessage('[VERDICT] %s won!', $this->firstParticipant->getName());

            return $this;
        }

        if ( $this->firstParticipant->getHealth() > $this->secondParticipant->getHealth() ) {
            LoggerHelper::addMessage(
                '[VERDICT] %s won because he has more life (%d)!',
                [
                    $this->firstParticipant->getName(),
                    $this->firstParticipant->getHealth()
                ]
            );

            return $this;
        }

        if ( $this->firstParticipant->getHealth() < $this->secondParticipant->getHealth() ) {
            LoggerHelper::addMessage(
                '[VERDICT] %s won because he has more life (%d)!',
                [
                    $this->secondParticipant->getName(),
                    $this->secondParticipant->getHealth()
                ]
            );

            return $this;
        }

        LoggerHelper::addMessage('[VERDICT] TIE!');

        return $this;
    }
}
