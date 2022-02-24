<?php

namespace App\Tests\Integration\Resources\FixturesPhp;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

abstract class AbstractFixture extends Fixture
{
    protected FixtureValuesInterface $fixtureValues;
    protected SecurityHelperInterface $securityHelper;
    protected FakerFactoryInterface $fakerFactory;

    /**
     * AbstractFixture constructor.
     * @param FixtureValuesInterface $fixtureValues
     * @param SecurityHelperInterface $securityHelper
     * @param FakerFactoryInterface $fakerFactory
     */
    public function __construct(
        FixtureValuesInterface $fixtureValues,
        SecurityHelperInterface $securityHelper,
        FakerFactoryInterface $fakerFactory
    ) {
        $this->fixtureValues = $fixtureValues;
        $this->securityHelper = $securityHelper;
        $this->fakerFactory = $fakerFactory;
    }
}
