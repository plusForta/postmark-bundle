<?php

namespace PlusForta\PostmarkBundle;

use PlusForta\PostmarkBundle\DependencyInjection\Compiler\PostmarkBundleCompilerPass;
use PlusForta\PostmarkBundle\DependencyInjection\PlusFortaPostmarkExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PlusFortaPostmarkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PostmarkBundleCompilerPass());
    }

    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new PlusFortaPostmarkExtension();
        }

        return $this->extension;
    }

}