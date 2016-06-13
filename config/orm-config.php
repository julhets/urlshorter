<?php
/**
 * Created by PhpStorm.
 * User: julhets
 * Date: 6/11/16
 * Time: 5:08 PM
 */

return ['orm.proxies_dir' => __DIR__ . '/../cache/doctrine/proxies',
    'orm.default_cache' => 'array',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'path' => __DIR__ . '/../src/Urlshorter/ValueObject',
                'namespace' => 'Urlshorter\\ValueObject',
            ),
        ))];