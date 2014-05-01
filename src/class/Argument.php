<?php

namespace Com\PaulDevelop\Library\CommandLineArguments;

use Com\PaulDevelop\Library\Common\Base;

/**
 * Argument
 *
 * @package  Com\PaulDevelop\Library\CommandLineArguments
 * @category CommandLineArguments
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @property string $Name
 * @property string $Value
 * @property ArgumentType $Type
 */
class Argument extends Base
{
    #region member
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var ArgumentType
     */
    private $type;
    #endregion

    #region constructor
    /**
     * @param string       $name
     * @param string       $value
     * @param ArgumentType $type
     */
    public function __construct(
        $name = '',
        $value = '',
        ArgumentType $type = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }
    #endregion

    #region properties
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return ArgumentType
     */
    public function getType()
    {
        return $this->type;
    }
    #endregion
}
