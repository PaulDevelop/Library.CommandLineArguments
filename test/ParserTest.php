<?php

namespace Com\PaulDevelop\CommandLineArguments;

use Com\PaulDevelop\Library\CommandLineArguments\Argument;
use Com\PaulDevelop\Library\CommandLineArguments\ArgumentCollection;
use Com\PaulDevelop\Library\CommandLineArguments\ArgumentType;
use Com\PaulDevelop\Library\CommandLineArguments\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testArgumentShortFlagWithSettings()
    {
        $expected = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $expected->add(new Argument('m', '/home/vagrant/project/model/website.model.xml', $type), 'm');
        $expected->add(new Argument('mt', '/home/vagrant/project/.generator/templates/website-3.0.0', $type), 'mt');
        $expected->add(new Argument('t', '/home/vagrant/project/_templates/', $type), 't');
        $expected->add(new Argument('o', '/home/vagrant/project/', $type), 'o');
        $expected->add(new Argument('l', 'src/controller/frontend/:src/template/frontend/', $type), 'l');
        $expected->add(new Argument('c', '/home/vagrant/project/.generator/generator.xml', $type), 'c');
        $expected->add(new Argument('v', '', $type), 'v');

        $actual = Parser::parse(
            '-m /home/vagrant/project/model/website.model.xml '.
            '-mt /home/vagrant/project/.generator/templates/website-3.0.0 '.
            '-t /home/vagrant/project/_templates/ '.
            '-o /home/vagrant/project/ '.
            '-l src/controller/frontend/:src/template/frontend/ '.
            '-c /home/vagrant/project/.generator/generator.xml '.
            '-v',
            '/m/model=file,/mt/master-template=dir?,/t/template=dir,/o/output=dir?,/l/limit=list?,/c/config=file?,/v/verbose'
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testArgumentLongFlagWithSettings()
    {
        $expected = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $expected->add(new Argument('model', '/home/vagrant/project/model/website.model.xml', $type), 'model');
        $expected->add(
            new Argument(
                'master-template', '/home/vagrant/project/.generator/templates/website-3.0.0',
                $type
            ), 'master-template'
        );
        $expected->add(new Argument('template', '/home/vagrant/project/_templates/', $type), 'template');
        $expected->add(new Argument('output', '/home/vagrant/project/', $type), 'output');
        $expected->add(new Argument('limit', 'src/controller/frontend/:src/template/frontend/', $type), 'limit');
        $expected->add(new Argument('config', '/home/vagrant/project/.generator/generator.xml', $type), 'config');
        $expected->add(new Argument('verbose', '', $type), 'verbose');

        $actual = Parser::parse(
            '-model /home/vagrant/project/model/website.model.xml '.
            '-master-template /home/vagrant/project/.generator/templates/website-3.0.0 '.
            '-template /home/vagrant/project/_templates/ '.
            '-output /home/vagrant/project/ '.
            '-limit src/controller/frontend/:src/template/frontend/ '.
            '-config /home/vagrant/project/.generator/generator.xml '.
            '-verbose',
            '/m/model=file,/mt/master-template=dir?,/t/template=dir,/o/output=dir?,/l/limit=list?,/c/config=file?,/v/verbose'
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testArgumentShortFlagWithSettingsAndTemplateParameter()
    {
        $expected = new ArgumentCollection();
        $typeShortFlag = new ArgumentType();
        $typeShortFlag->addFlag(ArgumentType::SHORT_FLAG);
        $expected->add(new Argument('m', '/home/vagrant/project/model/website.model.xml', $typeShortFlag), 'm');
        $expected->add(
            new Argument('mt', '/home/vagrant/project/.generator/templates/website-3.0.0', $typeShortFlag), 'mt'
        );
        $expected->add(new Argument('t', '/home/vagrant/project/_templates/', $typeShortFlag), 't');
        $expected->add(new Argument('o', '/home/vagrant/project/', $typeShortFlag), 'o');
        $expected->add(new Argument('l', 'src/controller/frontend/:src/template/frontend/', $typeShortFlag), 'l');
        $expected->add(new Argument('c', '/home/vagrant/project/.generator/generator.xml', $typeShortFlag), 'c');
        $expected->add(new Argument('v', '', $typeShortFlag), 'v');
        $typeLongFlag = new ArgumentType();
        $typeLongFlag->addFlag(ArgumentType::LONG_FLAG);
        $expected->add(new Argument('database', 'pd:pd@127.0.0.1/pd_generator', $typeLongFlag), 'database');
        $expected->add(
            new Argument('database-test', 'test:test@127.0.0.1/test_pd_generator', $typeLongFlag),
            'database-test'
        );
        $expected->add(
            new Argument('application-host', 'http://generator.pauldevelop.de/', $typeLongFlag),
            'application-host'
        );
        $expected->add(new Argument('application-path', '/', $typeLongFlag), 'application-path');
        $expected->add(new Argument('application-name', 'generator', $typeLongFlag), 'application-name');
        $expected->add(
            new Argument('application-namespace', 'Com\\PaulDevelop\\Generator\\Website', $typeLongFlag),
            'application-namespace'
        );

        $actual = Parser::parse(
            '-m /home/vagrant/project/model/website.model.xml '.
            '-mt /home/vagrant/project/.generator/templates/website-3.0.0 '.
            '-t /home/vagrant/project/_templates/ '.
            '-o /home/vagrant/project/ '.
            '-l src/controller/frontend/:src/template/frontend/ '.
            '-c /home/vagrant/project/.generator/generator.xml '.
            '-v '.
            '--database=pd:pd@127.0.0.1/pd_generator '.
            '--database-test=test:test@127.0.0.1/test_pd_generator '.
            '--application-host=http://generator.pauldevelop.de/ '.
            '--application-path=/ '.
            '--application-name=generator '.
            '--application-namespace=Com\\PaulDevelop\\Generator\\Website',
            '/m/model=file,/mt/master-template=dir?,/t/template=dir,/o/output=dir?,/l/limit=list?,/c/config=file?'.
            ',/v/verbose,//database=dsn,//database-test=dsn,//application-host=url,//application-path=path'.
            ',//application-name=name,//application-namespace=name'
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testSimpleParameter()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('value', '', $type), 'value');
        $this->assertEquals(
            $ac,
            Parser::parse('value')
        );
    }

    /**
     * @test
     */
    public function testSimpleParameterWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('whitespace value', '', $type), 'whitespace value');
        $this->assertEquals(
            $ac,
            Parser::parse('"whitespace value"')
        );
    }

    /**
     * @test
     */
    public function testMultipleSimpleParameter()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('value1', '', $type), 'value1');
        $ac->add(new Argument('value2', '', $type), 'value2');
        $this->assertEquals(
            $ac,
            Parser::parse('value1 value2')
        );
    }

    /**
     * @test
     */
    public function testMultipleSimpleParameterWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('whitespace value 1', '', $type), 'whitespace value 1');
        $ac->add(new Argument('whitespace value 2', '', $type), 'whitespace value 2');

        $this->assertEquals(
            $ac,
            Parser::parse('"whitespace value 1" "whitespace value 2"')
        );
    }

    /**
     * @test
     */
    public function testNamedParameter()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('flag', 'value', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('flag=value')
        );
    }

    /**
     * @test
     */
    public function testNamedParameterWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('flag', 'whitespace value', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('flag="whitespace value"')
        );
    }

    /**
     * @test
     */
    public function testMultipleNamedParameter()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('flag1', 'value1', $type), 'flag1');
        $ac->add(new Argument('flag2', 'value2', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('flag1=value1 flag2=value2')
        );
    }

    /**
     * @test
     */
    public function testMultipleNamedParameterWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::PARAMETER);
        $ac->add(new Argument('flag1', 'whitespace value 1', $type), 'flag1');
        $ac->add(new Argument('flag2', 'whitespace value 2', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('flag1="whitespace value 1" flag2="whitespace value 2"')
        );
    }

    /**
     * @test
     */
    public function testSimpleShortFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $ac->add(new Argument('flag', '', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('-flag')
        );
    }

    /**
     * @test
     */
    public function testMultipleSimpleShortFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $ac->add(new Argument('flag1', '', $type), 'flag1');
        $ac->add(new Argument('flag2', '', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('-flag1 -flag2')
        );
    }

    /**
     * @test
     */
    public function testNamedShortFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $ac->add(new Argument('flag', 'value', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('-flag=value')
        );
    }

    /**
     * @test
     */
    public function testNamedShortflagWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $ac->add(new Argument('flag', 'whitespace value', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('-flag="whitespace value"')
        );
    }

    /**
     * @test
     */
    public function testMultipleNamedShortFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $ac->add(new Argument('flag1', 'value1', $type), 'flag1');
        $ac->add(new Argument('flag2', 'value2', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('-flag1=value1 -flag2=value2')
        );
    }

    /**
     * @test
     */
    public function testMultipleShortFlagWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::SHORT_FLAG);
        $ac->add(new Argument('flag1', 'whitespace value 1', $type), 'flag1');
        $ac->add(new Argument('flag2', 'whitespace value 2', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('-flag1="whitespace value 1" -flag2="whitespace value 2"')
        );
    }

    /**
     * @test
     */
    public function testSimpleLongFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::LONG_FLAG);
        $ac->add(new Argument('flag', '', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('--flag')
        );
    }

    /**
     * @test
     */
    public function testMultipleSimpleLongFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::LONG_FLAG);
        $ac->add(new Argument('flag1', '', $type), 'flag1');
        $ac->add(new Argument('flag2', '', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('--flag1 --flag2')
        );
    }

    /**
     * @test
     */
    public function testNamedLongFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::LONG_FLAG);
        $ac->add(new Argument('flag', 'value', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('--flag=value')
        );
    }

    /**
     * @test
     */
    public function testNamedLongFlagWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::LONG_FLAG);
        $ac->add(new Argument('flag', 'whitespace value', $type), 'flag');
        $this->assertEquals(
            $ac,
            Parser::parse('--flag="whitespace value"')
        );
    }

    /**
     * @test
     */
    public function testMultipleNamedLongFlag()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::LONG_FLAG);
        $ac->add(new Argument('flag1', 'value1', $type), 'flag1');
        $ac->add(new Argument('flag2', 'value2', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('--flag1=value1 --flag2=value2')
        );
    }

    /**
     * @test
     */
    public function testMultipleNamedLongFlagWithWhitespace()
    {
        $ac = new ArgumentCollection();
        $type = new ArgumentType();
        $type->addFlag(ArgumentType::LONG_FLAG);
        $ac->add(new Argument('flag1', 'whitespace value 1', $type), 'flag1');
        $ac->add(new Argument('flag2', 'whitespace value 2', $type), 'flag2');
        $this->assertEquals(
            $ac,
            Parser::parse('--flag1="whitespace value 1" --flag2="whitespace value 2"')
        );
    }

    // mixed
    // => simpleParameter -shortFlag=value2 --longFlag=value3

    // special
    // => simpleParameter -shortFlag value2 --longFlag value3
}
