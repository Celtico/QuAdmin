<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuAdmin;

class Util
{

    public static function urlFilter($string){

        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);

        $NOT_acceptable_characters_regex = '#[^-a-zA-Z0-9_ ]#';
        $string = preg_replace($NOT_acceptable_characters_regex, '', $string);
        $string = trim($string);
        $string = preg_replace('#[-_ ]+#', '-', $string);
        $string =  strtolower($string);

        return $string;
    }

    public static function getter($string){

        $string = explode('_',$string);
        $col = '';
        foreach($string as $s){
            if($s){
               $col .= ucfirst($s);
            }
        }
        return  'get' . $col;
    }

    public static function setter($string){

        $string = explode('_',$string);
        $col = '';
        foreach($string as $s){
            if($s){
                $col .= ucfirst($s);
            }
        }
        return  'set' . $col;
    }

    public static function  active($param,$active,$css)
    {
        if($param == $active){
            $active = $css;
        }else{
            $active = '';
        }
        return $active;
    }

}
