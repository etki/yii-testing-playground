<?php

return array_merge_recursive(
    require __DIR__ . '/main.php',
    array(
        'commandMap' => array(
            'migrate' => array(
                'class' => 'system.cli.commands.MigrateCommand',
                'migrationPath' => 'application.migrations',
                'migrationTable' => 'sys_migrations',
                'connectionID' => 'db',
            ),
        ),
    )
);