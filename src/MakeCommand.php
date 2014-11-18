<?php namespace Miclf\Spotch;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Transform JavaScript code to a bookmarklet-friendly URL.
 *
 * @author Michaël Lecerf <michael@estsurinter.net>
 */
class MakeCommand extends Command
{
    /**
     * Name of the command.
     *
     * @var string
     */
    protected $name = 'make';

    /**
     * Description of the command.
     *
     * @var string
     */
    protected $description = 'Transform JavaScript code to a bookmarklet URL.';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $code = $this->compile($this->getSource());

        if ($this->option('output')) {
            $this->save($code);
        } else {
            $this->line($code);
        }
    }

    /**
     * Get the source JavaScript code.
     *
     * @return string
     */
    protected function getSource()
    {
        $path = $this->argument('file');

        return file_get_contents($path);
    }

    /**
     * Transform a string of JavaScript
     * code to a bookmarklet.
     *
     * @param  string  $source
     * @return string
     */
    protected function compile($source)
    {
        $bookmarkler = new Bookmarkler;

        return $bookmarkler->make($source);
    }

    /**
     * Minify a string of JavaScript code.
     *
     * @param  string  $source
     * @return string
     */
    protected function minify($source)
    {
        return (new Minifier)->minify($source);
    }

    /**
     * Write the bookmarklet to disk.
     *
     * @param  string  $code
     * @return void
     */
    protected function save($code)
    {
        $path = $this->option('output');

        file_put_contents($path, $code);

        $this->info("Bookmaklet code save to <comment>{$path}</comment>");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'file',
                InputArgument::IS_ARRAY,
                'Path(s) to the source file(s) to transform',
                ['./script.js']
            ]
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'output',
                'o',
                InputOption::VALUE_OPTIONAL,
                'If set, the output will be saved to that path instead of being dumped to stdout.',
            ]
        ];
    }
}
