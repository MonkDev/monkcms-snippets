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
        return new Rackspace(
            Rackspace::US_IDENTITY_ENDPOINT,
            [
                'username' => getenv('RACKSPACE_USERNAME'),
                'apiKey'   => getenv('RACKSPACE_API_KEY')
            ],
            [
                Rackspace::SSL_CERT_AUTHORITY => 'system',
                Rackspace::CURL_OPTIONS => [
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ],
            ]
        );
    }

    protected function progressOne(ProgressBar $progress, OutputInterface $output, $addLineBreak = false)
    {
        $progress->advance();
        if ($addLineBreak) {
            $output->writeln('');
        }
    }
}
