<?php

return array_merge_recursive(
    require __DIR__ . '/main.php',
    array(
        'components' => array(
            'viewRenderer' => array(
                'class' => 'application.vendor.yiiext.twig-renderer.ETwigViewRenderer',
                'twigPathAlias' => 'application.vendor.twig.twig.lib.Twig',
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'rules' => array(
                    'developers' => 'developer/index',
                    'feedback' => 'site/feedback',
                    '' => 'site/index',
                )
            )
        ),
    )
);
