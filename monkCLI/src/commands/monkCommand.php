<?php namespace monk\commands;

use OpenCloud\Rackspace;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class monkCommand extends Command
{
    protected function message($string, OutputInterface $output, $addLineOnTop = false)
    {
        if ($addLineOnTop) {
            $output->writeln('');
        }
        $output->writeln($string);
        $output->writeln('');
    }

    protected function startRackspaceConnection()
    {
        return new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => getenv('RACKSPACE_USERNAME'),
            'apiKey'   => getenv('RACKSPACE_API_KEY')
        ));
    }

    protected function progressOne(ProgressBar $progress, OutputInterface $output, $addLineBreak = false)
    {
        $progress->advance();
        if ($addLineBreak) {
            $output->writeln('');
        }
    }
}
