<?php
namespace App\Helpers;
class MongoDate
{
    public static function getFromTimestamp( $timestamp = null){
        if( !$timestamp ){
            $timestamp = time();
        }    
        return new \MongoDB\BSON\UTCDateTime( intval( $timestamp ) * 1000 );
    }
    public static function parse( $mongoDate ){
      
        if( !self::isMongoDate( $mongoDate ) ){
            return '';
        }
        $date = $mongoDate->toDateTime();
        $tz = new \DateTimeZone(env('APP_TIMEZONE'));
        $date = $date->setTimezone( $tz )->format( 'Y-m-d H:i:s' );
        return $date;
    }
    public static function isMongoDate( $date ){
        return gettype( $date ) == "object" && get_class( $date ) === 'MongoDB\BSON\UTCDateTime';
    }
}