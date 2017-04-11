<?php

declare(strict_types=1);

chdir(dirname(__DIR__) . '/app');

require __DIR__ . '/../app/vendor/autoload.php';

$cli = new \Symfony\Component\Console\Application(
    'Doctrine Command Line Interface',
    \Doctrine\DBAL\Migrations\MigrationsVersion::VERSION()
);

$container = require __DIR__ . '/../app/config/container.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet();
$helperSet->set(new \Symfony\Component\Console\Helper\QuestionHelper(), 'dialog');

$connection = $container->get(\Doctrine\DBAL\Connection::class);
$helperSet->set(
    new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($connection),
    'connection'
);

$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$cli->addCommands(array(
    // Migrations Commands
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
));

$cli->run();
