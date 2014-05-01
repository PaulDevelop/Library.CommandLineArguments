<?php

namespace Com\PaulDevelop\Library\CommandLineArguments;

use Com\PaulDevelop\Library\Common\GenericCollection;

/**
 * ArgumentCollection
 *
 * @package  Com\PaulDevelop\Library\CommandLineArguments
 * @category CommandLineArguments
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
class ArgumentCollection extends GenericCollection
{
    public function __construct()
    {
        parent::__construct('Com\PaulDevelop\Library\CommandLineArguments\Argument');
    }
}
