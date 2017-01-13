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

namespace PHT\Xml\User;

use PHT\Xml;
use PHT\Config;
use PHT\Utils;

class Bookmarks extends Xml\File
{
    /**
     * Return number of bookmarks
     *
     * @return integer
     */
    public function getBookmarkNumber()
    {
        return $this->getXml()->getElementsByTagName('Bookmark')->length;
    }

    /**
     * Return bookmark element object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Element
     */
    public function getBookmark($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//Bookmark');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index), true));
            return new Bookmark\Element($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark element objects
     *
     * @return \PHT\Xml\User\Bookmark\Element[]
     */
    public function getBookmarks()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Element[] $data */
        $data = new Utils\XmlIterator($nodeList, '\PHT\Xml\User\Bookmark\Element');
        return $data;
    }

    /**
     * Return number of senior team bookmarks
     *
     * @return integer
     */
    public function getBookmarkSeniorTeamNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_TEAM . '"]')->length;
    }

    /**
     * Return bookmark senior team object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Team\Senior
     */
    public function getBookmarkSeniorTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkSeniorTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_TEAM . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Team\Senior($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark senior team objects
     *
     * @return \PHT\Xml\User\Bookmark\Team\Senior[]
     */
    public function getBookmarkSeniorTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_TEAM . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Team\Senior[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Team\Senior');
        return $data;
    }

    /**
     * Return number of senior player bookmarks
     *
     * @return integer
     */
    public function getBookmarkSeniorPlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_PLAYER . '"]')->length;
    }

    /**
     * Return bookmark senior player object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Player\Senior
     */
    public function getBookmarkSeniorPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkSeniorPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_PLAYER . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Player\Senior($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark senior player objects
     *
     * @return \PHT\Xml\User\Bookmark\Player\Senior[]
     */
    public function getBookmarkSeniorPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_PLAYER . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Player\Senior[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Player\Senior');
        return $data;
    }

    /**
     * Return number of senior match bookmarks
     *
     * @return integer
     */
    public function getBookmarkSeniorMatchNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_MATCH . '"]')->length;
    }

    /**
     * Return bookmark match object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Match
     */
    public function getBookmarkSeniorMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkSeniorMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_MATCH . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Match($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark match objects
     *
     * @return \PHT\Xml\User\Bookmark\Match[]
     */
    public function getBookmarkSeniorMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_MATCH . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Match[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Match');
        return $data;
    }

    /**
     * Return number of user bookmarks
     *
     * @return integer
     */
    public function getBookmarkUserNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_USER . '"]')->length;
    }

    /**
     * Return bookmark user object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\User
     */
    public function getBookmarkUser($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkUserNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_USER . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\User($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark user objects
     *
     * @return \PHT\Xml\User\Bookmark\User[]
     */
    public function getBookmarkUsers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_USER . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\User[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\User');
        return $data;
    }

    /**
     * Return number of senior league bookmarks
     *
     * @return integer
     */
    public function getBookmarkSeniorLeagueNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_LEAGUE . '"]')->length;
    }

    /**
     * Return bookmark senior league object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\League\Senior
     */
    public function getBookmarkSeniorLeague($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkSeniorLeagueNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_LEAGUE . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\League\Senior($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark senior league objects
     *
     * @return \PHT\Xml\User\Bookmark\League\Senior[]
     */
    public function getBookmarkSeniorLeagues()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_SENIOR_LEAGUE . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\League\Senior[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\League\Senior');
        return $data;
    }

    /**
     * Return number of youth team bookmarks
     *
     * @return integer
     */
    public function getBookmarkYouthTeamNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_TEAM . '"]')->length;
    }

    /**
     * Return bookmark youth team object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Team\Youth
     */
    public function getBookmarkYouthTeam($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkYouthTeamNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_TEAM . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Team\Youth($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark youth team objects
     *
     * @return \PHT\Xml\User\Bookmark\Team\Youth[]
     */
    public function getBookmarkYouthTeams()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_TEAM . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Team\Youth[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Team\Youth');
        return $data;
    }

    /**
     * Return number of youth player bookmarks
     *
     * @return integer
     */
    public function getBookmarkYouthPlayerNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_PLAYER . '"]')->length;
    }

    /**
     * Return bookmark youth player object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Player\Youth
     */
    public function getBookmarkYouthPlayer($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkYouthPlayerNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_PLAYER . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Player\Youth($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark youth player objects
     *
     * @return \PHT\Xml\User\Bookmark\Player\Youth[]
     */
    public function getBookmarkYouthPlayers()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_PLAYER . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Player\Youth[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Player\Youth');
        return $data;
    }

    /**
     * Return number of youth match bookmarks
     *
     * @return integer
     */
    public function getBookmarkYouthMatchNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_MATCH . '"]')->length;
    }

    /**
     * Return bookmark match object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Match
     */
    public function getBookmarkYouthMatch($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkSeniorMatchNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_MATCH . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Match($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark match objects
     *
     * @return \PHT\Xml\User\Bookmark\Match[]
     */
    public function getBookmarkYouthMatches()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_MATCH . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Match[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Match');
        return $data;
    }

    /**
     * Return number of youth league bookmarks
     *
     * @return integer
     */
    public function getBookmarkYouthLeagueNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_LEAGUE . '"]')->length;
    }

    /**
     * Return bookmark youth league object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\League\Youth
     */
    public function getBookmarkYouthLeague($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkYouthLeagueNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_LEAGUE . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\League\Youth($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark youth league objects
     *
     * @return \PHT\Xml\User\Bookmark\League\Youth[]
     */
    public function getBookmarkYouthLeagues()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_YOUTH_LEAGUE . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\League\Youth[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\League\Youth');
        return $data;
    }

    /**
     * Return number of forum post bookmarks
     *
     * @return integer
     */
    public function getBookmarkPostNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_FORUM_POST . '"]')->length;
    }

    /**
     * Return bookmark forum post object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Post
     */
    public function getBookmarkPost($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkPostNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_FORUM_POST . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Post($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark post objects
     *
     * @return \PHT\Xml\User\Bookmark\Post[]
     */
    public function getBookmarkPosts()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_FORUM_POST . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Post[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Post');
        return $data;
    }

    /**
     * Return number of forum thread bookmarks
     *
     * @return integer
     */
    public function getBookmarkThreadNumber()
    {
        $xpath = new \DOMXPath($this->getXml());
        return $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_FORUM_THREAD . '"]')->length;
    }

    /**
     * Return bookmark forum thread object
     *
     * @param integer $index
     * @return \PHT\Xml\User\Bookmark\Thread
     */
    public function getBookmarkThread($index)
    {
        $index = round($index);
        if ($index >= Config\Config::$forIndex && $index < $this->getBookmarkThreadNumber() + Config\Config::$forIndex) {
            $index -= Config\Config::$forIndex;
            $xpath = new \DOMXPath($this->getXml());
            $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_FORUM_THREAD . '"]');
            $bookmark = new \DOMDocument('1.0', 'UTF-8');
            $bookmark->appendChild($bookmark->importNode($nodeList->item($index)->parentNode, true));
            return new Bookmark\Thread($bookmark);
        }
        return null;
    }

    /**
     * Return iterator of bookmark thread objects
     *
     * @return \PHT\Xml\User\Bookmark\Thread[]
     */
    public function getBookmarkThreads()
    {
        $xpath = new \DOMXPath($this->getXml());
        $nodeList = $xpath->query('//BookmarkTypeID[.="' . Config\Config::BOOKMARK_FORUM_THREAD . '"]');
        $bookmark = new \DOMDocument('1.0', 'UTF-8');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $bookmark->appendChild($bookmark->importNode($nodeList->item($i)->parentNode, true));
        }
        $nodes = $bookmark->getElementsByTagName('Bookmark');
        /** @var \PHT\Xml\User\Bookmark\Thread[] $data */
        $data = new Utils\XmlIterator($nodes, '\PHT\Xml\User\Bookmark\Thread');
        return $data;
    }
}
