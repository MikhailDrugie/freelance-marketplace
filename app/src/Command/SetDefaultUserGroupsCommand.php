<?php

namespace App\Command;

use App\Entity\UserGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetDefaultUserGroupsCommand extends Command
{
    public function __construct(protected EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('app:set-default-user-groups')
            ->setDescription('Checks for USER and ADMIN user groups and adds them if they are not present');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userGroupRepository = $this->entityManager->getRepository(UserGroup::class);
        $defaultLevels = ['level' => [UserGroup::LEVEL_USER, UserGroup::LEVEL_ADMIN]];
        $userGroups = $userGroupRepository->findBy($defaultLevels);
        $groupsToAdd = [UserGroup::LEVEL_USER => 'user', UserGroup::LEVEL_ADMIN => 'admin'];
        foreach ($userGroups as $userGroup) unset($groupsToAdd[$userGroup->getLevel()]);
        try {
            foreach ($groupsToAdd as $level => $label) {
                $userGroup = new UserGroup();
                $userGroup->setLevel($level);
                $userGroup->setLabel($label);
                $userGroup->setDescription('Created with custom command');
                $this->entityManager->persist($userGroup);
            }
            $this->entityManager->flush();
            $output->writeln('Default user groups are successfully set up!');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("Failed to add user groups. Error: ".$e->getMessage());
            return self::FAILURE;
        }
    }
}