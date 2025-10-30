<?php

if (!function_exists("_genNoIndex")) {
  function _genNoIndex($prefixed = "", $number = "", $digit = 4)
  {
    $response = $prefixed . sprintf("%0{$digit}d", $number);

    return $response;
  }
}

if (!function_exists('_stripTags')) {
  function _stripTags($text = '')
  {
    $data = strip_tags(preg_replace("/<img[^>]+\>/i", '', stripcslashes($text)), '');

    return $data;
  }
}

if (!function_exists('site_asset_common_img_url')) {
  function site_asset_common_img_url($uri = '')
  {
    return asset('assets/images/' . $uri);
  }
}

if (!function_exists('genSlug')) {
  function genSlug($string = '')
  {
    $string = preg_replace("`\[.*\]`U", "", $string);
    $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $string);
    $string = str_replace('%', '-percent', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string);
    $string = preg_replace(["`[^a-z0-9ก-๙เ-า]`i", "`[-]+`"], "-", $string);
    $response = strtolower(trim($string, '-'));

    return $response;
  }
}

if (!function_exists('breadcrumb')) {
  function breadcrumb($data = [], $dir = 'ltr'): string
  {
    $result = '';
    $count = count($data);
    if ($count > 0) :
      $i = 1;
      $active = '';
      $result .= '<ol dir="' . $dir . '" class="app-breadcrumb breadcrumb">';
      foreach ($data as $key => $val) :
        if ($i == $count) {
          $result .= '<li class="breadcrumb-item active">' . $val['label'] . '</li>';
        } else {
          $result .= '<li class="breadcrumb-item"><a href="' . $val['link'] . '" class="text-decoration-none">' . $val['label'] . '</a></li>';
        }
        ++$i;
      endforeach;
      $result .= '</ol>';
    endif;

    return $result;
  }
}

if (!function_exists('_fileExists')) {
  function _fileExists($folder = '', $string = ''): bool
  {
    return (($string != "") && \Storage::disk('public')->exists("/$folder/$string"));
  }
}

if (!function_exists('__avatar')) {
  function __avatar()
  {
    return asset('assets/images/user.png');
  }
}

if (!function_exists('__via_placeholder')) {
  function __via_placeholder($width, $height)
  {
    return "https://via.placeholder.com/{$width}x{$height}.png?text={$width}x{$height}";
  }
}

if (!function_exists('__fileExtension')) {
  function __fileExtension($file_name)
  {
    $tmp = explode('.', $file_name);
    $file_extension = strtolower(end($tmp));

    $types = [
      'pdf' => '<i class="fas fa-file-pdf fa-fw"></i>',
      'doc' => '<i class="fas fa-file-word fa-fw text-primary"></i>',
      'docx' => '<i class="fas fa-file-word fa-fw text-primary"></i>',
      'ppt' => '<i class="fas fa-file-powerpoint fa-fw text-danger"></i>',
      'pptx' => '<i class="fas fa-file-powerpoint fa-fw text-danger"></i>',
      'xls' => '<i class="fas fa-file-excel fa-fw text-success"></i>',
      'xlsx' => '<i class="fas fa-file-excel fa-fw text-success"></i>',
      'jpg' => '<i class="fas fa-image"></i>',
      'jpeg' => '<i class="fas fa-image"></i>',
      'png' => '<i class="fas fa-image"></i>',
      'gif' => '<i class="fas fa-image"></i>'
    ];

    // ถ้าไม่มีในรายการ ให้แสดงไอคอน file ทั่วไป
    return isset($types[$file_extension]) ? $types[$file_extension] : '<i class="fas fa-file fa-fw"></i>';
  }
}

if (!function_exists('__fileType')) {
  function __fileType($file_name)
  {
    $tmp = explode('.', $file_name);
    $file_extension = strtolower(end($tmp));

    $types = [
      'pdf' => 'pdf',
      'doc' => 'office',
      'docx' => 'office',
      'ppt' => 'office',
      'pptx' => 'office',
      'xls' => 'office',
      'xlsx' => 'office',
      'jpg' => 'image',
      'jpeg' => 'image',
      'png' => 'image',
      'gif' => 'image'
    ]; 

    // ถ้าไม่มีในรายการ ให้ return 'other'
    return isset($types[$file_extension]) ? $types[$file_extension] : 'other';
  }
}

// if (!function_exists('site_wpsize_url')) {
//     function site_wpsize_url($folder = '', $string = '', $event = "w800")
//     {
//         $response = site_url("uploads/$folder/" . $string);
//         if (ENVIRONMENT == "production") {
//             $response = site_url("wp-size/$event/uploads/$folder/" . $string);
//         }

//         return $response;
//     }
// }

// if (!function_exists('site_wpsize_subfolder_url')) {
//     function site_wpsize_subfolder_url($folder = '', $id = '', $url = '', $event = "w800")
//     {
//         $subfolder = gen_folder($id);

//         $response = site_url("uploads/$folder/$subfolder/$url");
//         if (ENVIRONMENT == "production") {
//             $response = site_url("wp-size/$event/uploads/$folder/$subfolder/$url");
//         }

//         return $response;
//     }
// }

if (!function_exists('gen_folder')) {
  function gen_folder($id, $string = '')
  {
    $response = $id . '-' . md5($id);
    if ($string != "") {
      $response = $string;
    }

    return $response;
  }
}

// if (!function_exists('_file_exists')) {
//     function _file_exists($folder = '', $string = '')
//     {
//         if (($string != "") && (file_exists(FCPATH . "uploads/$folder/$string"))) {
//             return true;
//         } else {
//             return false;
//         }
//     }
// }

// if (!function_exists('_file_exists_subfolder')) {
//     function _file_exists_subfolder($folder = '', $id, $string = '')
//     {
//         $subfolder = gen_folder($id);
//         if (($string != "") && (file_exists(FCPATH . "uploads/$folder/$subfolder/$string"))) {
//             return true;
//         } else {
//             return false;
//         }
//     }
// }

if (!function_exists('ic_image')) {
  function ic_image($name = "", $width = "", $height = "")
  {
    if ($width != "" && $height == "") {
      $response = '<img width="' . $width . '" src="' . asset('assets/images/' . $name) . '"/>';
    } elseif ($width == "" && $height != "") {
      $response = '<img height="' . $height . '" src="' . asset('assets/images/' . $name) . '"/>';
    } elseif ($width != "" && $height != "") {
      $response = '<img width="' . $width . '" height="' . $height . '" src="' . asset('assets/images/' . $name) . '"/>';
    } else {
      $response = '<img width="20" height="20" src="' . asset('assets/images/' . $name) . '"/>';
    }

    return $response;
  }
}

if (!function_exists('__searchForId')) {
  function __searchForId($id, $array)
  {
    foreach ($array as $val) {
      if ($val['id'] === $id) {
        return $id;
      }
    }

    return false;
  }
}

if (!function_exists("_jsonUnescapedUnicode")) {
  function _jsonUnescapedUnicode(array $arr)
  {
    return json_encode($arr, JSON_UNESCAPED_UNICODE);
  }
}

if (!function_exists("thaiNum")) {
  function thaiNum($string = 0, $type = "normal")
  {
    $chrarray = str_split($string);
    if ($type == "icon") {
      $num = array(
        '<div class="num-image">๐</div>',
        '<div class="num-image">๑</div>',
        '<div class="num-image">๒</div>',
        '<div class="num-image">๓</div>',
        '<div class="num-image">๔</div>',
        '<div class="num-image">๕</div>',
        '<div class="num-image">๖</div>',
        '<div class="num-image">๗</div>',
        '<div class="num-image">๘</div>',
        '<div class="num-image">๙</div>',
      );
    } else {
      $num = array('๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙');
    }

    $thai = array();
    foreach ($chrarray as $chr) {
      array_push($thai, (is_numeric($chr)) ? $num[intval($chr)] : $chr);
    }

    return implode($thai);
  }
}

if (!function_exists('getDomainName')) {
  function getDomainName($url = '')
  {
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
      return $regs['domain'];
    }
    return FALSE;
  }
}

if (!function_exists('icn_internet_banking')) {
  function icn_internet_banking($name = "")
  {
    $response = "";
    if ($name == "krungsri") {
      $response = '<img width="20" height="20" src="' . asset('assets/images/ic-krungsri.png') . '"/>';
    } elseif ($name == "bualuang") {
      $response = '<img width="20" height="20" src="' . asset('assets/images/ic-bualuang.png') . '"/>';
    } elseif ($name == "ktb") {
      $response = '<img width="20" height="20" src="' . asset('assets/images/ic-ktb.png') . '"/>';
    } elseif ($name == "scb") {
      $response = '<img width="20" height="20" src="' . asset('assets/images/ic-scb.png') . '"/>';
    }

    return $response;
  }
}

if (!function_exists("backSlashNHiperLink")) {
  function backSlashNHiperLink($links)
  {
    $exp = explode("\n", $links);
    if (count($exp) <= 1)
      return "<a class=\"text-decoration-none text-dark\" href=\"$links\">$links</a>";

    $links = [];
    foreach ($exp as $value) {
      $links[] = "<a class=\"text-decoration-none text-dark\" href=\"$value\">$value</a><br>";
    }

    return implode("", $links);
  }
}

if (!function_exists('ckeditor_advanced_url')) {
  function ckeditor_advanced_url($element = '', $quantity = 1): string
  {
    $content = '';
    if ($quantity == 1) {
      $content = '
       <script src="' . asset('assets/plugins/ckfile/ckeditor/ckeditor.js') . '"></script>
       <script src="' . asset('assets/plugins/ckfile/ckfinder/ckfinder.js') . '"></script>
     ';
    }

    $content .= '
     <script>
      CKEDITOR.replace(\'' . $element . '\',{
        editorplaceholder: "Start typing here...",
        on: {
          instanceReady: loadBootstrap,
          mode: loadBootstrap
        },
        toolbar : [
          {
            name: "document",
            items: ["Source", "Undo", "Redo"]
          },
          {
            name: "styles",
            items: ["Styles", "Format"]
          },
          {
            name: "colors",
            items: ["TextColor", "BGColor"]
          },
          {
            name: "basicstyles",
            items: ["Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "CopyFormatting", "RemoveFormat"]
          },
          {
            name: "paragraph",
            items: ["NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-", "BidiLtr", "BidiRtl"]
          },
          {
            name: "links",
            items: ["Link", "Unlink"]
          },
          {
            name: "insert",
            items: ["Image", "Table", "Iframe", "Youtube", "btgrid", "btbutton", "BootstrapTabs", "ckeditorfa"]
          },
          {
            name: "tools",
            items: ["Maximize"]
          },
        ]
      });

      function loadBootstrap(event) {
        if (event.name == "mode" && event.editor.mode == "source")
          return; // Skip loading jQuery and Bootstrap when switching to source mode.

        var jQueryScriptTag = document.createElement("script");
        var bootstrapScriptTag = document.createElement("script");

        jQueryScriptTag.src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js";
        bootstrapScriptTag.src = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js";

        var editorHead = event.editor.document.$.head;

        editorHead.appendChild(jQueryScriptTag);
        jQueryScriptTag.onload = function () {
          editorHead.appendChild(bootstrapScriptTag);
        };
      }
     </script>
   ';

    return $content;
  }
}

if (!function_exists('limitText')) {
  function limitText($string = '', $limit = 200)
  {
    $string = strip_tags($string);
    $string = \Str::limit($string, $limit, ' (...)');
    $string = trim(str_replace('&nbsp;', '', $string));
    $string = ($string == '') ? '-' : $string;

    return $string;
  }
}

