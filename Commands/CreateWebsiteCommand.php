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

use Piwik\Access;
use Piwik\Plugin\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Piwik\Plugins\SitesManager\API as SitesManagerAPI;

/**
 * Class CreateWebsiteCommand
 * @package Piwik\Plugins\ConsoleInstaller\Commands
 */
class CreateWebsiteCommand extends ConsoleCommand
{
    use ExceptionToOutputLogHandler;

    const NAME_ARG = 'name';
    const URL_ARG = 'url';

    const ENABLE_ECOMMERCE_OPTION = 'enable-ecommerce';
    const TIMEZONE_OPTION = 'timezone';
    const CURRENCY_OPTION = 'currency';
    const START_DATE_OPTION = 'start-date';

    protected function configure()
    {
        $this
            ->setName('console-installer:create-website')
            ->addArgument(self::NAME_ARG, InputArgument::REQUIRED)
            ->addArgument(self::URL_ARG, InputArgument::REQUIRED)
            ->addOption(self::ENABLE_ECOMMERCE_OPTION, null, InputOption::VALUE_NONE)
            ->addOption(self::TIMEZONE_OPTION, null, InputOption::VALUE_OPTIONAL)
            ->addOption(self::CURRENCY_OPTION, null, InputOption::VALUE_OPTIONAL)
            ->addOption(self::START_DATE_OPTION, null, InputOption::VALUE_OPTIONAL);

        $this->extendDefinitionOfCommand($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument(self::NAME_ARG);
        $url = $input->getArgument(self::URL_ARG);

        $eCommerce = $input->getOption(self::ENABLE_ECOMMERCE_OPTION) ? true : false;
        $timezone = $input->getOption(self::TIMEZONE_OPTION);
        $currency = $input->getOption(self::CURRENCY_OPTION);
        $startDate = $input->getOption(self::START_DATE_OPTION);

        $this->handle($input, $output, function () use ($name, $url, $eCommerce, $timezone, $currency, $startDate) {
            Access::doAsSuperUser(function () use ($name, $url, $eCommerce, $timezone, $currency, $startDate) {
                SitesManagerAPI::getInstance()->addSite(
                    $name,
                    $url,
                    $eCommerce,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $timezone,
                    $currency,
                    null,
                    $startDate,
                    null,
                    null,
                    null,
                    null,
                    null
                );
            });
        });
    }
}
