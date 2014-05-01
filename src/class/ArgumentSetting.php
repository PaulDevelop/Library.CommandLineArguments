<?php

namespace Com\PaulDevelop\Library\CommandLineArguments;

use Com\PaulDevelop\Library\Common\Base;

/**
 * ArgumentSetting
 *
 * @package  Com\PaulDevelop\Library\CommandLineArguments
 * @category CommandLineArguments
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @property string       $ParameterName
 * @property string       $ShortFlagName
 * @property string       $LongFlagName
 * @property string       $Variable
 * @property bool         $Optional
 * @property ArgumentType $Type
 */
class ArgumentSetting extends Base
{
    #region member
    /**
     * @var string
     */
    private $parameterName;

    /**
     * @var string
     */
    private $shortFlagName;

    /**
     * @var string
     */
    private $longFlagName;

    /**
     * @var string
     */
    private $variable;

    /**
     * @var bool
     */
    private $optional;

    /**
     * @var ArgumentType
     */
    private $type;
    #endregion

    #region constructor
    public function __construct(
        $parameterName = '',
        $shortFlagName = '',
        $longFlagName = '',
        $variable = '',
        $optional = false,
        ArgumentType $type = null
    ) {
        $this->parameterName = $parameterName;
        $this->shortFlagName = $shortFlagName;
        $this->longFlagName = $longFlagName;
        $this->variable = $variable;
        $this->optional = $optional;
        $this->type = $type;
    }
    #endregion

    #region properties
    /**
     * @return string
     */
    public function getParameterName()
    {
        return $this->parameterName;
    }

    /**
     * @return string
     */
    public function getShortFlagName()
    {
        return $this->shortFlagName;
    }

    /**
     * @return string
     */
    public function getLongFlagName()
    {
        return $this->longFlagName;
    }

    /**
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @return bool
     */
    public function getOptional()
    {
        return $this->optional;
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
