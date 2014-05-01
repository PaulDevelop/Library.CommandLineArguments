<?php

namespace Com\PaulDevelop\Library\CommandLineArguments;

use Com\PaulDevelop\Library\Common\GenericCollection;

/**
 * ArgumentSettingCollection
 *
 * @package  Com\PaulDevelop\Library\CommandLineArguments
 * @category CommandLineArguments
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
class ArgumentSettingCollection extends GenericCollection
{
    private $parameterNames;
    private $shortFlagNames;
    private $longFlagNames;

    public function __construct()
    {
        $this->parameterNames = array();
        $this->shortFlagNames = array();
        $this->longFlagNames = array();
        parent::__construct('Com\PaulDevelop\Library\CommandLineArguments\ArgumentSetting');
    }

    /**
     * @param ArgumentSetting $value
     * @param string          $parameterName
     * @param string          $shortFlagName
     * @param string          $longFlagName
     *
     * @throws \Com\PaulDevelop\Library\Common\ArgumentException
     * @throws \Com\PaulDevelop\Library\Common\TypeCheckException
     */
    public function add(ArgumentSetting $value = null, $parameterName = '', $shortFlagName = '', $longFlagName = '')
    {
        $id = count($this->parameterNames);
        $this->parameterNames[$id] = $value->ParameterName;
        $this->shortFlagNames[$id] = $value->ShortFlagName;
        $this->longFlagNames[$id] = $value->LongFlagName;
        parent::add($value);
    }

    /**
     * @param string $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset = null)
    {
        // init
        $result = null;

        // action
        foreach ($this->parameterNames as $id => $parameterName) {
            if ($parameterName == $offset) {
                $result = isset($this->collection[$id]) ? $this->collection[$id] : null;
                break;
            }
        }

        if ($result == null) {
            foreach ($this->shortFlagNames as $id => $shortFlagName) {
                if ($shortFlagName == $offset) {
                    $result = isset($this->collection[$id]) ? $this->collection[$id] : null;
                    break;
                }
            }
        }

        if ($result == null) {
            foreach ($this->longFlagNames as $id => $longFlagName) {
                if ($longFlagName == $offset) {
                    $result = isset($this->collection[$id]) ? $this->collection[$id] : null;
                    break;
                }
            }
        }

        // return
        return $result;
    }
}
