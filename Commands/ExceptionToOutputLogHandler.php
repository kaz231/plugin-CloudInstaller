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
use Piwik\Plugin\ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Trait ExceptionToOutputLogHandler
 * @package Piwik\Plugins\ConsoleInstaller\Commands
 */
trait ExceptionToOutputLogHandler
{
    private $exceptionToLogOption = 'exception-to-log';

    /**
     * @param ConsoleCommand $command
     */
    protected function extendDefinitionOfCommand(ConsoleCommand $command)
    {
        $command->addOption(
            $this->exceptionToLogOption,
            null,
            InputOption::VALUE_NONE,
            'Use this flag to convert threw exception into log to output (avoid of return code 1)'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param callable $commandExecution
     * @throws \Exception
     */
    protected function handle(InputInterface $input, OutputInterface $output, \Closure $commandExecution)
    {
        $exceptionToLog = $input->getOption($this->exceptionToLogOption) ? true : false;

        try {
            $commandExecution();
        } catch (\Exception $e) {
            if ($exceptionToLog) {
                $output->writeln($e->getMessage());
            } else {
                throw $e;
            }
        }
    }
}
