<?php

namespace App\Command;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ChangeMaxResumeCommand extends Command
{
    public function __construct(protected EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('app:set-config')
            ->setDescription('Sets maximum resume/projects/open chats amount per user in CONFIG table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question('Set maximum resume amount [10]: ', 10);
        $resumeAmount = $helper->ask($input, $output, $question);

        $question = new Question('Set maximum projects amount [10]: ', 10);
        $projectAmount = $helper->ask($input, $output, $question);

        $question = new Question('Set maximum open chats amount [5]: ', 5);
        $chatsAmount = $helper->ask($input, $output, $question);

        $configsToAdd = ['max_resumes' => $resumeAmount, 'max_projects' => $projectAmount, 'max_open_chats' => $chatsAmount];
        $configRepository = $this->entityManager->getRepository(Config::class);
        try {
            foreach ($configsToAdd as $label => $value) {
                $config = $configRepository->findOneBy(['label' => $label]) ?? new Config();
                $config->setLabel($label); $config->setValue($value);
                $this->entityManager->persist($config);
            }
            $this->entityManager->flush();
            $output->writeln('Successfully set config values');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("Failed to set configs. Error: ".$e->getMessage());
            return self::FAILURE;
        }
    }
}