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

namespace PHT\Utils;

class XmlIterator implements \Iterator
{
    /**
     * @var \DOMNodeList
     */
    private $nodes;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $item;

    /**
     * @var mixed
     */
    private $param;

    /**
     * Create xml iterator
     *
     * @param \DOMNodeList $nodes
     * @param string $type
     * @param mixed $extraParam
     */
    public function __construct($nodes, $type, $extraParam = null)
    {
        $this->nodes = $nodes;
        $this->type = $type;
        $this->item = 0;
        if ($extraParam !== null) {
            $this->param = $extraParam;
        }
    }

    /**
     * @return mixed
     */
    public function current()
    {
        $node = new \DOMDocument('1.0', 'UTF-8');
        $node->appendChild($node->importNode($this->nodes->item($this->item), true));
        if (isset($this->param)) {
            return new $this->type($node, $this->param);
        }
        return new $this->type($node);
    }

    /**
     * @return integer
     */
    public function key()
    {
        return $this->item;
    }

    public function next()
    {
        $this->item++;
    }

    public function rewind()
    {
        $this->item = 0;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return $this->item < $this->nodes->length;
    }
}
