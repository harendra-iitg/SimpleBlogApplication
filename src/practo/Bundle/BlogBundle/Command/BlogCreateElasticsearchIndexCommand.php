<?php

namespace practo\Bundle\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlogCreateElasticsearchIndexCommand extends ContainerAwareCommand
{

	/**
	 * Intialize Services
	 *
	 * @param InputInterface  $input  - stdin
	 * @param OutputInterface $output - stdout
	 */
	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		$this->doctrine = $this->getContainer()->get('doctrine');
		$this->logger = $this->getContainer()->get('logger');
		$this->blogManager = $this->getContainer()->get('practo_blog.blog_manager');
		$this->esManager = $this->getContainer()->get('practo_blog.elastic_search_manager');
		$this->em = $this->getContainer()->get('doctrine')->getManager();
	}

	/**
	 * Configure the task.
	 */
	protected function configure()
	{
		$this->setName('blog:createElasticsearchIndex')
			->setDescription('This command create the indexes for elasticsearch')
			->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
			->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if($this->esManager->indexExist()) {
			$this->esManager->deleteIndex();
		}
		$this->esManager->createIndex();
		$argument = $input->getArgument('argument');
		$blogs = $this->blogManager->loadAll();
		foreach($blogs as $blog) {
			$this->esManager->indexingSingleDocument($blog);
		}

		/*
		   if ($input->getOption('option')) {

		   }*/

		$output->writeln("\n Command Executed Sucessfully \n");
	}

}
