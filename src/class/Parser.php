<?php
namespace Com\PaulDevelop\Library\Processing\CommandLineArguments;

/**
 * Parser
 *
 * @package  Com\PaulDevelop\Library\CommandLineArguments
 * @category CommandLineArguments
 * @author   Rüdiger Scheumann <code@pauldevelop.com>
 * @license  http://opensource.org/licenses/MIT MIT
 */
abstract class Parser
{
    // key1=value
    // key1="value"
    // -k=value
    // -k="value"
    // --key=value
    // --key="value"

    // -a -b  => two flags
    // --AllEntries --BestBefore => twoFlags

    // -a 1 -b foo   => two parameter: a with value 1, b with value foo
    //
    //

    #region methods
    /**
     * @param string $commandLineArguments
     *
     * @return array
     */
    public static function Parse($commandLineArguments = '')
    {
        // --- init ---
        $result = array();

        // --- action ---
        $stringIsOpen = false;
        $argumentIsOpen = false;
        $isShortFlag = false;
        $isLongFlag = false;

        define('SPACE', ' ');

        $parameter = array();

        $buffer = '';
        $lastChar = '';
        $currentChar = '';
        $nextChar = '';
        for ($i = 0; $i < strlen($commandLineArguments); $i++) {
            if (($i - 1) > 0) {
                $lastChar = $commandLineArguments[($i - 1)];
            }
            $currentChar = $commandLineArguments[$i];
            if (($i + 1) < strlen($commandLineArguments)) {
                $nextChar = $commandLineArguments[($i + 1)];
            }

            if (!$argumentIsOpen
                && $currentChar == SPACE
            ) {
                continue;
            }

            if (!$argumentIsOpen
                && $currentChar != SPACE
            ) {
                $argumentIsOpen = true;
                $buffer .= $currentChar;
            }
            else if ($argumentIsOpen
                && $currentChar != SPACE
            ) {
                $buffer .= $currentChar;
            } else if ($argumentIsOpen
                && $currentChar == SPACE
            ) {
                $argumentIsOpen = false;
                $buffer .= $currentChar;

                //echo " ".$buffer."\n";

                if (strlen($buffer) > 0 && substr($buffer, 0, 1) == '-') {
                    if (strlen($buffer) > 1 && substr($buffer, 0, 2) == '--') {
                        $isLongFlag = true;
                    } else {
                        $isShortFlag = true;
                    }
                }

                // check, if previous argument had leading -
                if (($isLongFlag || $isShortFlag) && sizeof($parameter) > 0) {
                    $name = $parameter[(sizeof($parameter) - 1)]['name'];
                    if (substr($name, 0, 1) == '-') {
                        if (substr($name, 0, 2) == '--') {
                            $parameter[(sizeof($parameter) - 1)]['type'] = 'flagLong';
                        } else {
                            $parameter[(sizeof($parameter) - 1)]['type'] = 'flagShort';
                        }
                    }
                }

                $previousArgumentIsFlag = false;
                if (sizeof($parameter) > 0) {
                    $name = $parameter[(sizeof($parameter) - 1)]['name'];
                    if (substr($name, 0, 1) == '-') {
                        $previousArgumentIsFlag = true;
                    }
                }

                $value = '';
                $pos = strpos($buffer, '=');
                if (($pos = strpos($buffer, '=')) != false) {
                    $value = substr($buffer, $pos + 1);
                    $buffer = substr($buffer, 0, $pos);
                }

                $buffer = trim($buffer);
                $value = trim($value);

                if (!$isLongFlag && !$isShortFlag && $previousArgumentIsFlag) {
                    $parameter[(sizeof($parameter) - 1)]['value'] = $buffer;
                } else {
                    array_push($parameter, array('type' => 'parameter', 'name' => $buffer, 'value' => $value));
                }

                $buffer = '';
                $argumentIsOpen = false;
                $stringIsOpen = false;
                $isShortFlag = false;
                $isLongFlag = false;
            }
        }

        //echo " ".$buffer."\n";
        if (strlen($buffer) > 0 && substr($buffer, 0, 1) == '-') {
            if (strlen($buffer) > 1 && substr($buffer, 0, 2) == '--') {
                $isLongFlag = true;
            } else {
                $isShortFlag = true;
            }
        }

        // check, if previous argument had leading -
        if (($isLongFlag || $isShortFlag) && sizeof($parameter) > 0) {
            $name = $parameter[(sizeof($parameter) - 1)]['name'];
            //echo $name."<br />\n";
            if (substr($name, 0, 1) == '-') {
                if (substr($name, 0, 2) == '--') {
                    $parameter[(sizeof($parameter) - 1)]['type'] = 'flagLong';
                } else {
                    $parameter[(sizeof($parameter) - 1)]['type'] = 'flagShort';
                }
            }
        }

        $previousArgumentIsFlag = false;
        if (sizeof($parameter) > 0) {
            $name = $parameter[(sizeof($parameter) - 1)]['name'];
            if (substr($name, 0, 1) == '-') {
                $previousArgumentIsFlag = true;
            }
        }

        $value = '';
        if (($pos = strpos($buffer, '=')) != false) {
            $value = substr($buffer, $pos + 1);
            $buffer = substr($buffer, 0, $pos);
        }

        $buffer = trim($buffer);
        $value = trim($value);

        if (!$isLongFlag && !$isShortFlag && $previousArgumentIsFlag) {
            $parameter[(sizeof($parameter) - 1)]['value'] = $buffer;
        } else {
            array_push($parameter, array('type' => 'parameter', 'name' => $buffer, 'value' => $value));
        }


        array_walk(
            $parameter, function (&$p = array()) {
                $p['name'] = preg_replace('/^\-+/', '', $p['name']);
            }
        );
        //var_dump($parameter);

        $result = array();
        foreach ($parameter as $argument) {
            $result[$argument['name']] = array('value' => $argument['value'], 'type' => $argument['type']);
        }

        // --- return ---
        return $result;
    }

    #endregion
}
