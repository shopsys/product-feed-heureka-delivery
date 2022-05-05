<?php

declare(strict_types=1);

use Shopsys\CodingStandards\CsFixer\ForbiddenPrivateVisibilityFixer;
use Shopsys\CodingStandards\Sniffs\ForceLateStaticBindingForProtectedConstantsSniff;
use Shopsys\CodingStandards\Sniffs\ObjectIsCreatedByFactorySniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

/**
 * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $parameters = $containerConfigurator->parameters();

    $services->set(ForceLateStaticBindingForProtectedConstantsSniff::class);

    // this package is meant to be extensible using class inheritance, so we want to avoid private visibilities in the model namespace
    $services->set('forbidden_private_visibility_fixer.product_feed_heureka_delivery', ForbiddenPrivateVisibilityFixer::class)
        ->call('configure', [['analyzed_namespaces' => ['Shopsys\ProductFeed\HeurekaDeliveryBundle\Model']]]);

    $parameters->set(
        Option::SKIP,
        [
            ObjectIsCreatedByFactorySniff::class => [
                __DIR__ . '/tests/*',
            ],
        ]
    );

    $containerConfigurator->import(__DIR__ . '/vendor/shopsys/coding-standards/ecs.php', null, true);
};
