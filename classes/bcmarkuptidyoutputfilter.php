<?php
/**
 * File containing the BcMixedContentPreventionFunctions class.
 *
 * @copyright Copyright (C) 1999 - 2017 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or later)
 * @version 0.1.0
 * @package bcmixedcontentpreventionfilter
 */

/*
 * BcMarkupTidyOutputFilter functions
 *
*/
class BcMarkupTidyOutputFilter
{
    /*
     * Clean up / Tidy HTML Output to Look Clean and near-perfectly formatted
     *
    */
    static function outputFilter( $templateResultOutput )
    {
        $htmlOutput = $templateResultOutput;

        $siteINI = eZINI::instance('bcmarkuptidyoutputfilter.ini');

        $outputTidyFormat = $siteINI->variable( 'BCMarkupTidyOutputFilterSettings', 'TidyFormat' );
        $outputTidyFormatEnabled = ( $outputTidyFormat == 'enabled' ) ? true : false;

        if( $outputTidyFormatEnabled )
        {
            // Specify configuration
            $htmlOutputCharset = $siteINI->variable( 'BCMarkupTidyOutputFilterSettings', 'TidyCharset' );

            $tidyConfigurationString = $siteINI->variable( 'BCMarkupTidyOutputFilterSettings', 'TidyConfiguration' );

            $tidyConfigurationArray = array();
            foreach( $tidyConfigurationString as $string )
            {
                $configPartArray = explode( ';', $string );
                $tidyConfigurationArray[ $configPartArray[0] ] = $configPartArray[1];
            }

            // Tidy
            $tidy = new tidy;
            $tidyConfig = $tidyConfigurationArray;

            $tidy->parseString( $htmlOutput, $tidyConfig, $htmlOutputCharset );
            $tidy->cleanRepair();

            $htmlOutput = $tidy;
        }

        $outputBlockFormat = $siteINI->variable( 'BCMarkupTidyOutputFilterSettings', 'BlockFormat' );
        $outputBlockFormatEnabled = ( $outputBlockFormat == 'enabled' ) ? true : false;
 
        if( $outputBlockFormatEnabled )
        {
            $search = array(
                '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
                '/[^\S ]+\</s',     // strip whitespaces before tags, except space
                '/(\s)+/s',         // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/' // Remove HTML comments
            );
            $replace = array(
               '>',
               '<',
               '\\1',
               ''
             );

             $spaceLessOutput = preg_replace( $search, $replace, $htmlOutput );
             $htmlOutput = $spaceLessOutput;
         }

         return $htmlOutput;
    }
}