<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
  protected $table = 'visits';
  protected $fillable = [
    'ip',
    'created',
    'hits',
    'online',
  ];

  public static function getVisited()
  {
    $d = date("d");
    $yd = date('d', strtotime('-1 day', time()));
    $m = date("m");
    $mago = date('m', strtotime('-1 month', time()));
    $y = date("Y");
    $yago = date('Y', strtotime('-1 year', time()));
    $deadline = time() - 300;

    $data['today'] = self::where(\DB::raw("(DATE_FORMAT(created,'%d'))"), "$d")->count(); //วันนี้
    $data['yesterday'] = self::where(\DB::raw("(DATE_FORMAT(created,'%d'))"), "$yd")->count(); //เมื่อวาน
    $data['month'] = self::where(\DB::raw("(DATE_FORMAT(created,'%m'))"), "$m")->count(); //เดือนนี้
    $data['month_ago'] = self::where(\DB::raw("(DATE_FORMAT(created,'%m'))"), "$mago")->count(); //เดือนที่ผ่านมา
    $data['year'] = self::where(\DB::raw("(DATE_FORMAT(created,'%m'))"), "$y")->count(); //ปีนี้
    $data['year_ago'] = self::where(\DB::raw("(DATE_FORMAT(created,'%m'))"), "$yago")->count(); //ปีที่ผ่านมา
    $data['total_hits'] =  self::sum('hits');
    // $data['online_visitors'] = self::where('online', '>', "$deadline")->count();

    return $data;
  }
}
