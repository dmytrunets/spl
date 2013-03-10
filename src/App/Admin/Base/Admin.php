<?php

namespace App\Admin\Base;

use Sonata\AdminBundle\Admin\Admin as DefaultAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

abstract class Admin extends DefaultAdmin
{
    /**
     * Returns the baseRoutePattern used to generate the routing information
     *
     * @throws \RuntimeException
     *
     * @return string the baseRoutePattern used to generate the routing information
     */
    public function getBaseRoutePattern()
    {
        if (!$this->baseRoutePattern) {
            preg_match('@([A-Za-z0-9]*)\\\(Entity|Document|Model)\\\(.*)@', $this->getClass(), $matches);

            if (!$matches) {
                throw new \RuntimeException(sprintf('Please define a default `baseRoutePattern` value for the admin class `%s`', get_class($this)));
            }

            if ($this->isChild()) { // the admin class is a child, prefix it with the parent route name
                $this->baseRoutePattern = sprintf('%s/{id}/%s',
                    $this->getParent()->getBaseRoutePattern(),
                    $this->urlize($matches[4], '-')
                );
            } else {

                $this->baseRoutePattern = sprintf('/%s/%s',
                    $this->urlize($matches[1], '-'),
                    $this->urlize($matches[3], '-')
                );
            }
        }

        return $this->baseRoutePattern;
    }

    /**
     * Returns the baseRouteName used to generate the routing information
     *
     * @throws \RuntimeException
     *
     * @return string the baseRouteName used to generate the routing information
     */
    public function getBaseRouteName()
    {
        if (!$this->baseRouteName) {
            preg_match('@([A-Za-z0-9]*)\\\(Entity|Document|Model)\\\(.*)@', $this->getClass(), $matches);

            if (!$matches) {
                throw new \RuntimeException(sprintf('Please define a default `baseRouteName` value for the admin class `%s`', get_class($this)));
            }

            if ($this->isChild()) { // the admin class is a child, prefix it with the parent route name
                $this->baseRouteName = sprintf('%s_%s',
                    $this->getParent()->getBaseRouteName(),
                    $this->urlize($matches[4])
                );
            } else {

                $this->baseRouteName = sprintf('admin_%s_%s',
                    $this->urlize($matches[1]),
                    $this->urlize($matches[3])
                );
            }
        }

        return $this->baseRouteName;
    }
}