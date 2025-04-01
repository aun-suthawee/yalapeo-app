<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use App\Http\Controllers\BaseManageController;
use Modules\News\Repositories\NewsRepository;
use Modules\Page\Repositories\PageRepository;

class DashboardController extends BaseManageController
{
  protected $news;
  protected $page;

  public function __construct(
    NewsRepository $news,
    PageRepository $page
  ) {
    $this->news = $news;
    $this->page = $page;

    $this->init([
      'body' => [
        'app_title' => [
          'h1' => '<i class="fas fa-tachometer-alt"></i> Dashboard',
          'p' => 'ภาพรวมของระบบ'
        ],
      ],
      // 'permission_prefix' => 'dashboard'
    ]);
  }

  public function index()
  {
    $data['news_lists'] = $this->news->limit([], 10);
    $data['page_lists'] = $this->page->limit([], 10);

    return $this->render('dashboard::index', $data);
  }
}
