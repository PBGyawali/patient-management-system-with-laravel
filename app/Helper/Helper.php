<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
class Helper
{
    public static function instance()
    {
        return new Helper();
    }

    static function get_datetime()
	{
		return date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
    }
    private static function check_array($value){
		if (is_array($value))
			return $value;
			return array($value);
	}
    static function CountTable($table,$condition=null,$value=null,$compare='=',$attr=array()){
        $query = DB::table($table);
        $condition=self::check_array($condition);
        $value=self::check_array($value);
        if($condition)
        foreach($condition as $key=> $column)
        {
            if($key==='raw')
                $query->whereRaw("$column $compare '".$value[$key] ."'".(isset($attr['interval'])?$attr['interval']:' '));
            else
                $query->where($column, $compare, $value[$key]);
        }
        return $query->count();
    }

    static function format_number($number,$noformat=false) {
        $precision=3;
        $suffix = '';
        $divider=1;
        if($noformat)
            return $number;
        if ($number < 900) {
            // 0 - 900
            $precision=1;
        } else if ($number < 900000) {
            // 0.9k-850k
            $precision=2;
            $divider= 1000;
            $suffix = 'K';
        } else if ($number < 900000000) {
            // 0.9m-850m
            $divider=1000000;
            $suffix = 'M';
        } else if ($number < 900000000000) {
            // 0.9b-850b
            $divider=1000000000;
            $suffix = 'B';
        } else {
            // 0.9t+
            $divider=1000000000000;
            $suffix = 'T';
        }
        $number_format =0+ number_format($number /$divider, $precision);

      // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
      // Intentionally does not affect partials, eg "1.50" -> "1.50"
        /*if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $number_format = str_replace( $dotzero, '', $number_format );
        }*/
        return $number_format . $suffix;
    }
}
