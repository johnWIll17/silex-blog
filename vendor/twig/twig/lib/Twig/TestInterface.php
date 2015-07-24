<?php

/*
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a template Test.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_TestInterface
{
    /**
     * Compiles a Test.
     *
     * @return string The PHP code for the Test
     */
    public function compile();
}
