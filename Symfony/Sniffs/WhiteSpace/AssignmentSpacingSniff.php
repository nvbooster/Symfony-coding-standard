<?php

/**
 * This file is part of the Symfony2-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */

/**
 * Symfony2_Sniffs_WhiteSpace_AssignmentSpacingSniff.
 *
 * Throws warnings if an assignment operator isn't surrounded with whitespace.
 *
 * PHP version 5
 *
 * @category PHP
 * @package Symfony2-coding-standard
 * @author Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link https://github.com/escapestudios/Symfony2-coding-standard
 */
class Symfony2_Sniffs_WhiteSpace_AssignmentSpacingSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP'
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return PHP_CodeSniffer_Tokens::$assignmentTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $declareStatement = false;

        // check for declare
        if ( !empty($tokens[$stackPtr]['nested_parenthesis'])) {
            $closeParenthesisPtr = min($tokens[$stackPtr]['nested_parenthesis']);
            if ( !empty($tokens[$closeParenthesisPtr]['parenthesis_owner'])) {
                if ($tokens[$tokens[$closeParenthesisPtr]['parenthesis_owner']]['code'] === T_DECLARE) {
                    $declareStatement = true;
                }
            }
        }

        if ($declareStatement) {
            if (
                $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
                || $tokens[$stackPtr + 1]['code'] === T_WHITESPACE
            ) {
                $phpcsFile->addError('No spaces needed around assignment in declare statements', $stackPtr, 'Invalid');
            }
        } else {
            if (
                $tokens[$stackPtr - 1]['code'] !== T_WHITESPACE
                || $tokens[$stackPtr - 1]['length'] > 1
                || $tokens[$stackPtr + 1]['code'] !== T_WHITESPACE
                || $tokens[$stackPtr + 1]['length'] > 1
            ) {
                $phpcsFile->addError('Single spaces expected around assignment operators', $stackPtr, 'Invalid');
            }
        }
    }
}
