<?php

namespace Modules\Assessment\Repositories;

use App\CustomClass\ItopCyberUpload;
use Storage;
use Modules\Assessment\Entities\Lpa;
use App\Repositories\BaseRepository;

class LpaRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Lpa::class,
      'uri_prefix' => 'lpa'
    ]);
  }

  public function lpasOfYear($year)
  {
    $q = $this->classModelName::query();
    $q->where('year', $year);

    return $q->first();
  }

  public function lpasDistinctYear()
  {
    $q = $this->classModelName::distinct();
    $result = $q->orderByDesc('year')->get('year')->toArray();

    return array_column($result, 'year');
  }

  public function ctlUploadSliderOption()
  {
    return [
      'crop' => [
        'width' => 400,
        'height' => 266,
        'watermark' => env('ITOPCY_UPLOAD_WATEMARK', "NAKOMAH STUDIO")
      ]
    ];
  }

  public function ctlUploadSlider($file, $id)
  {
    $exp = explode("\\", $this->classModelName);
    $folder = strtolower(end($exp)) . '/' . gen_folder($id);

    $options = $this->ctlUploadSliderOption();

    return ItopCyberUpload::upload(storage_path('app/public/' . $folder), $file, $options);
  }

  public function update($request, $id)
  {
    $result = $this->classModelName::findOrFail($id)->toArray();
    $files = $result['lpas'][$request['i']]['files'];

    if (isset($request['file']) && isset($request['_token'])) {
      $response = ($files != null) ? json_decode($files, true) : [];

      foreach ($request['file'] as $value) {
        if ($value != '') {
          $requestFile = [
            'name' => $value->getClientOriginalName(),
            'type' => $value->getClientMimeType(),
            'tmp_name' => $value->getPathName(),
            'error' => $value->getError(),
            'size' => $value->getSize()
          ];

          $requestFile['name_uploaded'] = $this->ctlUploadSlider($requestFile, $result['id']);
          $response[count($response)] = $requestFile;
        }
      }

      $result['lpas'][$request['i']]['files'] = $response;
      $this->classModelName::findOrFail($id)->update($result);
    }

    return $result;
  }

  public function redirectToList()
  {
    return redirect()->route('admin.' . strtolower($this->uriPrefix) . '.index');
  }
}
