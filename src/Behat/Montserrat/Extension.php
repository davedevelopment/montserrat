<?php

namespace Behat\Montserrat;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Behat\Behat\Extension\ExtensionInterface;

class Extension implements ExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    Extension configuration hash (from behat.yml)
     * @param ContainerBuilder $container ContainerBuilder instance
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('montserrat.xml');

        if (isset($config['working_dir'])) {
            $container->setParameter('behat.montserrat.working_dir', $config['working_dir']);
        }

        if (isset($config['prepend_paths'])) {
            $parameterBag = $container->getParameterBag();
            $paths = array();
            foreach ($config['prepend_paths'] as $p) {
                $paths[] = $parameterBag->resolveValue($p); 
            }
            $container->setParameter('behat.montserrat.prepend_path', implode(PATH_SEPARATOR, $paths));
        }
    }

    /**
     * Setups configuration for current extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder->
            children()->
                scalarNode('working_dir')->
                    defaultValue('tmp/montserrat')->
                end()->
                arrayNode('prepend_paths')->
                    prototype('scalar')->
                end()->
            end()->
        end();
    }

    /**
     * Returns compiler passes used by this extension.
     *
     * @return array
     */
    public function getCompilerPasses() 
    {  
        return array(); 
    }
}
