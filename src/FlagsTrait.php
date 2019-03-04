<?php

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

namespace Niko9911\Flags;

use ReflectionClass;

trait FlagsTrait
{
    /**
     * @var int
     */
    protected $mask;
    /**
     * @var callable|null
     */
    protected $onModifyCallback;

    /** @noinspection PhpDocMissingThrowsInspection */

    /**
     * Return matched names as array.
     *
     * @param int|null $mask
     *
     * @return array
     */
    public function getFlagNames(?int $mask = null): array
    {
        $mask = $mask ?: $this->mask;
        $calledClass = static::class;
        /** @noinspection PhpUnhandledExceptionInspection */
        $constants = (new ReflectionClass($calledClass))->getConstants();

        $names = [];
        if ($constants) {
            foreach ($constants as $constant => $flag) {
                if ($mask & $flag) {
                    $names[] = \method_exists($calledClass, 'registerFlags') && !empty($calledClass::registerFlags()[$flag])
                        ? $calledClass::registerFlags()[$flag]
                        : \implode('', \array_map('ucfirst', \explode('_', \mb_strtolower($constant))));
                }
            }
        }

        return $names;
    }

    /**
     * @param callable $onModify
     *
     * @return self
     */
    public function setOnModifyCallback(callable $onModify): self
    {
        $this->onModifyCallback = $onModify;

        return  $this;
    }

    /**
     * @internal this is mean to be called only on modify and is internal function
     */
    protected function onModify(): void
    {
        null === $this->onModifyCallback ?: \call_user_func($this->onModifyCallback, $this);
    }

    /**
     * @param int $mask
     *
     * @return self
     */
    public function setMask(int $mask): self
    {
        $before = $this->mask;
        $this->mask = $mask;
        $before === $this->mask ?: $this->onModify();

        return $this;
    }

    /**
     * @return int
     */
    public function getMask(): int
    {
        return $this->mask;
    }

    /**
     * @param int $flag
     *
     * @return self
     */
    public function addFlag(int $flag): self
    {
        $before = $this->mask;
        $this->mask |= $flag;
        $before === $this->mask ?: $this->onModify();

        return $this;
    }

    /**
     * @param int $flag
     *
     * @return self
     */
    public function removeFlag(int $flag): self
    {
        $before = $this->mask;
        $this->mask &= ~$flag;
        $before === $this->mask ?: $this->onModify();

        return $this;
    }

    /**
     * @param int $flag
     *
     * @return bool
     */
    public function matchFlag(int $flag): bool
    {
        return ($this->mask & $flag) === $flag;
    }

    /**
     * @param int $mask
     *
     * @return bool
     */
    public function matchAnyFlag(int $mask): bool
    {
        return ($this->mask & $mask) > 0;
    }
}
