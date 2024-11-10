<?php

namespace Skel\Commands;

use Skel\Git;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'git',
    description: 'Creates a git file',
    hidden: false,
    aliases: ['app:add-user']
)]
class CreateGitCommand extends Command
{
    protected static $defaultName = 'git';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Git files for your project';

    protected $skeletonPath = '';

    protected $userProjectPath = '';

    /**
     * Create a new command instance.
     *
     * @param string $path the directory of the skeleton project
     *
     * @return void
     */
    public function __construct(string $path, string $userProjectPath)
    {
        parent::__construct();

        $this->skeletonPath = $path;
        $this->userProjectPath = $userProjectPath;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = [];
        $arg['names'] = $input->getArgument('names');

        $document = new Git();
        foreach ([$arg['names']] as $doc) {

            $pathSource = $this->skeletonPath . '/'
                . $document->getSourceFileName($doc);
            $pathTo = $this->userProjectPath . '/'
                . $document->getDestinationFileName($doc);

            copy($pathSource, $pathTo);
        }

    }

    protected function configure()
    {
        $helpNames = 'specify the file name. choose from: ' .
                     'ignore. ';

        $this
            ->setDescription('Creates git files for your project.')
            ->setHelp('Generate boilerplate git files for your project')
            ->addArgument(
                'names',
                InputArgument::REQUIRED,
                $helpNames
            );
    }

    protected function info($message)
    {
        return '<info>' . $message . '</info>';
    }

    protected function link($url, $linkText = '')
    {
        if (empty($linkText)) {
            $linkText = $url;
        }
        return '<href=' . $url .'>' . $linkText . '</>';
    }

}
