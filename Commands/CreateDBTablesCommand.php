<?php
/**
 * Copyright (C) Piwik PRO - All rights reserved.
 *
 * Using this code requires that you first get a license from Piwik PRO.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @link http://piwik.pro
 */
namespace Piwik\Plugins\ConsoleInstaller\Commands;

use Piwik\DbHelper;
use Piwik\Plugin\ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateDBTablesCommand
 * @package Piwik\Plugins\ConsoleInstaller\Commands
 */
class CreateDBTablesCommand extends ConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('console-installer:create-db-tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (DbHelper::isInstalled()) {
            $output->writeln('Database is already installed.');
            return;
        }

        DbHelper::createTables();
        DbHelper::createAnonymousUser();
    }
}
