<?php

namespace TimeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use TimeBundle\Entity\DailySchedule;
//use Doctrine\ORM\EntityManagerInterface;
use TimeBundle\constant\Roles;


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
            
        }
        
//        $em = $this->getContainer()->get('doctrine')->getManager();
//        
//        $users = $em->getRepository('TimeBundle:User')->findByRole(Roles::ROLE_CHILD);
//        $tasks = $em->getRepository('TimeBundle:Task')->findByCreator(Roles::ROLE_MOTHER);
////        dump($tasks);
////        die();
//        foreach ($users as $user){
//            foreach ($tasks as $task) {
//                $dailySchedule = new  DailySchedule();
//                $dailySchedule->setDate(new \DateTime());
//                $dailySchedule->setUserInSchedule($user);
//                $dailySchedule->setTaskInSchedule($task);
//
//                $em->persist($dailySchedule);
//                $em->flush();
//        
//            }
//        }
        
        $dailyScheduleService = $this->getContainer()->get('TimeBundle\Service\DailyScheduleService');
        $dailyScheduleService->createAdminDailySchedule();
        
        $output->writeln('Daily Schedule admin tasks created for all users.');
        
    }

}
