<?php

namespace Skel\Commands;

use AndrewWoods\ChicagoStyle\Content;
use Skel\Document;
use Skel\Path;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnexpectedValueException;

#[AsCommand(
    name: 'write:article',
    description: 'Write an article for a blog or magazine',
    hidden: false
)]
class WriteCommand extends Command
{
    protected static $defaultName = 'write';

    protected $type = 'article';

    protected Path $path;


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
    public function __construct(Path $path)
    {
        parent::__construct();

        $this->path = $path;
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
        $option['type'] = $this->type;
        $option['title'] = $input->getOption('title') ?? $defaultTitle;

        $arg = [];
        $arg['filename'] = $input->getArgument('filename') ?? null;

        $document = new Document();

        $pathSource = $this->path->getTemplatePath()
            . '/' . $this->getSourceFileName($option['type']);

        $pathTo = $this->path->getUserPath()
            . '/' . $arg['filename'];

        $io = new SymfonyStyle($input, $output);

        $documentTitle = $option['title'];

        if ($option['title'] === $defaultTitle) {
            $helper = $this->getHelper('question');
            $question = new Question('What is your document title? ');

            $documentTitle = $helper->ask($input, $output, $question);
        }

        $content = new Content();
        $loader = new \Twig\Loader\FilesystemLoader($this->path->getTemplatePath());
        $twig = new \Twig\Environment(
            $loader, [
            'debug' => true,
            ]
        );

        $formatOpalDate = 'Y M d D';
        $formatOpalDateTime = 'Y M d D H:i';
        $dayInSeconds = 86_400;
        $dateYear = date('Y');
        $dateIsoDate = date('Y-m-d');
        $dateIsoDateTime = date('Y-m-dTH:i');
        $dateIsoTimeStamp = date('Y-m-dTH:i:sP');
        $dateToday = date($formatOpalDate);
        $dateDue = date($formatOpalDate, \time() + (10 * $dayInSeconds));
        $nowDate = date($formatOpalDate);
        $nowDateTime = date($formatOpalDateTime);

        $fh = fopen($pathTo, 'w');
        if (! $fh) {
            $output->writeln("Sorry, but the file '{$pathTo}' cannot be written");
            exit(1);
        }
        $templateFile = $this->getSourceFileName($option['type']);
        fwrite(
            $fh,
            $twig->render(
                $templateFile, [
                'title' => $content->titleCase($documentTitle),
                'language' => "en-US",
                'iso_date' => $dateIsoDate,
                'iso_datetime' => $dateIsoDateTime,
                'iso_timestamp' => $dateIsoTimeStamp,
                'date_created' =>  $dateToday,
                'date_due' =>  $dateDue,
                'now_date' =>  $nowDate,
                'now_datetime' =>  $nowDateTime,
                ]
            )
        );

        return Command::SUCCESS;
    }

    /*==========================================================================
     *   Protected Functions
     *==========================================================================
     */
    protected function configure()
    {
        $helpFilename = 'Specify the file name';

        $this
            ->setHelp('Write an article for a blog or magazine.')
            ->addOption(
                'title',
                null,
                InputOption::VALUE_REQUIRED,
                'The Title of the Blog/Magazine Article'
            )
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'The name of the file in the current directory'
            );
    }

    protected function getSourceFileName($doc)
    {
        return 'article.md';
    }

}
