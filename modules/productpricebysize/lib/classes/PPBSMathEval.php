<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

class PPBSMathEval
{
    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';
    const PARENTHESIS_DEPTH = 10;

    public static function calculate($input)
    {
        $ppbs_eval_math = new PPBSEvalMath();
        return $ppbs_eval_math->evaluate($input);
    }

    private static function compute($input)
    {
        $x = create_function('', 'return number_format(' . $input . ', 12);');
        return number_format(0 + $x(), 12);
    }

    private static function callback($input)
    {
        if (is_numeric($input[1])) {
            return $input[1];
        } elseif (preg_match(self::PATTERN, $input[1], $match)) {
            return self::compute($match[0]);
        }
        return 0;
    }

    public static function computeEquation($equation, $params)
    {
        foreach ($params as $key => $val) {
            if (empty($val)) {
                $val = 0;
            }
            $equation = str_replace('[' . $key . ']', $val, $equation);
        }

        //it's possible some fields no longer exists in some products, replace all unmatched fields with 0
        $equation = preg_replace('/\[.*\]/', '0', $equation);

        $equation = self::parseConditions($equation, $params);
        return self::calculate($equation);
    }


    /**
     * Parse if else blocks in equation and return equation with evaluated values
     * @param $equation
     * @param $params
     */
    private static function parseConditions($equation, $params)
    {
        preg_match_all('/{if(.*?){\/if}/sm', $equation, $matches);

        // Parse all {if}{else}{/if} blocks in the equation and replace with resulting values from arithmetic operation
        if (!empty($matches)) {
            for ($x = 0; $x <= sizeof($matches[0]) - 1; $x++) {
                $str = $matches[1][$x];

                $if_str = substr($str, 0, strpos($str, '}'));

                $tmmp_func = create_function('', 'return ' . $if_str . ';');
                $condition_result = $tmmp_func();

                $str = substr($str, strpos($str, '}') + 1);
                $results = explode('{else}', $str);

                if ($condition_result) {
                    $result = $results[0];
                } else {
                    $result = $results[1];
                }
                $equation = str_replace($matches[0][$x], $result, $equation);
            }
        }
        return $equation;
    }
}
