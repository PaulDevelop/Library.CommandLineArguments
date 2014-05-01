<?php

namespace Com\PaulDevelop\Library\CommandLineArguments;

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
    //
    // -a -b  => two flags
    // --AllEntries --BestBefore => twoFlags
    //
    // -a 1 -b foo   => two parameter: a with value 1, b with value foo
    //
    //
    //
    // usage: [-a] -b value2 [-key1] [-key2 value4] value5 [value6] ...
    // a,b:value?,key1?,key2:value,param1,param2?

    #region methods
    private static function parseArgumentSettings($argumentConfig = '')
    {
        // init
        $result = new ArgumentSettingCollection();

        // action
        $chunks = preg_split('/,/', $argumentConfig);
        foreach ($chunks as $chunk) {
            $variable = '';
            $optional = false;
            $type = new ArgumentType();

            if (substr($chunk, strlen($chunk) - 1, 1) == '?') {
                $optional = true;
                $chunk = substr($chunk, 0, strlen($chunk) - 1);
            }

            if (strpos($chunk, '=') > 0) {
                // argument with value
                list($keys, $variable) = preg_split('/=/', $chunk);
                list($parameterName, $shortFlagName, $longFlagName) = preg_split('/\//', $keys);
            } else {
                // argument without value
                list($parameterName, $shortFlagName, $longFlagName) = preg_split('/\//', $chunk);
            }

            if ($parameterName != '') {
                $type->addFlag(ArgumentType::PARAMETER);
            }
            if ($shortFlagName != '') {
                $type->addFlag(ArgumentType::SHORT_FLAG);
            }
            if ($longFlagName != '') {
                $type->addFlag(ArgumentType::LONG_FLAG);
            }

            $ac = new ArgumentSetting(
                $parameterName,
                $shortFlagName,
                $longFlagName,
                $variable,
                $optional,
                $type
            );
            $result->add($ac);
        }

        //var_dump($result);
        //var_dump($result['m']);
        //die;

        // return
        return $result;
    }

    /**
     * @param string $commandLineArguments
     * @param string $argumentSettings
     *
     * @return ArgumentCollection
     * @throws \Com\PaulDevelop\Library\Common\ArgumentException
     * @throws \Com\PaulDevelop\Library\Common\TypeCheckException
     */
    public static function parse($commandLineArguments = '', $argumentSettings = '')
    {
        // === short version ===
        // generator -m file [-mt dir] -t dir [-o dir] [-l list] [-c file] [-v]
        //
        // meta information
        //   /m/model=file,/mt/master-template=dir?,/t/template=dir,/o/output=dir?,/l/limit=list?,/c/config=file?,
        //   /v/verbose
        //
        // meta information for template specific arguments (templates/website-3.0.0/.generator/config.xml)
        //   database//=dsn,database-test//=dsn,application-host//=url,application-path//=path,
        //   application-name//=name,application-namespace//=name
        //
        // generator -m /home/vagrant/project/model/website.model.xml
        //           -mt /home/vagrant/project/.generator/templates/website-3.0.0
        //           -t /home/vagrant/project/_templates/
        //           -o /home/vagrant/project/
        //           -l ":"
        //           -c ""
        //           -v
        //
        // === long version ===
        // generator --model file [--master-template dir] --template dir [--output dir] [--limit list]
        //           [--config file] [--verbose]
        //
        // generator --model /home/vagrant/project/model/website.model.xml
        //           --master-template /home/vagrant/project/.generator/templates/website-3.0.0
        //           --template /home/vagrant/project/_templates/
        //           --output /home/vagrant/project/
        //           --limit ":"
        //           --config ""
        //           --verbose
        //
        // === template specific arguments ===
        //           --database=dsn
        //           --database-test=dsn
        //           --application-host=url
        //           --application-path=path
        //           --application-name=name
        //           --application-namespace=name
        //
        //           --database=pd:pd@127.0.0.1/pd_generator
        //           --database-test=test:test@127.0.0.1/test_pd_generator
        //           --application-host=http://pauldevelop.no-ip.info:94/
        //           --application-path=/
        //           --application-name=planetkeys
        //           --application-namespace=Com\\PlanetKeys\\Website
        //
        // === help ===
        // generator -m, --model file
        //           -mt, --master-template dir
        //           -t, --template dir
        //           -o, --output dir
        //           -l, --limit /dir1/:/dir2/
        //           -c, --config generator.xml
        //
        // === version ===

        // init
        $result = new ArgumentCollection();

        // action
        // parse argument settings
        $argumentSettingCollection = null;
        if ($argumentSettings != '') {
            $argumentSettingCollection = self::parseArgumentSettings($argumentSettings);
        }

        // parse arguments
        $stringIsOpen = false;
        $argumentIsOpen = false;

        $buffer = '';
        for ($i = 0; $i < strlen($commandLineArguments); $i++) {
            $currentChar = $commandLineArguments[$i];

            //echo $currentChar;

            if (!$argumentIsOpen) {
                if ($currentChar == Constants::SPACE) {
                    // ignore whitespace between arguments
                    continue;
                } else {
                    // open argument
                    $argumentIsOpen = true;

                    if ($currentChar == Constants::STRING_TERMINATOR) {
                        // open string
                        $stringIsOpen = true;
                    } else {
                        // add non space or string terminator char to buffer
                        $buffer .= $currentChar;
                    }

                }
            } else {
                if ($stringIsOpen) {
                    if ($currentChar == Constants::STRING_TERMINATOR) {
                        // end string
                        $stringIsOpen = false;

                        // end argument
                        $argument = self::endArgument($buffer);
                        $result->add($argument, $argument->Name);

                        $argumentIsOpen = false;
                        $buffer = '';
                    } else {
                        // add to buffer
                        $buffer .= $currentChar;
                    }
                } else {
                    if ($currentChar == Constants::STRING_TERMINATOR) {
                        // open string
                        $stringIsOpen = true;
                    } elseif ($currentChar == Constants::SPACE) {
                        // end argument
                        $argument = self::endArgument($buffer);
                        //var_dump($argument);
                        //var_dump($as[$argument->Name]);
                        //die;
                        $result->add($argument, $argument->Name);

                        $argumentIsOpen = false;
                        $buffer = '';
                    } else {
                        // add to buffer
                        $buffer .= $currentChar;
                    }
                }
            }
        }

        // process rest of buffer (input end)
        if (trim($buffer) != '') {
            $argument = self::endArgument($buffer);
            $result->add($argument, $argument->Name);
        }

        // checks
        if ($argumentSettingCollection != null) {
            $result = self::collapseNameAndValue($result, $argumentSettingCollection);
        }

        // check missing optional arguments

        // return
        return $result;
    }

    /**
     * @param string $buffer
     *
     * @return Argument
     */
    private static function endArgument($buffer = '')
    {
        // action
        //echo 'buffer: '.$buffer.PHP_EOL;
        //echo "================================================================================".PHP_EOL;
        //var_dump($buffer);

        //die;

        // name and value
        $name = $buffer;
        $value = '';
        if (($pos = strpos($buffer, '=')) != false) {
            $name = substr($buffer, 0, $pos);
            $value = substr($buffer, $pos + 1);
        }

        // type
        //$type = ArgumentType::PARAMETER;
        $type = new ArgumentType();
        if (substr($buffer, 0, 1) == '-') {
            if (substr($buffer, 0, 2) == '--') {
                $name = substr($name, 2);
                //$type = ArgumentType::LONG_FLAG;
                $type->addFlag(ArgumentType::LONG_FLAG);
            } else {
                $name = substr($name, 1);
                //$type = ArgumentType::SHORT_FLAG;
                $type->addFlag(ArgumentType::SHORT_FLAG);
            }
        } else {
            $type->addFlag(ArgumentType::PARAMETER);
        }

        //var_dump($name);
        //die;

        //$result = array($name => array('value' => $value, 'type' => $type));
        //$at = new ArgumentType();
        //$at->addFlag()
        $result = new Argument($name, $value, $type);

        //var_dump($result);

        // return
        return $result;
    }

    /**
     * @param ArgumentCollection        $argumentCollection
     * @param ArgumentSettingCollection $argumentSettingCollection
     *
     * @return ArgumentCollection
     * @throws \Com\PaulDevelop\Library\Common\ArgumentException
     * @throws \Com\PaulDevelop\Library\Common\TypeCheckException
     */
    public static function collapseNameAndValue(
        ArgumentCollection $argumentCollection,
        ArgumentSettingCollection $argumentSettingCollection
    ) {
        // combine arguments and values
        $result = new ArgumentCollection();
        $argumentNames = array_keys($argumentCollection->getIterator()->getArrayCopy());
        for ($i = 0; $i < count($argumentNames); $i++) {
            /** @var Argument $argument */
            $argument = $argumentCollection[$argumentNames[$i]];
            $argumentSetting = $argumentSettingCollection[$argumentNames[$i]];
            /** @var ArgumentSetting $argumentSetting */
            if ($argumentSetting == null
                || $argumentSetting->Variable == ''
                || ($argumentSetting->Variable != '' && $argument->Value != '')
            ) {
                $newArgument = new Argument(
                    $argument->Name,
                    $argument->Value,
                    $argument->Type
                );
                $result->add($newArgument, $newArgument->Name);
            } else {
                // get name of next argument and set as value
                $nextArgument = $argumentCollection[$argumentNames[$i + 1]];
                /** @var Argument $nextArgument */
                $newArgument = new Argument(
                    $argument->Name,
                    $nextArgument->Name,
                    $argument->Type
                );
                $result->add($newArgument, $newArgument->Name);
                $i++;
            }
        }
        return $result;
    }
    #endregion
}
