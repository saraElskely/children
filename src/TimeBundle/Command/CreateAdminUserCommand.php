<?php

namespace TimeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TimeBundle\Entity\User;
use Symfony\Component\Console\Question\Question;
use TimeBundle\constant\Roles;

class CreateAdminUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('createAdminUser')
            ->setDescription('to create user admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        
//        $question = new Question('Please enter username :(admin)','admin');
//        $username = $helper->ask($input, $output, $question);
        
        $question = new Question('Please enter password more than 8 character :(admin/admin)','admin/admin');
        $password = $helper->ask($input, $output, $question);
        
        $admin = new User();
        $admin->setUsername('admin')
                ->setRole(Roles::ROLE_ADMIN)
                ->setAge(30)
                ->setFname('admin')
                ->setLname('admin');
        
        $passwordEncoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $passwordEncoder->encodePassword($admin, $password);
        
        $admin->setPassword($encodedPassword);
        
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();
        

        $output->writeln('you create admin successfuly with username :'.$username.' & password :'.$password);
    }

}
