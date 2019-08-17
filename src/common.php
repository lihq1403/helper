<?php

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     * 如果是个闭包 返回闭包执行结果 否则 返回值
     * @param mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('filter_check')) {
    /**
     * 使用filter_var方式验证
     * @access public
     * @param  mixed     $value  字段值
     * @param  mixed     $rule  验证规则
     * @return bool
     */
    function filter_check($value, $rule)
    {
        if (is_string($rule) && strpos($rule, ',')) {
            list($rule, $param) = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = isset($rule[1]) ? $rule[1] : null;
            $rule  = $rule[0];
        } else {
            $param = null;
        }

        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }
}

if (!function_exists('regex_check')) {
    /**
     * 使用正则验证数据
     * @access public
     * @param  mixed     $value  字段值
     * @param  mixed     $rule  验证规则 正则规则
     * @return bool
     */
    function regex_check($value, $rule)
    {
        if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            // 不是正则表达式则两端补上/
            $rule = '/^' . $rule . '$/';
        }

        return is_scalar($value) && 1 === preg_match($rule, (string) $value);
    }
}
