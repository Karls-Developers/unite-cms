<?php
/**
 * Created by PhpStorm.
 * User: franzwilding
 * Date: 16.06.17
 * Time: 11:28
 */

namespace UniteCMS\CoreBundle\SchemaType;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SchemaTypeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('unite.cms.graphql.schema_type_manager')) {
            return;
        }

        $definition = $container->findDefinition('unite.cms.graphql.schema_type_manager');
        $taggedServices = $container->findTaggedServiceIds('unite_cms.graphql.schema_type');
        $taggedFactoryServices = $container->findTaggedServiceIds('unite_cms.graphql.schema_type_factory');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('registerSchemaType', array(new Reference($id)));
        }

        foreach ($taggedFactoryServices as $id => $tags) {
            $definition->addMethodCall('registerSchemaTypeFactory', array(new Reference($id)));
        }
    }
}
