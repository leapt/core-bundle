<?php
namespace Snowcap\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;


class ResetCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('snowcap:core:reset')
            ->setDescription('Reset db with drop, create, ...')
            ->addOption('fixtures', 'f', InputOption::VALUE_NONE, 'Load fixtures after reset')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:schema:drop');
        $arguments = array(
            'command' => 'doctrine:schema:drop',
            '--force'    => true,
        );
        $inputs = new ArrayInput($arguments);
        $output->writeln('Running doctrine:schema:drop --force');
        $command->run($inputs, $output);

        $command = $this->getApplication()->find('doctrine:schema:create');
        $arguments = array(
            'command' => 'doctrine:schema:create',
        );
        $inputs = new ArrayInput($arguments);
        $output->writeln('Running doctrine:schema:create');
        $command->run($inputs, $output);

        if ($input->getOption('fixtures')) {
            $command = $this->getApplication()->find('doctrine:fixtures:load');
            $arguments = array(
                'command' => 'doctrine:fixtures:load',
            );
            $inputs = new ArrayInput($arguments);
            $output->writeln('Running doctrine:fixtures:load');
            $command->run($inputs, $output);
        }


    }
}