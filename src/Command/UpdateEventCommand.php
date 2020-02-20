<?php

namespace App\Command;

use App\Service\UpdateEventStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateEventCommand extends Command
{
    protected static $defaultName = 'app:updateEvent';

    protected function configure()
    {
        $this
            ->setDescription('This command permit to update the database about status of events')
            ->setHelp('Démerde toi tout seul !');
    }

    private $em;
    private $ues;

    public function __construct(EntityManagerInterface $entityManager, UpdateEventStatus $ues)
    {
        parent::__construct();
        $this->em = $entityManager;
        $this->ues = $ues;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Update Status',
            '=============',
            '',
        ]);


        $this->ues->updateEventStatus2();

        $io->success('Your event\'s status is about is up to date in your database, good job');

        return 0;
    }
}
