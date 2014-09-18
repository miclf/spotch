<?php namespace Miclf\Spotch;

class Minifier
{
    /**
     * Store strings that need to be protected
     * from accidental modifications.
     *
     * @var array
     */
    protected $safe = [
        'protocol' => [],
        'regex'    => [],
    ];

    /**
     * Regular expressions to identify and extract
     * strings that need to be shielded.
     *
     * @var array
     */
    protected $shieldRegex = [
        'protocol' => '#(\w+://)#U',
        'regex'    => '#((?<!\x5C)/.*(?<!\x5C)/[a-zA-Z]*)#U',
    ];

    /**
     * Regular expressions to identify and remove
     * optional space characters.
     *
     * @var array
     */
    protected $cleanRegex = [
        '#\s*if\s*#'                 => 'if',
        '#\s*else\s*#'               => 'else',
        '#([^+])(\s*\+\s*)([^+])#'   => '$1+$3',
        '#([^=])(\s*=\s*)([^=])#'    => '$1=$3',
        '#([^=])(\s*==\s*)([^=])#'   => '$1==$3',
        '#([^:])(\s*:\s*)([^:])#'    => '$1:$3',
        '#([^,])(\s*,\s*)([^,])#'    => '$1,$3',
        '#([^{])(\s*\{\s*)([^{])#'   => '$1{$3',
        '#([^}])(\s*\}\s*)([^}])#'   => '$1}$3',
        '#([^(])(\s*\(\s*)([^(])#'   => '$1($3',
        '#([^)])(\s*\)\s*)([^)])#'   => '$1)$3',
        '#([^&])(\s*&&\s*)([^&])#'   => '$1&&$3',
        '#([^|])(\s*\|\|\s*)([^|])#' => '$1||$3',
    ];

    /**
     * Minify a string of JavaScript code.
     *
     * @param  string  $source
     * @return string
     */
    public function minify($source)
    {
        // We start by extracting special strings from the code
        // in order to prevent any accidental modification.
        $code = $this->shield($source);

        // Then, we remove the comments, since they’re
        // not needed by the interpreter.
        $code = $this->removeComments($code);

        // We do some optional optimizations by removing
        // space characters in different situations.
        $code = $this->removeOptionalSpaces($code);

        // Finally, we clean lines by trimming them and
        // removing empty ones.
        $code = $this->trimLines($code);
        $code = $this->removeEmptyLines($code);
        $code = $this->removeNewlines($code);

        // To conclude, we reinject the special strings
        // in the minified code and return it.
        return $this->unshield($code);
    }

    /**
     * Remove comments from a string.
     *
     * @param  string  $source
     * @return string
     */
    public function removeComments($source)
    {
        $lines = $this->splitLines($source);

        $lines = array_map([$this, 'removeSingleLineComment'], $lines);

        return $this->joinLines($lines);
    }

    /**
     * Extract strings that need to be protected from
     * accidental modifications.
     *
     * @param  string  $source
     * @return string
     */
    protected function shield($source)
    {
        foreach ($this->shieldRegex as $type => $pattern) {
            $source = $this->shieldPattern($source, $pattern, $type);
        }

        return $source;
    }

    /**
     * Protect substrings matching a pattern
     * from accidental modifications.
     *
     * @param  string  $source
     * @param  string  $pattern
     * @param  string  $type
     * @return string
     */
    protected function shieldPattern($source, $pattern, $type)
    {
        return preg_replace_callback($pattern, function ($matches) use ($type) {

            $this->safe[$type][] = $matches[1];

            return "__{$type}__";

        }, $source);
    }

    /**
     * Reinject strings that were shielded.
     *
     * @param  string  $source
     * @return string
     */
    protected function unshield($source)
    {
        foreach ($this->shieldRegex as $type => $pattern) {
            $source = $this->unshieldPattern($source, $type);
        }

        return $source;
    }

    /**
     * Reinject substrings matching a given pattern.
     *
     * @param  string  $source
     * @param  string  $type
     * @return string
     */
    protected function unshieldPattern($source, $type)
    {
        $pattern = "#__{$type}__#U";

        return preg_replace_callback($pattern, function ($matches) use ($type) {

            return array_shift($this->safe[$type]);

        }, $source);
    }

    /**
     * Split a string to an array of lines.
     *
     * @param  string  $source
     * @return array
     */
    protected function splitLines($source)
    {
        return preg_split('#[\n\r]+#', $source);
    }

    /**
     * Concatenate an array of strings to a single string.
     *
     * @param  array   $lines
     * @param  string  $glue
     * @return string
     */
    protected function joinLines(array $lines, $glue = "\n")
    {
        return implode($glue, $lines);
    }

    /**
     * Remove single line comments from a string.
     *
     * @param  string  $source
     * @return string
     */
    protected function removeSingleLineComment($source)
    {
        $comment = mb_strpos($source, '//');

        if ($comment !== false) {
            $source = substr($source, 0, $comment);
        }

        return $source;
    }

    /**
     * Remove empty lines from a string.
     *
     * @param  string  $source
     * @return string
     */
    public function removeEmptyLines($source)
    {
        $lines = $this->splitLines($source);

        $lines = array_filter($lines, function ($line) {
            return (bool) trim($line);
        });

        return $this->joinLines($lines);
    }

    /**
     * Trim individual lines in a string.
     *
     * @param  string  $source
     * @return string
     */
    public function trimLines($source)
    {
        $lines = $this->splitLines($source);

        $lines = array_map(function ($line) {
            return trim($line);
        }, $lines);

        return $this->joinLines($lines);
    }

    /**
     * Remove newline characters from a string.
     *
     * @param  string  $source
     * @return string
     */
    public function removeNewlines($source)
    {
        $lines = $this->splitLines($source);

        return trim($this->joinLines($lines, ''), "\n\r");
    }

    /**
     * Remove optional spaces from a string.
     *
     * @param  string  $source
     * @return string
     */
    public function removeOptionalSpaces($source)
    {
        foreach ($this->cleanRegex as $pattern => $replacement) {
            $source = preg_replace($pattern, $replacement, $source);
        }

        return $source;
    }
}
