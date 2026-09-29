<?php

namespace Skel\Commands;

use AndrewWoods\ChicagoStyle\Content;
use Skel\DateTrait;
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
    name: 'write:talk',
    description: 'Write a talk abstract and outline',
    hidden: false
)]
class WriteTalkCommand extends Command
{
    use DateTrait;

    protected static $defaultName = 'write:talk';

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
        $defaultSubtitle = '';
        $defaultDateDue = '';

        $option = [];
        $option['type'] = 'talk';
        $option['title'] = $input->getOption('title') ?? $defaultTitle;
        $option['subtitle'] = $input->getOption('subtitle') ?? $defaultSubtitle;
        $option['date_due'] = $input->getOption('date_due') ?? $defaultDateDue;

        $arg = [];
        $arg['filename'] = $input->getArgument('filename') ?? null;


        $pathSource = $this->path->getTemplatePath()
        . '/' . $this->getSourceFileName($option['type']);

        $pathTo = $this->path->getUserPath()
        . '/' . $arg['filename'];

        $io = new SymfonyStyle($input, $output);

        $documentTitle = $option['title'];
        $subtitle = $option['subtitle'];
        $dateDue = $option['date_due'];
        if ($option['title'] === $defaultTitle) {
            $helper = $this->getHelper('question');
            $question = new Question('What is the title of your presentation? ');

            $documentTitle = $helper->ask($input, $output, $question);
        }

        if ($option['subtitle']) {
            $helper = $this->getHelper('question');
            $question = new Question('What is the subtitle? ');

            $subtitle = $helper->ask($input, $output, $question);
        }


        if ($option['date_due'] === $defaultDateDue) {
            $helper = $this->getHelper('question');
            $question = new Question('What is the due date? ');

            $dateDue = $helper->ask($input, $output, $question);
        }

        $content = new Content();
        $loader = new \Twig\Loader\FilesystemLoader($this->path->getTemplatePath());
        $twig = new \Twig\Environment(
            $loader, [
            'debug' => true,
            ]
        );


        $dates = $this->getDates([ 'date_due' => $dateDue, ]);

        $fh = fopen($pathTo, 'w');
        if (! $fh) {
            echo "Sorry, but the file '{$pathTo}' cannot be written";
            exit(1);
        }
        $templateFile = $this->getSourceFileName($option['type']);

        $data = [
        'title' => $content->titleCase($documentTitle),
        'subtitle' => $subtitle ?? '',
        'language' => "en-US",
        ];
        $data = array_merge($data, $dates);

        fwrite(
            $fh,
            $twig->render(
                $templateFile,
                $data
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
        $this
            ->setHelp('Write a talk abstract and outline.')
            ->addOption(
                'title',
                null,
                InputOption::VALUE_REQUIRED,
                'The title or subject of the presentation'
            )
            ->addOption(
                'subtitle',
                null,
                InputOption::VALUE_NONE,
                'Add a subtitle'
            )
            ->addOption(
                'date_due',
                null,
                InputOption::VALUE_REQUIRED,
                'The date when you need to submit your talk in YYYY-MM-DD format.'
            )
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'The name of the file to write the output'
            );
    }

    protected function getSourceFileName($doc)
    {
        $allowedTypes = ['talk'];

        switch ($doc){

        case 'talk':
            return 'talk.md';
                break;

        default:
            $message = 'You have used an unknown file type(' . $doc . '). '
               . 'Please use one of the following: '
               . implode(', ', $allowedTypes);
            throw new UnexpectedValueException($message);
        }
    }

}
