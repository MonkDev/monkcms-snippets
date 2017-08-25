<?php

/**
 * Replace English words with Spanish.
 *
 * Since there are so many replacements being made in this class, I would recommend
 * using this class only when necessary. For example, if a template hard-codes
 * words like "listen" or "watch"... hard-code the spanish terms there instead of
 * replacing them.
 *
 * EXAMPLE
 * $translated = SpanishTranslator::replace($string);
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class SpanishTranslator
{
    /**
     * A dictionary of English to Spanish translations.
     *
     * @var array
     */
    private static $dictionary = array(
        // Technology
        'article'    => 'artículo',
        'articles'   => 'artículos',
        'sermon'     => 'predica',
        'sermons'    => 'predicas',
        'listen'     => 'escucha',
        'watch'      => 'ver',
        'download'   => 'descargar',
        'search'     => 'buscar',
        'send'       => 'enviar',
        'submit'     => 'enviar',

        // Sorting
        'sort'       => 'ordenar',
        'preacher'   => 'predicador',
        'speaker'    => 'orador',
        'category'   => 'categoría',
        'categories' => 'categorías',
        'group'      => 'grupo',
        'groups'     => 'grupos',

        // Days
        'day'        => 'día',
        'Monday'     => 'lunes',
        'Tuesday'    => 'martes',
        'Wednesday'  => 'miércoles',
        'Thursday'   => 'jueves',
        'Friday'     => 'viernes',
        'Saturday'   => 'sábado',
        'Sunday'     => 'domingo',

        // Months
        'month'      => 'mes',
        'January'    => 'enero',
        'February'   => 'febrero',
        'March'      => 'marzo',
        'April'      => 'abril',
        'May'        => 'mayo',
        'June'       => 'junio',
        'July'       => 'julio',
        'August'     => 'agosto',
        'September'  => 'septiembre',
        'October'    => 'octubre',
        'November'   => 'noviembre',
        'December'   => 'diciembre',

        // Time grammar
        'latest'     => 'último',
        'recent'     => 'reciente',
        'every'      => 'cada',
        'time'       => 'hora'
    );

    /**
     * Make all replacements based on the dictionary of terms.
     *
     * @param  string $text The text to operate on
     * @return string
     */
    public static function replace($text)
    {
        foreach (self::$dictionary as $en => $sp) {
            $text = self::findAndReplaceString($en, $sp, $text);
        }

        return $text;
    }

    /**
     * Search and replace a whole word, honoring the original capitalization.
     *
     * @param  string $find    The string to search
     * @param  string $replace The string to replace with
     * @param  string $subject The text
     * @return string
     */
    private static function findAndReplaceString($find, $replace, $subject)
    {
        $pattern = '/\b' . preg_quote($find) . '\b/i';
        preg_match($pattern, $subject, $matches);

        if (isset($matches[0])) {
            $replace = ctype_upper($matches[0][0]) ? ucfirst($replace) : $replace;
            return preg_replace($pattern, $replace, $subject);
        }

        return $subject;
    }
}
