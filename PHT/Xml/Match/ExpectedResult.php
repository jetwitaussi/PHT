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

namespace PHT\Xml\Match;

use PHT\Xml;

class ExpectedResult extends Xml\Base
{
    /**
     * @param \DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xmlText = $xml->saveXML();
        $this->xml = $xml;
    }

    /**
     * Return number of simulated replays used for statistics
     *
     * @return integer
     */
    public function getSampleNumber()
    {
        return $this->getValue('/ExpectedResult/NrOfSamples');
    }

    /**
     * Return number of simulated replays won by home team (including penalty shootouts)
     *
     * @return integer
     */
    public function getHomeWins()
    {
        return $this->getValue('/ExpectedResult/HomeWins');
    }

    /**
     * Return number of simulated replays ended in a draw (including penalty shootouts)
     *
     * @return integer
     */
    public function getDraws()
    {
        return $this->getValue('/ExpectedResult/Draws');
    }

    /**
     * Return number of simulated replays won by away team (including penalty shootouts)
     *
     * @return integer
     */
    public function getAwayWins()
    {
        return $this->getValue('/ExpectedResult/AwayWins');
    }

    /**
     * Return percentage of simulated replays won by home team
     *
     * @return float
     */
    public function getHomeWinsPercentage()
    {
        return $this->getPercentage($this->getHomeWins());
    }

    /**
     * Return percentage of simulated replays ended in a draw
     *
     * @return float
     */
    public function getDrawsPercentage()
    {
        return $this->getPercentage($this->getDraws());
    }

    /**
     * Return percentage of simulated replays won by away team
     *
     * @return float
     */
    public function getAwayWinsPercentage()
    {
        return $this->getPercentage($this->getAwayWins());
    }

    /**
     * Return average number of goals scored by home team
     *
     * @return float
     */
    public function getAverageHomeGoals()
    {
        return $this->getValue('/ExpectedResult/AverageHomeGoals');
    }

    /**
     * Return average number of goals scored by away team
     *
     * @return float
     */
    public function getAverageAwayGoals()
    {
        return $this->getValue('/ExpectedResult/AverageAwayGoals');
    }

    /**
     * Return if full time results (before penalty shootout) are available, only for cup rules matches
     *
     * @return boolean
     */
    public function hasFullTime()
    {
        return $this->getValue('/ExpectedResult/FullTime/HomeWins') !== null;
    }

    /**
     * Return number of simulated replays won by home team before any penalty shootout, only for cup rules matches
     *
     * @return integer
     */
    public function getFullTimeHomeWins()
    {
        return $this->getValue('/ExpectedResult/FullTime/HomeWins');
    }

    /**
     * Return number of simulated replays level before any penalty shootout, only for cup rules matches
     *
     * @return integer
     */
    public function getFullTimeDraws()
    {
        return $this->getValue('/ExpectedResult/FullTime/Draws');
    }

    /**
     * Return number of simulated replays won by away team before any penalty shootout, only for cup rules matches
     *
     * @return integer
     */
    public function getFullTimeAwayWins()
    {
        return $this->getValue('/ExpectedResult/FullTime/AwayWins');
    }

    /**
     * Return number of simulated replays where home team scored exactly $goals goals,
     * use 6 for more than 5 goals
     *
     * @param integer $goals (0 to 6)
     * @return integer
     */
    public function getHomeGoalDistribution($goals)
    {
        return $this->getGoalDistribution('Home', $goals);
    }

    /**
     * Return number of simulated replays where away team scored exactly $goals goals,
     * use 6 for more than 5 goals
     *
     * @param integer $goals (0 to 6)
     * @return integer
     */
    public function getAwayGoalDistribution($goals)
    {
        return $this->getGoalDistribution('Away', $goals);
    }

    /**
     * Return average goal margin of home team wins (without penalty shootout)
     *
     * @return float
     */
    public function getHomeAverageWinMargin()
    {
        return $this->getValue('/ExpectedResult/AverageWinMargin/Home');
    }

    /**
     * Return average goal margin of away team wins (without penalty shootout)
     *
     * @return float
     */
    public function getAwayAverageWinMargin()
    {
        return $this->getValue('/ExpectedResult/AverageWinMargin/Away');
    }

    /**
     * Return number of simulated replays with more total goals than $line
     *
     * @param float $line (0.5, 1.5, 2.5, 3.5 or 4.5)
     * @return integer
     */
    public function getOver($line)
    {
        return $this->getOverUnder($line, 'Over');
    }

    /**
     * Return number of simulated replays with fewer total goals than $line
     *
     * @param float $line (0.5, 1.5, 2.5, 3.5 or 4.5)
     * @return integer
     */
    public function getUnder($line)
    {
        return $this->getOverUnder($line, 'Under');
    }

    /**
     * @param string $team
     * @param integer $goals
     * @return integer
     */
    private function getGoalDistribution($team, $goals)
    {
        $goals = (int)$goals;
        if ($goals < 0 || $goals > 6) {
            return null;
        }
        $tag = $goals == 6 ? 'GoalsMoreThan5' : 'Goals' . $goals;
        return $this->getValue('/ExpectedResult/GoalDistribution/' . $team . '/' . $tag);
    }

    /**
     * @param float $line
     * @param string $type
     * @return integer
     */
    private function getOverUnder($line, $type)
    {
        $line = (string)(floor((float)$line) * 10 + 5);
        $line = str_pad($line, 2, '0', STR_PAD_LEFT);
        return $this->getValue('/ExpectedResult/OverUnder/Line' . $line . '/' . $type);
    }

    /**
     * @param integer $value
     * @return float
     */
    private function getPercentage($value)
    {
        $samples = $this->getSampleNumber();
        if (!$samples) {
            return null;
        }
        return round($value * 100 / $samples, 2);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getValue($path)
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query($path);
        if ($nodeList->length) {
            return $nodeList->item(0)->nodeValue;
        }
        return null;
    }
}
