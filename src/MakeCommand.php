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
        $code = $this->compile($this->getSources());

        if ($this->option('output')) {
            $this->save($code);
        } else {
            $this->line($code);
        }
    }

    /**
     * Get the source files.
     *
     * @return array
     */
    protected function getSources()
    {
        $sources = [];

        foreach ($this->argument('file') as $path) {
            $sources[$path] = file_get_contents($path);
        }

        return $sources;
    }

    /**
     * Transform strings of JavaScript
     * code to a bookmarklet.
     *
     * @param  array  $sources
     * @return string
     */
    protected function compile($sources)
    {
        // Minify the files that can be minified.
        foreach ($sources as $path => $source) {

            if ($this->isMinifyable($path)) {
                $source = $this->minify($source);
            }

            $sources[$path] = $source;
        }

        // Concatenate the sources together.
        $source = implode("\n", $sources);

        return (new Bookmarkler)->make($source);
    }

    /**
     * Check if a given file can be minified.
     *
     * @param  string  $path
     * @return bool
     */
    protected function isMinifyable($path)
    {
        return !in_array($path, $this->option('no-minify'));
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

        if (!is_writable(dirname($path))) {

            $this->error("[{$path}] is not writable");

            return;
        }

        file_put_contents($path, $code);

        $this->info("Bookmaklet code saved to <comment>{$path}</comment>");
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
            ],
            [
                'no-minify',
                'i',
                InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY,
                'List of files that should be included in the bookmarklet but not minified.',
                [],
            ]
        ];
    }
}
