<?php

declare(strict_types=1);

namespace Dvs\Core\ConfigBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ConfigAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $baseRouteName = 'admin_config';

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'config';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    );

    /**
     * @var array
     */
    protected $searchResultActions = ['show'];

    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
        $collection->remove('create');
        $collection->remove('show');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('defaultValue')
            ->add('currentValue')
            ->add('description')
            ->add('updatedAt')
            ->add('_action', null, array(
                'actions' => [
                    'edit' => [],
                ],
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, ['disabled' => true])
            ->add('defaultValue', null, ['disabled' => true])
            ->add('currentValue')
            ->add('description')
        ;
    }
}
