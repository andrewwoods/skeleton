<?php

namespace Skel\Commands;

use Skel\Document;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WriteCommand extends Command
{
    protected static $defaultName = 'write';

    protected static $defaultType = 'article';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Write a document for publishing based on a template';

    protected $skeletonPath = '';

    protected $userProjectPath = '';

    protected $templatePath = '';


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
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $option = [];
        $option['type'] = $input->getOption('type') ?? 'article';

        $arg = [];
        $arg['filename'] = $input->getArgument('filename') ?? null;

        $document = new Document();

        $pathSource = $this->getTemplatePath()
            . '/' . $this->getSourceFileName($option['type']);
        
        $pathTo = $this->userProjectPath 
            . '/' . $arg['filename'];

        $io = new SymfonyStyle($input, $output);
        $io->title('Source Path');
        $output->writeln($pathSource);

        $io->title('Destination Path');
        $output->writeln($pathTo);

        copy($pathSource, $pathTo);
    }

    public function setTemplatePath($path){
        $this->templatePath = $path;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    protected function configure()
    {
        $helpFilename = 'Specify the file name';
        $helpType = 'Which type of file are you creating:' . "\n" .
                     'article, daily, or weekly?';

        $this
            ->setDescription('Create a file for writing.')
            ->setHelp('Prepare a file to write from a template.')
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED,
                $helpType,
                'article'
            )
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                $helpFilename
            );
    }
    
    protected function getSourceFileName($doc)
    {
        $allowedTypes = ['article'];

        switch ($doc){
            case 'article':
                return 'article.md';
                break;

            default:
                $message = 'You have used an unknown file type (' . $doc . '). '
                   . 'Please use one of the following: '
                   . implode(', ', $allowedTypes)
                ;
                throw new unexpectedvalueexception($message);
        }
    }

}
