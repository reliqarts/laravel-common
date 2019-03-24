<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Unit;

use AspectMock\Test;
use ReliqArts\Tests\TestCase;

abstract class AspectMockedTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $parentNamespace;

    protected function setUp(): void
    {
        parent::setUp();

        $group = 'aspectMock';

        $this->setGroups(array_merge($this->getGroups(), [$group]));
    }

    protected function tearDown(): void
    {
        Test::clean();

        parent::tearDown();
    }
}
