<?php namespace Miclf\Spotch;

class Bookmarkler
{
    /**
     * Create a bookmarklet from a string
     * of JavaScript code.
     *
     * @param  string  $source
     * @return string
     */
    public function make($source)
    {
        $minified = $this->minify($source);

        $encoded = $this->encode($minified);

        return 'javascript:(function(){'.$encoded.'})();';
    }

    /**
     * Minify a string of JavaScript code.
     *
     * @param  string  $source
     * @return string
     */
    protected function minify($source)
    {
        $minifier = new Minifier;

        return $minifier->minify($source);
    }

    /**
     * Encode special characters to make
     * them URL-friendly.
     *
     * @param  string  $source
     * @return string
     */
    protected function encode($source)
    {
        return rawurlencode($source);
    }
}
