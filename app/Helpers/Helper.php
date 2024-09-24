<?php

if (! function_exists('admin_url')) {
    /**
     * Generate a url for the application.
     * @param string $path
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function admin_url($path = null)
    {
        $admin_path = ADMIN_PREFIX . '/';
        if (is_null($path)) {
            return url($admin_path);
        }
        return url($admin_path . $path);
    }
}

if(!function_exists("encrypt"))
{
    function encrypt($string, $key) {
        $result = '';
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result.=$char;
        }
        return base64_encode($result);
    }
}
if(!function_exists("decrypt"))
{
    function decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        if ($errors->isEmpty()){
            return 0;
        }
        return $result;
    }
}

if(!function_exists("format_to_date"))
{
    function format_to_date($date, $separator='/')
    {
        if($date)
        {
            $dateArray = explode($separator, $date);
            $date = $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
        }
        return $date;
    }
}
if(!function_exists("format_date_to_show"))
{
    function format_date_to_show($date, $separator='-')
    {
        if($date)
        {
            $dateArray = explode($separator, $date);
            $date = $dateArray[1].'/'.$dateArray[2].'/'.$dateArray[0];
        }
        return $date;
    }
}

