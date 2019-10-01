<?php

namespace PlusForta\PostmarkBundle;

use PlusForta\PostmarkBundle\DependencyInjection\PlusFortaPostmarkExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PlusFortaPostmarkBundle extends Bundle
{

    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new PlusFortaPostmarkExtension();
        }
        return $this->extension;
    }

}