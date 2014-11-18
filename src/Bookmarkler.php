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
        $encoded = $this->encode($source);

        return 'javascript:(function(){'.$encoded.'})();';
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
