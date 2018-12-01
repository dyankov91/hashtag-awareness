<?php

namespace App\Contracts;

/**
 * Interface CountableModelInterface
 */
interface CountableModelInterface
{
    /**
     * @return string
     */
    public function getTable(): string;
}
