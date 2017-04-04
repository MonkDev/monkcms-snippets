<?php

/**
 * MCMS MEDIA REDIRECT
 *
 * Looks up media records with the MCMS API and performs a redirect.
 * This is useful for adding shortcuts, redirecting local media URLs like:
 *
 * http://site.com/mediafiles/document.pdf
 * to
 * http://site.com/mediafiles/uploaded/d/document.pdf (or the CDN URL...)
 *
 * Add this to the top of the site htaccess file. The rewrite condition checks if
 * the referenced filename is not a path of an existing file on the server.
 *
 // Redirect media links
 RewriteCond %{REQUEST_FILENAME} !-f
 RewriteRule ^mediafiles/(.+)$ mcms_media_redirect.php?file=$1 [L]
 *
 */

require $_SERVER['DOCUMENT_ROOT'] . '/monkcms.php';

class MediaRedirect
{
    private static $encodedFormats = array(
    	'mov',
    	'mp4',
    	'mkv',
    	'm4v',
    	'flv'
    );

    public static function findAndRedirect($file)
    {
        $find = self::findAndMatch($file);

        // Make sure we won't get stuck in a redirect loop.
        $findPath = parse_url($file, PHP_URL_PATH);
        $filePath = parse_url($find, PHP_URL_PATH);
        $pathsDiffer = $findPath != $filePath;

        // Redirect.
        if ($find && $pathsDiffer) {
            header('Location:' . $find);
        } else {
            header('HTTP/1.0 404 Not Found');
        }

        exit();
    }

    public static function findAndMatch($file)
    {
        $isEncodedFormat = self::isEncodedFormat($file);
        $originalBasename = self::getOriginalBasename($file);
        $originalFilename = self::getFilename($originalBasename);

        // Search the API for this filename.
        if ($isEncodedFormat) {
            $find = self::find($originalFilename);
        } else {
            $find = self::find($originalBasename);
        }

        // Check if found file matches the filename we'd expect.
        $isMatch = self::isMatch($file, $find, $isEncodedFormat);

        return $isMatch ? $find : null;
    }

    private static function isMatch($file1, $file2, $ignoreExtension = false)
    {
        $basename1 = self::getOriginalBasename($file1);
        $basename2 = self::getOriginalBasename($file2);

        if (!$ignoreExtension) {
            return $basename1 == $basename2;
        } else {
            return self::getFilename($basename1) == self::getFilename($basename2);
        }
    }

    private static function find($file)
    {
        $find = self::findViaMediaApi($file);

        if (!$find) {
            $find = self::findViaSearchApi($file);
        }

        return $find;
    }

    private static function findViaMediaApi($file)
    {
        $response = getContent(
            'media',
            'display:detail',
            'find:' . $file,
            'howmany:1',
            'show:__url__',
            'noecho'
        );

        return trim($response);
    }

    private static function findViaSearchApi($file)
    {
        $response = getContent(
            'search',
            'display:results',
            'find_module:media',
            'keywords:' . $file,
            'howmany:1',
            'show:__url__',
            'no_show: ',
            'noecho'
        );

        return trim($response);
    }

    private static function isEncodedFormat($file)
    {
        $extension = strtolower(self::getExtension($file));

        return in_array($extension, self::$encodedFormats);
    }

    private static function getOriginalBasename($path)
    {
        $basename = self::getBasename($path);

        // Remove all prefixes denoted with underscores.
        if (strpos($basename, '_') !== false) {
            $basename = array_pop(explode('_', $basename));
        }

        return $basename;
    }

    private static function getBasename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    private static function getFilename($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    private static function getExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

}

// Find the file set in the query string redirect to it.
MediaRedirect::findAndRedirect($_GET['file']);

