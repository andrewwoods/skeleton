<?php

namespace Skel\Commands;

use Skel\Document;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'write',
    description: 'Write a document for publishing from a template',
    hidden: false
)]
class WriteCommand extends Command
{
    protected static $defaultName = 'write';

    protected static $defaultType = 'article';

    protected $skeletonPath = '';

    protected $userProjectPath = '';

    protected $templatePath = '';


    /*==========================================================================
     *   Magic Functions
     *==========================================================================
     */

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

    /*==========================================================================
     *   Public Functions
     *==========================================================================
     */

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $defaultTitle = '';

        $option = [];
        $option['type'] = $input->getOption('type') ?? 'article';
        $option['title'] = $input->getOption('title') ?? $defaultTitle;

        $arg = [];
        $arg['filename'] = $input->getArgument('filename') ?? null;

        $document = new Document();

        $pathSource = $this->getTemplatePath()
            . '/' . $this->getSourceFileName($option['type']);
        
        $pathTo = $this->userProjectPath 
            . '/' . $arg['filename'];

        $io = new SymfonyStyle($input, $output);

        $documentTitle = $option['title'];
        if ($option['title'] === $defaultTitle) { 
            $helper = $this->getHelper('question');
            $question = new Question('What is your document title? ');

            $documentTitle = $helper->ask($input, $output, $question);
        }

        $loader = new \Twig\Loader\FilesystemLoader($this->getTemplatePath());
        $twig = new \Twig\Environment($loader, [
            'debug' => true,
        ]);

        $formatOpalDate = 'Y M d D';
        $dayInSeconds = 86_400;
        $dateToday = date($formatOpalDate);
        $dateDue = date($formatOpalDate, \time() + (7 * $dayInSeconds));

        echo $twig->render('article.md', [
            'title' => $documentTitle,
            'language' => "en-US",
            'date_created' =>  $dateToday,
            'date_due' =>  $dateDue,
        ]);

        return Command::SUCCESS;

    }

    public function setTemplatePath($path){
        $this->templatePath = $path;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    /*==========================================================================
     *   Protected Functions
     *==========================================================================
     */
    protected function configure()
    {
        $helpFilename = 'Specify the file name';
        $helpType = 'Which type of file are you creating:' . "\n" .
                     'article, daily, or weekly?';
        $helpTitle = 'The title of your Document' . "\n"; 

        $this
            ->setHelp('Write a document for publishing from a template.')
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED,
                $helpType,
                'article'
            )
            ->addOption(
                'title',
                null,
                InputOption::VALUE_REQUIRED,
                $helpType
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
