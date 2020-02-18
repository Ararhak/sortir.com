<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SayHelloCommand extends Command
{
    protected static $defaultName = 'app:sayHello';

    protected function configure()
    {
        $this
            ->setDescription('Say Hello')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Hello !');
        $name = $io->ask('What is your name ?');
        $io->writeln('Hello '. $name . '!');

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;























    }
}
