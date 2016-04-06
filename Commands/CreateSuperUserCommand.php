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
use Piwik\Plugins\UsersManager\API as UserManagerAPI;

/**
 * Class CreateSuperUserCommand
 * @package Piwik\Plugins\ConsoleInstaller\Commands
 */
class CreateSuperUserCommand extends ConsoleCommand
{
    use ExceptionToOutputLogHandler;

    const USERNAME_ARG = 'username';
    const PASSWORD_ARG = 'password';
    const EMAIL_ARG = 'email';

    const ALIAS_OPTION = 'alias';
    const HASHED_PASSWORD = 'hashed-password';

    protected function configure()
    {
        $this
            ->setName('console-installer:create-super-user')
            ->setDescription('Allows to create super user')
            ->addArgument(self::USERNAME_ARG, InputArgument::REQUIRED)
            ->addArgument(self::PASSWORD_ARG, InputArgument::REQUIRED)
            ->addArgument(self::EMAIL_ARG, InputArgument::REQUIRED)
            ->addOption(self::ALIAS_OPTION, null, InputOption::VALUE_OPTIONAL)
            ->addOption(
                self::HASHED_PASSWORD,
                null,
                InputOption::VALUE_NONE,
                'Use this flag if provided password was encoded with MD5'
            );

        $this->extendDefinitionOfCommand($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument(self::USERNAME_ARG);
        $password = $input->getArgument(self::PASSWORD_ARG);
        $email = $input->getArgument(self::EMAIL_ARG);

        $alias = $input->getOption(self::ALIAS_OPTION);
        $isPasswordHashed = $input->getOption(self::HASHED_PASSWORD) ? true : false;

        $this->handle($input, $output, function () use ($username, $password, $email, $alias, $isPasswordHashed) {
            Access::doAsSuperUser(function () use ($username, $password, $email, $alias, $isPasswordHashed) {
                UserManagerAPI::getInstance()->addUser(
                    $username,
                    $password,
                    $email,
                    $alias ?: false,
                    $isPasswordHashed
                );

                UserManagerAPI::getInstance()->setSuperUserAccess($username, true);
            });
        });
    }
}
 