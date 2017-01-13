<?php
/**
 * PHT
 *
 * @author Telesphore
 * @link https://github.com/jetwitaussi/PHT
 * @version 3.0
 * @license "THE BEER-WARE LICENSE" (Revision 42):
 *          Telesphore wrote this file.  As long as you retain this notice you
 *          can do whatever you want with this stuff. If we meet some day, and you think
 *          this stuff is worth it, you can buy me a beer in return.
 */

namespace PHT\Config;

class Orders
{
    const BEHAVIOUR_NORMAL = 0;
    const BEHAVIOUR_OFFENSIVE = 1;
    const BEHAVIOUR_DEFENSIVE = 2;
    const BEHAVIOUR_TOWARDS_MIDDLE = 3;
    const BEHAVIOUR_TOWARDS_WING = 4;
    const TACTIC_NORMAL = 0;
    const TACTIC_PRESSING = 1;
    const TACTIC_COUNTER_ATTACK = 2;
    const TACTIC_ATTACK_MIDDLE = 3;
    const TACTIC_ATTACK_WINGS = 4;
    const TACTIC_PLAY_CREATIVE = 7;
    const TACTIC_LONG_SHOTS = 8;
    const ATTITUDE_NORMAL = 0;
    const ATTITUDE_PIC = -1;
    const ATTITUDE_MOTS = 1;

    private $matchId;
    private $sourceSystem;
    private $errors = array();
    private $goalkeeper = 0;
    private $rightDefender = 0;
    private $rightDefenderBehaviour = 0;
    private $rightCentralDefender = 0;
    private $rightCentralDefenderBehaviour = 0;
    private $centralDefender = 0;
    private $centralDefenderBehaviour = 0;
    private $leftCentralDefender = 0;
    private $leftCentralDefenderBehaviour = 0;
    private $leftDefender = 0;
    private $leftDefenderBehaviour = 0;
    private $rightWinger = 0;
    private $rightWingerBehaviour = 0;
    private $rightMidfield = 0;
    private $rightMidfieldBehaviour = 0;
    private $centralMidfield = 0;
    private $centralMidfieldBehaviour = 0;
    private $leftMidfield = 0;
    private $leftMidfieldBehaviour = 0;
    private $leftWinger = 0;
    private $leftWingerBehaviour = 0;
    private $rightForward = 0;
    private $rightForwardBehaviour = 0;
    private $centralForward = 0;
    private $centralForwardBehaviour = 0;
    private $leftForward = 0;
    private $leftForwardBehaviour = 0;
    private $substituteGoalkeeper = 0;
    private $substituteDefender = 0;
    private $substituteMidfield = 0;
    private $substituteForward = 0;
    private $substituteWinger = 0;
    private $captain = 0;
    private $setpieces = 0;
    private $penaltyOne = 0;
    private $penaltyTwo = 0;
    private $penaltyThree = 0;
    private $penaltyFour = 0;
    private $penaltyFive = 0;
    private $penaltySix = 0;
    private $penaltySeven = 0;
    private $penaltyEight = 0;
    private $penaltyNine = 0;
    private $penaltyTen = 0;
    private $penaltyEleven = 0;
    private $tactic = 0;
    private $coachModifier = 0;
    private $speechLevel = 0;
    private $substitutionOne = null;
    private $substitutionTwo = null;
    private $substitutionThree = null;
    private $substitutionFour = null;
    private $substitutionFive = null;

    /**
     * @param integer $matchId
     * @param string $sourceSystem (use \PHT\Config\Config MATCH_* constants)
     */
    public function __construct($matchId, $sourceSystem)
    {
        $this->matchId = $matchId;
        $this->sourceSystem = $sourceSystem;
    }

    /**
     * Return match id
     *
     * @return integer
     */
    public function getMatchId()
    {
        return $this->matchId;
    }

    /**
     * Return if match source system
     *
     * @return string
     */
    public function getSourceSystem()
    {
        return $this->sourceSystem;
    }

    /**
     * Define goalkeeper
     *
     * @param integer $playerId
     */
    public function setGoalkeeper($playerId)
    {
        $this->goalkeeper = $playerId;
    }

    /**
     * Define right defender and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setRightDefender($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->rightDefender = $playerId;
        $this->rightDefenderBehaviour = $behaviour;
    }

    /**
     * Define right central defender and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setRightCentralDefender($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->rightCentralDefender = $playerId;
        $this->rightCentralDefenderBehaviour = $behaviour;
    }

    /**
     * Define central defender and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setCentralDefender($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->centralDefender = $playerId;
        $this->centralDefenderBehaviour = $behaviour;
    }

    /**
     * Define left central defender and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setLeftCentralDefender($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->leftCentralDefender = $playerId;
        $this->leftCentralDefenderBehaviour = $behaviour;
    }

    /**
     * Define left defender and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setLeftDefender($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->leftDefender = $playerId;
        $this->leftDefenderBehaviour = $behaviour;
    }

    /**
     * Define right winger and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setRightWinger($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->rightWinger = $playerId;
        $this->rightWingerBehaviour = $behaviour;
    }

    /**
     * Define right midfield and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setRightMidfield($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->rightMidfield = $playerId;
        $this->rightMidfieldBehaviour = $behaviour;
    }

    /**
     * Define central midfield and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setCentralMidfield($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->centralMidfield = $playerId;
        $this->centralMidfieldBehaviour = $behaviour;
    }

    /**
     * Define left midfield and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setLeftMidfield($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->leftMidfield = $playerId;
        $this->leftMidfieldBehaviour = $behaviour;
    }

    /**
     * Define left winger and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setLeftWinger($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->leftWinger = $playerId;
        $this->leftWingerBehaviour = $behaviour;
    }

    /**
     * Define right forward and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setRightForward($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->rightForward = $playerId;
        $this->rightForwardBehaviour = $behaviour;
    }

    /**
     * Define central forward and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setCentralForward($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->centralForward = $playerId;
        $this->centralForwardBehaviour = $behaviour;
    }

    /**
     * Define left forward and its behaviour
     *
     * @param integer $playerId
     * @param integer $behaviour (use class constants BEHAVIOUR_*)
     */
    public function setLeftForward($playerId, $behaviour = self::BEHAVIOUR_NORMAL)
    {
        $this->leftForward = $playerId;
        $this->leftForwardBehaviour = $behaviour;
    }

    /**
     * Define goalkeeper substitute
     *
     * @param integer $playerId
     */
    public function setSubstituteGoalkeeper($playerId)
    {
        $this->substituteGoalkeeper = $playerId;
    }

    /**
     * Define defender substitute
     *
     * @param integer $playerId
     */
    public function setSubstituteDefender($playerId)
    {
        $this->substituteDefender = $playerId;
    }

    /**
     * Define midfield substitute
     *
     * @param integer $playerId
     */
    public function setSubstituteMidfield($playerId)
    {
        $this->substituteMidfield = $playerId;
    }

    /**
     * Define winger substitute
     *
     * @param integer $playerId
     */
    public function setSubstituteWinger($playerId)
    {
        $this->substituteWinger = $playerId;
    }

    /**
     * Define forward substitute
     *
     * @param integer $playerId
     */
    public function setSubstituteForward($playerId)
    {
        $this->substituteForward = $playerId;
    }

    /**
     * Define captain
     *
     * @param integer $playerId
     */
    public function setCaptain($playerId)
    {
        $this->captain = $playerId;
    }

    /**
     * Define set pieces taker
     *
     * @param integer $playerId
     */
    public function setSetpieces($playerId)
    {
        $this->setpieces = $playerId;
    }

    /**
     * Define penalty taker number 1
     *
     * @param integer $playerId
     */
    public function setPenalty1($playerId)
    {
        $this->penaltyOne = $playerId;
    }

    /**
     * Define penalty taker number 2
     *
     * @param integer $playerId
     */
    public function setPenalty2($playerId)
    {
        $this->penaltyTwo = $playerId;
    }

    /**
     * Define penalty taker number 3
     *
     * @param integer $playerId
     */
    public function setPenalty3($playerId)
    {
        $this->penaltyThree = $playerId;
    }

    /**
     * Define penalty taker number 4
     *
     * @param integer $playerId
     */
    public function setPenalty4($playerId)
    {
        $this->penaltyFour = $playerId;
    }

    /**
     * Define penalty taker number 5
     *
     * @param integer $playerId
     */
    public function setPenalty5($playerId)
    {
        $this->penaltyFive = $playerId;
    }

    /**
     * Define penalty taker number 6
     *
     * @param integer $playerId
     */
    public function setPenalty6($playerId)
    {
        $this->penaltySix = $playerId;
    }

    /**
     * Define penalty taker number 7
     *
     * @param integer $playerId
     */
    public function setPenalty7($playerId)
    {
        $this->penaltySeven = $playerId;
    }

    /**
     * Define penalty taker number 8
     *
     * @param integer $playerId
     */
    public function setPenalty8($playerId)
    {
        $this->penaltyEight = $playerId;
    }

    /**
     * Define penalty taker number 9
     *
     * @param integer $playerId
     */
    public function setPenalty9($playerId)
    {
        $this->penaltyNine = $playerId;
    }

    /**
     * Define penalty taker number 10
     *
     * @param integer $playerId
     */
    public function setPenalty10($playerId)
    {
        $this->penaltyTen = $playerId;
    }

    /**
     * Define penalty taker number 11
     *
     * @param integer $playerId
     */
    public function setPenalty11($playerId)
    {
        $this->penaltyEleven = $playerId;
    }

    /**
     * Define match tactic
     *
     * @param integer $code (use class constant TACTIC_*)
     */
    public function setTactic($code)
    {
        $this->tactic = $code;
    }

    /**
     * Define coach modifier
     *
     * @param integer $level (from -10 to +10)
     */
    public function setCoachModifier($level)
    {
        $this->coachModifier = $level;
    }

    /**
     * Define match attitude
     *
     * @param integer $code (use class constant ATTITUDE_*)
     */
    public function setAttitude($code)
    {
        $this->speechLevel = $code;
    }

    /**
     * Define first substitution
     *
     * @param \PHT\Config\OrdersSubstitution $substitution
     */
    public function setSubstitution1($substitution)
    {
        $this->substitutionOne = $substitution;
    }

    /**
     * Define second substitution
     *
     * @param \PHT\Config\OrdersSubstitution $substitution
     */
    public function setSubstitution2($substitution)
    {
        $this->substitutionTwo = $substitution;
    }

    /**
     * Define third substitution
     *
     * @param \PHT\Config\OrdersSubstitution $substitution
     */
    public function setSubstitution3($substitution)
    {
        $this->substitutionThree = $substitution;
    }

    /**
     * Define fourth substitution
     *
     * @param \PHT\Config\OrdersSubstitution $substitution
     */
    public function setSubstitution4($substitution)
    {
        $this->substitutionFour = $substitution;
    }

    /**
     * Define fifth substitution
     *
     * @param \PHT\Config\OrdersSubstitution $substitution
     */
    public function setSubstitution5($substitution)
    {
        $this->substitutionFive = $substitution;
    }

    /**
     * Return if lineup has error
     *
     * @return boolean
     */
    public function hasError()
    {
        $this->errors = array();
        $team = array();
        $num = $def = $mid = $for = 0;
        $lineups = array('5-5-0', '5-4-1', '5-3-2', '5-2-3', '4-5-1', '4-4-2', '4-3-3', '3-5-2', '3-4-3', '2-5-3');
        if ($this->goalkeeper != 0) {
            ++$num;
            $team[] = $this->goalkeeper;
        }
        if ($this->rightDefender != 0) {
            ++$num;
            ++$def;
            $team[] = $this->rightDefender;
        }
        if ($this->rightCentralDefender != 0) {
            ++$num;
            ++$def;
            $team[] = $this->rightCentralDefender;
        }
        if ($this->centralDefender != 0) {
            ++$num;
            ++$def;
            $team[] = $this->centralDefender;
        }
        if ($this->leftCentralDefender != 0) {
            ++$num;
            ++$def;
            $team[] = $this->leftCentralDefender;
        }
        if ($this->leftDefender != 0) {
            ++$num;
            ++$def;
            $team[] = $this->leftDefender;
        }
        if ($this->rightWinger != 0) {
            ++$num;
            ++$mid;
            $team[] = $this->rightWinger;
        }
        if ($this->rightMidfield != 0) {
            ++$num;
            ++$mid;
            $team[] = $this->rightMidfield;
        }
        if ($this->centralMidfield != 0) {
            ++$num;
            ++$mid;
            $team[] = $this->centralMidfield;
        }
        if ($this->leftMidfield != 0) {
            ++$num;
            ++$mid;
            $team[] = $this->leftMidfield;
        }
        if ($this->leftWinger != 0) {
            ++$num;
            ++$mid;
            $team[] = $this->leftWinger;
        }
        if ($this->rightForward != 0) {
            ++$num;
            ++$for;
            $team[] = $this->rightForward;
        }
        if ($this->centralForward != 0) {
            ++$num;
            ++$for;
            $team[] = $this->centralForward;
        }
        if ($this->leftForward != 0) {
            ++$num;
            ++$for;
            $team[] = $this->leftForward;
        }
        if ($num < 9) {
            $this->errors[] = "Less than 9 players";
        }
        if ($num > 11) {
            $this->errors[] = "More than 11 players";
        }
        if ($this->goalkeeper == 0) {
            $this->errors[] = "No goalkeeper";
        }
        if (!in_array($def . '-' . $mid . '-' . $for, $lineups)) {
            $this->errors[] = "Invalid formation: " . $def . '-' . $mid . '-' . $for;
        }
        if ($this->coachModifier < -10 || $this->coachModifier > 10) {
            $this->errors[] = "Invalid coach modifier: " . $this->coachModifier . ". Value must be between -10 and 10";
        }
        if (count($team) != count(array_unique($team))) {
            $count = array_filter(array_count_values($team), create_function('$e', 'return $e>1;'));
            foreach ($count as $id => $use) {
                $this->errors[] = "Player " . $id . " used " . $use . " times";
            }
        }
        $nums = array(1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five');
        foreach ($nums as $i => $n) {
            $sub = $this->{'substitution' . $n};
            if ($sub !== null && !$sub instanceof OrdersSubstitution) {
                $this->errors[] = "Invalid substitution $i";
            } elseif ($sub !== null && $sub->orderType == $sub::TYPE_SUBSTITUTION) {
                if ($sub->playerIn && !in_array($sub->playerIn, array($this->substituteGoalkeeper, $this->substituteDefender, $this->substituteMidfield, $this->substituteForward, $this->substituteWinger))) {
                    $this->errors[] = "Substitution $i set a player which is not substitute : " . $sub->playerIn;
                }
            }
        }
        return $this->getErrorNumber() > 0;
    }

    /**
     * Return error number
     *
     * @return integer
     */
    public function getErrorNumber()
    {
        return count($this->errors);
    }

    /**
     * Return error
     *
     * @param integer $index
     * @return string
     */
    public function getError($index)
    {
        $index = round($index);
        if ($index > Config::$forIndex && $index < $this->getErrorNumber() + Config::$forIndex) {
            $index -= Config::$forIndex;
            return $this->errors[$index];
        }
        return null;
    }

    /**
     * Return errors
     *
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Return lineup as json string
     *
     * @return string
     */
    public function getJson()
    {
        $json = array();
        $json["positions"] = array(
            array("id" => $this->goalkeeper, "behaviour" => 0),
            array("id" => $this->rightDefender, "behaviour" => $this->rightDefenderBehaviour),
            array("id" => $this->rightCentralDefender, "behaviour" => $this->rightCentralDefenderBehaviour),
            array("id" => $this->centralDefender, "behaviour" => $this->centralDefenderBehaviour),
            array("id" => $this->leftCentralDefender, "behaviour" => $this->leftCentralDefenderBehaviour),
            array("id" => $this->leftDefender, "behaviour" => $this->leftDefenderBehaviour),
            array("id" => $this->rightWinger, "behaviour" => $this->rightWingerBehaviour),
            array("id" => $this->rightMidfield, "behaviour" => $this->rightMidfieldBehaviour),
            array("id" => $this->centralMidfield, "behaviour" => $this->centralMidfieldBehaviour),
            array("id" => $this->leftMidfield, "behaviour" => $this->leftMidfieldBehaviour),
            array("id" => $this->leftWinger, "behaviour" => $this->leftWingerBehaviour),
            array("id" => $this->rightForward, "behaviour" => $this->rightForwardBehaviour),
            array("id" => $this->centralForward, "behaviour" => $this->centralForwardBehaviour),
            array("id" => $this->leftForward, "behaviour" => $this->leftForwardBehaviour),
            array("id" => $this->substituteGoalkeeper, "behaviour" => 0),
            array("id" => $this->substituteDefender, "behaviour" => 0),
            array("id" => $this->substituteMidfield, "behaviour" => 0),
            array("id" => $this->substituteForward, "behaviour" => 0),
            array("id" => $this->substituteWinger, "behaviour" => 0),
            array("id" => $this->captain, "behaviour" => 0),
            array("id" => $this->setpieces, "behaviour" => 0),
            array("id" => $this->penaltyOne, "behaviour" => 0),
            array("id" => $this->penaltyTwo, "behaviour" => 0),
            array("id" => $this->penaltyThree, "behaviour" => 0),
            array("id" => $this->penaltyFour, "behaviour" => 0),
            array("id" => $this->penaltyFive, "behaviour" => 0),
            array("id" => $this->penaltySix, "behaviour" => 0),
            array("id" => $this->penaltySeven, "behaviour" => 0),
            array("id" => $this->penaltyEight, "behaviour" => 0),
            array("id" => $this->penaltyNine, "behaviour" => 0),
            array("id" => $this->penaltyTen, "behaviour" => 0),
            array("id" => $this->penaltyEleven, "behaviour" => 0)
        );
        $json["settings"] = array(
            "tactic" => $this->tactic,
            "coachModifier" => $this->coachModifier,
            "speechLevel" => $this->speechLevel,
            "newLineup" => ""
        );
        $json["substitutions"] = array();
        $nums = array('One', 'Two', 'Three', 'Four', 'Five');
        foreach ($nums as $i) {
            $sub = $this->{'substitution' . $i}();
            if ($sub === null || !$sub instanceof OrdersSubstitution) {
                continue;
            }
            $json["substitutions"][] = array(
                "playerin" => $sub->playerIn,
                "playerout" => $sub->playerInOut,
                "orderType" => $sub->orderType,
                "min" => $sub->minute,
                "pos" => $sub->position,
                "beh" => $sub->behaviour,
                "card" => $sub->card,
                "standing" => $sub->standing
            );
        }
        return json_encode($json);
    }
}
