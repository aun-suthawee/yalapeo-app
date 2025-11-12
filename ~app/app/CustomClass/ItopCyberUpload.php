<?php

namespace App\CustomClass;

use Exception;
use Verot\Upload\Upload;

class ItopCyberUpload
{
  public function __construct()
  {
  }

  public static function upload($path, $files, $options = [])
  {
    // $options = [
    //     'thumbnail' => [
    //         'width' => 100,
    //         'height' => 100,
    //         'watermark' => "watermark_01"
    //     ],
    //     'resize' => [
    //         'width' => 100,
    //         'height' => 100,
    //         'watermark' => "watermark_01"
    //     ],
    //     'crop' => [
    //         'width' => 640,
    //         'height' => 480,
    //         'watermark' => "watermark_01"
    //     ]
    // ];

    try {
      $response = '';
      $handle = new Upload($files);
      if ($handle->uploaded) {
        $file_name_to_store = $handle->file_src_name_body . '_' . uniqid();
        $handle->file_new_name_body = $file_name_to_store;
        $handle->image_convert = 'webp';
        $handle->process($path);
        if ($handle->processed) {
          $response = $handle->file_dst_name;
          // $handle->clean();

          if (!empty($options)) {
            if (isset($options['thumbnail'])) {
              $handle = new upload($files);
              if ($handle->uploaded) {
                if (isset($options['thumbnail']['watermark']) && $options['thumbnail']['watermark'] != "") {
                  $handle->image_text = $options['thumbnail']['watermark'];
                  $handle->image_text_opacity = 28;
                  $handle->image_text_size = 24;
                  // $handle->image_text_font = "./assets/webfonts/CSChatThai/CSChatThai.ttf";
                  // $handle->image_text_background = '#428bc9';
                  // $handle->image_text_x = -15;
                  $handle->image_text_y = -40;
                  $handle->image_text_padding = 5;
                }

                $handle->file_new_name_body = $file_name_to_store;

                $handle->image_resize = true;
                if (
                  ($options['watermark']['width'] === $handle->image_src_x) &&
                  ($options['watermark']['height'] === $handle->image_src_y)
                ) {
                  $handle->image_resize = false;
                }

                $handle->image_x = $options['thumbnail']['width'];
                $handle->image_ratio_y = true;
                $handle->image_convert = 'webp';
                $handle->image_background_color = '#000000';
                $handle->process($path . "/thumbnail/");
                if (!$handle->processed) {
                  $response = 'error : ' . $handle->error;
                } else {
                  $response = $handle->file_dst_name;
                  // $handle->clean();
                }
              }
            }

            if (isset($options['resize'])) {
              $handle = new upload($files);
              if ($handle->uploaded) {
                if (isset($options['resize']['watermark']) && $options['resize']['watermark'] != "") {
                  $handle->image_text = $options['resize']['watermark'];
                  $handle->image_text_opacity = 28;
                  $handle->image_text_size = 24;
                  // $handle->image_text_font = "./assets/webfonts/CSChatThai/CSChatThai.ttf";
                  // $handle->image_text_background = '#428bc9';
                  // $handle->image_text_x = -15;
                  $handle->image_text_y = -40;
                  $handle->image_text_padding = 5;
                }

                $handle->file_new_name_body = $file_name_to_store;

                $handle->image_resize = true;
                if (
                  ($options['resize']['width'] === $handle->image_src_x) &&
                  ($options['resize']['height'] === $handle->image_src_y)
                ) {
                  $handle->image_resize = false;
                }

                $handle->image_x = $options['resize']['width'];
                $handle->image_ratio_y = true;
                $handle->image_convert = 'webp';
                $handle->image_background_color = '#000000';
                $handle->process($path . "/resize/");
                if (!$handle->processed) {
                  $response = 'error : ' . $handle->error;
                } else {
                  $response = $handle->file_dst_name;
                  // $handle->clean();
                }
              }
            }

            if (isset($options['crop'])) {
              $handle = new upload($files);
              if ($handle->uploaded) {
                // dd($handle);
                if (isset($options['crop']['watermark']) && $options['crop']['watermark'] != "") {
                  $handle->image_text = $options['crop']['watermark'];
                  $handle->image_text_opacity = 28;
                  $handle->image_text_size = 24;
                  // $handle->image_text_font = "./assets/webfonts/CSChatThai/CSChatThai.ttf";
                  // $handle->image_text_background = '#428bc9';
                  // $handle->image_text_x = -15;
                  $handle->image_text_y = -40;
                  $handle->image_text_padding = 5;
                }

                $handle->file_new_name_body = $file_name_to_store;

                $handle->image_resize = true;
                if (
                  ($options['crop']['width'] === $handle->image_src_x) &&
                  ($options['crop']['height'] === $handle->image_src_y)
                ) {
                  $handle->image_resize = false;
                }

                $handle->image_ratio_crop = true;
                $handle->image_convert = 'webp';
                $handle->image_x = $options['crop']['width'];
                $handle->image_y = $options['crop']['height'];
                $handle->process($path . "/crop/");
                if (!$handle->processed) {
                  $response = 'error : ' . $handle->error;
                } else {
                  $response = $handle->file_dst_name;
                  // $handle->clean();
                }
              }
            }
          }

          $handle->clean();
        } else {
          echo 'error : ' . $handle->error;
        }
      }
    } catch (Exception $ex) {
      //dd ($ex->getMessage());
    }

    return $response;
  }
}