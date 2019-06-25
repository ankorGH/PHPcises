<?php 

namespace Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class QuizPathCommand extends Command 
{
    private $defaultFilePath = __DIR__ . "/../../problems.csv";

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setName("start")
        ->setDescription("Start quiz")
        ->addArgument("quiz_name")
        ->addOption("filepath");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $correctAnswers = 0;
        // $path = $input->getArgument("quiz_name");
        $path = $input->getOption("filepath");
        $path  = empty($path) ? $this->defaultFilePath : $path;
        $csvQuestions = array_map('str_getcsv', file('problems.csv'));
        $numberOfQuestions = count($csvQuestions);

        $output->writeln("<info>Answer the following questions?</info>");
        $output->writeln("");
        $helper = $this->getHelper("question");
        foreach($csvQuestions as $question){
            $answer = $question[1];
            $question = $question[0] . " ";
            $question = new Question($question);
            $question->setNormalizer(function($value){
                return $value ? trim($value) : "";
            });
            $userAnswer = $helper->ask($input,$output,$question);
            if($answer === $userAnswer) $correctAnswers++;
        }
        $output->writeln("");
        $output->writeln("<info>You got $correctAnswers out of $numberOfQuestions</info>");
    }
}
?>