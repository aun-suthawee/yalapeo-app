<?php

if (!function_exists("YEAR")) {
  function YEAR($prefix = "")
  {
    if ($prefix == "th")
      return (date('Y') + 543);

    return date('Y');
  }
}

if (!function_exists("_enDateTimeFormat")) {
  function _enDateTimeFormat($datetime)
  {
    [$data, $time] = explode(" ", $datetime);
    [$d, $m, $y] = explode("/", $data);

    return "$y-$m-$d $time";
  }
}


if (!function_exists("thai_month_arr")) {
  function thai_month_arr($month = "", $type = "normal")
  {
    if ($type == "short") {
      $arr = [
        "01" => "ม.ค.",
        "02" => "ก.พ.",
        "03" => "มี.ค.",
        "04" => "เม.ย.",
        "05" => "พ.ค.",
        "06" => "มิ.ย.",
        "07" => "ก.ค.",
        "08" => "ส.ค.",
        "09" => "ก.ย.",
        "10" => "ต.ค.",
        "11" => "พ.ย.",
        "12" => "ธ.ค."
      ];
    } else {
      $arr = [
        "01" => "มกราคม",
        "02" => "กุมภาพันธ์",
        "03" => "มีนาคม",
        "04" => "เมษายน",
        "05" => "พฤษภาคม",
        "06" => "มิถุนายน",
        "07" => "กรกฎาคม",
        "08" => "สิงหาคม",
        "09" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม"
      ];
    }

    return $month == "" ? $arr : $arr[$month];
  }
}

if (!function_exists("thaiDate")) {
  function thaiDate($date, $type = 'normal')
  {
    $d = date("d", strtotime($date));
    $m = date("m", strtotime($date));
    $y = date("Y", strtotime($date)) + 543;

    if ($type == 'long') {
      $result = $d . " เดือน" . thai_month_arr($m) . " พ.ศ. " . $y;
    } elseif ($type == 'short') {
      $result = $d . " " . thai_month_arr($m, 'short') . " " . $y;
    } else {
      $result = $d . "/" . $m . "/" . $y;
    }

    return strtotime($date) <= 0 ? "" : $result;
  }
}

if (!function_exists("getShortDateFormat")) {
  function getShortDateFormat($date, $format)
  {
    return date("$format", strtotime($date));
  }
}
