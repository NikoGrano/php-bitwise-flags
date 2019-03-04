<?php

/** @noinspection UnusedFunctionResultInspection */

declare(strict_types=1);

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under MIT license by Niko Granö.
 *
 * @copyright Niko Granö <niko9911@ironlions.fi> (https://granö.fi)
 *
 */

namespace Niko9911\Flags\Tests;

use Niko9911\Flags\Tests\Stubs\ExampleFlags;
use Niko9911\Flags\Tests\Stubs\ExampleFlagsWithNames;
use PHPUnit\Framework\TestCase;

class FlagsTest extends TestCase
{
    /**
     * @var ExampleFlags
     */
    protected $test;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var int
     */
    protected $mask = 0x0;

    /**
     * public method to set the mask.
     *
     * @param $mask
     */
    public function setMask($mask): void
    {
        $this->mask = $mask;
    }

    // set up test case
    public function setUp(): void
    {
        // base mask
        $this->mask = ExampleFlags::FOO | ExampleFlags::BAR;

        // callback function
        $model = $this;
        $this->callback = function (ExampleFlags $flags) use ($model): void {
            $model->setMask($flags->getMask());
        };

        // create new class with FOO and BAR set
        $this->test = (new ExampleFlags())
            ->setMask($this->mask)
            ->setOnModifyCallback($this->callback);
    }

    // test base mask set in setUp
    public function testBaseMask(): void
    {
        // verify if the correct flags are set
        $this->assertTrue($this->test->matchFlag(ExampleFlags::FOO));
        $this->assertTrue($this->test->matchFlag(ExampleFlags::BAR));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::QUX));

        // flag 1 and 2 should be resulting in 3
        $this->assertEquals(0x3, $this->test->getMask());
    }

    public function testMultipleFlags(): void
    {
        // test if all are set
        $this->assertTrue($this->test->matchFlag(ExampleFlags::FOO | ExampleFlags::BAR));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::BAR | ExampleFlags::BAZ));

        // test if any are set
        $this->assertTrue($this->test->matchAnyFlag(ExampleFlags::BAR | ExampleFlags::BAZ));
        $this->assertFalse($this->test->matchAnyFlag(ExampleFlags::BAZ | ExampleFlags::QUX));
    }

    public function testCallback(): void
    {
        // add BAZ which result in mask = 7
        $this->test->addFlag(ExampleFlags::BAZ);

        // the callback method should set the mask in this class to 7
        $this->assertEquals(0x7, $this->mask);
    }

    public function testAddFlag(): void
    {
        // add a flag
        $this->test->addFlag(ExampleFlags::BAZ);

        // add an existing flag
        $this->test->addFlag(ExampleFlags::FOO);

        // verify if the correct flags are set
        $this->assertTrue($this->test->matchFlag(ExampleFlags::FOO));
        $this->assertTrue($this->test->matchFlag(ExampleFlags::BAR));
        $this->assertTrue($this->test->matchFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::QUX));
    }

    public function testRemoveFlag(): void
    {
        // remove a flag
        $this->test->removeFlag(ExampleFlags::BAR);

        // remove an non-existing flag
        $this->test->removeFlag(ExampleFlags::BAZ);

        // verify if the correct flags are set
        $this->assertTrue($this->test->matchFlag(ExampleFlags::FOO));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::BAR));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::BAZ));
        $this->assertFalse($this->test->matchFlag(ExampleFlags::QUX));
    }

    public function testFlagNames(): void
    {
        $this->assertEquals(['Baz'], $this->test->getFlagNames(ExampleFlags::BAZ));
        $this->assertEquals(['Foo', 'Bar'], $this->test->getFlagNames());
    }

    public function testNamedFlagNames(): void
    {
        // same mask as exampleFlags
        $named = (new ExampleFlagsWithNames())
            ->setMask($this->test->getMask());

        $this->assertEquals(['My foo description', 'My bar description'], $named->getFlagNames());
    }
}
