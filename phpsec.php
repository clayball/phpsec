<?php

/******************************************************************************
 *
 * phpsec.php
 *
 * Clay Wells <cwells (at) cwells (.) org>
 *
 * Get PHP tokens and perform static analysis checks that we define (TODO)
 *
 * A full list of parser tokens is available at:
 *
 * http://php.net/manual/en/tokens.php
 *
 * The tokenizer functions provide an interface to the PHP tokenizer embedded
 * in the Zend Engine. Using these functions you may write your own PHP source
 * analyzing or modification tools without having to deal with the language
 * specification at the lexical level.
 * - source: http://php.net/manual/en/function.token-get-all.php
 *
 * token_get_all()
 * An array of token identifiers. Each individual token identifier is either a
 * single character (i.e.: ;, ., >, !, etc...), or a three element array
 * containing the token index in element 0, the string content of the original
 * token in element 1 and the line number in element 2.
 ******************************************************************************
 */


/**
 * T_ML_COMMENT does not exist in PHP 5.
 * The following three lines define it in order to
 * preserve backwards compatibility.
 *
 * The next two lines define the PHP 5 only T_DOC_COMMENT,
 * which we will mask as T_ML_COMMENT for PHP 4.
 */
if (!defined('T_ML_COMMENT')) {
    define('T_ML_COMMENT', T_COMMENT);
} else {
    define('T_DOC_COMMENT', T_ML_COMMENT);
}




/******************************************************************************
 * Functions
 */


/**
 * @param $infile
 *
 * Pulled this example from php.net
 */
function strip_comments($infile)
{
    $source = file_get_contents($infile);
    $tokens = token_get_all($source);

    foreach ($tokens as $token) {
        if (is_string($token)) {
            // simple 1-character token
            echo $token;
        } else {
            // token array
            list($id, $text) = $token;

            switch ($id) {
                case T_COMMENT:
                case T_ML_COMMENT: // we've defined this
                case T_DOC_COMMENT: // and this
                    // no action on comments
                    break;

                default:
                    // anything else -> output "as is"
                    echo $text;
                    break;
            }
        }
    }
}


/**
 * @param $infile
 *
 * TODO: add token processing, still need to think this through :)
 */
function get_all_tokens($infile)
{
    $source = file_get_contents($infile);
    $tokens = token_get_all($source);

    echo "[*] Reading file: ", $infile, PHP_EOL;

    foreach ($tokens as $token) {
        if (is_string($token)) {
            echo "[*] token is string: ", $token, PHP_EOL;
        } else {
            list($id, $text, $line) = $token;
            // adding single quotes around text so we can see the whitespace
            // and cr/lf's
            echo "[*] line: ", $line, " token data: ", $id, " ", token_name($id),
                " ", "'", $text, "'", PHP_EOL;
        }
    }
}


/******************************************************************************
 * Main Program
 */

// get the file to inspect
// TODO: allow directories and a list of file extensions
$infile = $argv[1];

// TODO: verify user input, does the file/directory exist

// call each functions
//strip_comments($infile);
get_all_tokens($infile);

?>