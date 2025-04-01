<?php


namespace Modules\Setting\Repositories;

use App\Repositories\BaseRepository;
use Modules\Setting\Entities\Meta;

class MetaRepository extends BaseRepository
{
  public function __construct()
  {
    $this->init([
      'class_model_name' => Meta::class
    ]);
  }

  public function redirectToList()
  {
    return redirect()->route('admin.setting.meta.index');
  }
}
