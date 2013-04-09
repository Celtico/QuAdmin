<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

return array(

    'controllers' => array(
        'invokables' => array(
            'qu_admin_controller' => 'QuAdmin\Controller\DashboardController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'quadmin' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/quadmin',
                    'defaults' => array(
                        'controller'    => 'qu_admin_controller',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),

            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),


);