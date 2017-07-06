<?php

namespace TimeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TimeBundle\Entity\DailySchedule;
use Doctrine\ORM\EntityManagerInterface;

class CreateDailyTasksCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('createDailyTasks')
            ->setDescription('create daily tasks for user')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }
        
        $task = new  DailySchedule();
        $task->setDate(new \DateTime());
        
        $task->setTaskInSchedule($taskInSchedule);
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $output->writeln('Command result.'.$em);
        $output->write('hello...');
    }

}
