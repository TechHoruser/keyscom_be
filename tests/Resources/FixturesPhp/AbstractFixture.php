<?php

namespace App\Tests\Resources\FixturesPhp;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Tests\Resources\Config\FixtureValuesInterface;
use App\Tests\Resources\Factory\FakerFactoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

abstract class AbstractFixture extends Fixture
{
    public function __construct(
        protected FixtureValuesInterface $fixtureValues,
        protected SecurityHelperInterface $securityHelper,
        protected FakerFactoryInterface $fakerFactory,
    ) {}
}
