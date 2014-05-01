<?php

/**
 * ArgumentType
 *
 * @package  Com\PaulDevelop\Library\CommandLineArguments
 * @category CommandLineArguments
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
namespace Com\PaulDevelop\Library\CommandLineArguments;

use Com\PaulDevelop\Library\Common\GenericFlag;

class ArgumentType extends GenericFlag
{
    #region const
    const PARAMETER = 1;
    const SHORT_FLAG = 2;
    const LONG_FLAG = 4;
    #endregion

    #region constructor
    public function __construct()
    {
        parent::__construct('Com\PaulDevelop\Library\CommandLineArguments\ArgumentType');
    }
    #endregion
}
