<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 15:47
 */
namespace Core\View\Helper;

//use Bluz\View\View;

return
    /**
     * @author The-Who
     *
     * @param array $attributes
     * @return \Closure
     */
    function (array $attributes = array()) {

        /** @var View $this */


        // atr bug
        if(isset($attributes[0]))
            $attributes = $attributes[0];
        // --

        if (empty($attributes)) {
            return '';
        }
        $result = array();
        foreach ($attributes as $key => $value) {
            if (null === $value) {
                // skip null values
                // ['value'=>null] => ''
                continue;
            }
            if (is_int($key)) {
                // allow non-associative keys
                // ['checked'] => 'checked="checked"'
                $key = $value;
            }
            $result[] = $key . '="' . htmlspecialchars((string)$value, ENT_QUOTES) . '"';
        }

        return join(' ', $result);
    };
