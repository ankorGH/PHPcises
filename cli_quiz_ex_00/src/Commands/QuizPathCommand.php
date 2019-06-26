<?php 

namespace Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputOption;

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
        ->addOption(
            "filepath",
            "f",
            InputOption::VALUE_REQUIRED,
            "Enter quiz filepath (csv)",
            null
        )
        ->addOption(
            "limit",
            "l",
            InputOption::VALUE_REQUIRED,
            "Enter time limit in seconds",
            null
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $correctAnswers = 0;
        $path  = $input->getOption("filepath");
        $limit = $input->getOption("limit");
        
        $useTimeLimit = !empty($limit) ? true : false;
        if($useTimeLimit) $limit = (int) $limit;
        $startTime = \microtime(TRUE);

        $csvQuestions = $this->getQuestions($path);
        $numberOfQuestions = count($csvQuestions);

        $output->writeln("<info>Answer the following questions?</info>");
        $helper = $this->getHelper("question");
        foreach($csvQuestions as $question){
            
            if($useTimeLimit) {
                $currentTime = \microtime(True);
                $timeElapsed = $currentTime - $startTime;
                if($timeElapsed >= $limit) break;
            }

            $answer = $question[1];
            $question = $question[0] . " ";
            $question = new Question($question);
            $question->setNormalizer(function($value){
                return $value ? trim($value) : "";
            });
            $userAnswer = $helper->ask($input,$output,$question);
            if($answer === $userAnswer) $correctAnswers++;
        }
        $currentTime = \microtime(True);
        $timeElapsed = round($currentTime - $startTime);
        
        $output->writeln("");
        $output->writeln("<info>You got $correctAnswers out of $numberOfQuestions</info>");
        $output->writeln("<info>Time used is $timeElapsed seconds</info>");
    }

    /*
    * Parses the csv file for the problems given the filepath 
    *
    * @param string filePath    Takes location of questions
    *
    * @return {mixed} questions
    */
    protected function getQuestions($filePath): array
    {
        $path  = empty($filePath) ? $this->defaultFilePath : $filePath;
        var_dump($path);
        $csvQuestions = array_map('str_getcsv', file($path));
        return $csvQuestions;
    }
}
?>