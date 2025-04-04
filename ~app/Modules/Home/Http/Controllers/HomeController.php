<?php

namespace Modules\Home\Http\Controllers;

use App\Http\Controllers\BaseViewController;
use Modules\Banner\Repositories\BannerRepository;
use Modules\Banner\Repositories\BannerSmallRepository;
use Modules\Book\Repositories\BookRepository;
use Modules\Box\Repositories\BoxRepository;
use Modules\Gallery\Repositories\GalleryRepository;
use Modules\Menu\Repositories\MenusideRepository;
use Modules\News\Repositories\NewsRepository;
use Modules\TiktokVideo\Entities\TiktokVideoModel;
use Modules\Video\Repositories\VideoRepository;
use Modules\Webboard\Repositories\WebboardRepository;

class HomeController extends BaseViewController
{
    protected $banner;
    protected $book;
    protected $box;
    protected $gallery;
    protected $news;
    protected $webboard;
    protected $video;

    public function __construct(BannerRepository $banner, BannerSmallRepository $bannerSmall, BookRepository $book, BoxRepository $box, GalleryRepository $gallery, MenusideRepository $menuSide, NewsRepository $news, WebboardRepository $webboard, VideoRepository $video)
    {
        $this->banner = $banner;
        // $this->bannerSmall = $bannerSmall;
        $this->book = $book;
        $this->box = $box;
        $this->gallery = $gallery;
        // $this->menuSide = $menuSide;
        $this->news = $news;
        $this->webboard = $webboard;
        $this->video = $video;

        $this->init([
            'body' => [
                'title' => 'หน้าแรก',
                'description' => 'หน้าแรก',
            ],
        ]);
    }

    public function index()
    {
        $data['banners'] = $this->banner->limit(
            [
                'sort' => 'ASC',
                'id' => 'DESC',
            ],
            10,
        );
        // $data['banner_smalls'] = $this->bannerSmall->limit(
        //     [
        //         'sort' => 'ASC',
        //         'id' => 'DESC',
        //     ],
        //     10,
        // );
        $data['lasted_news'] = $this->news->getLastedNewOfActivity([], 4);
        $data['news'] = $this->news->getNewOfTypeAll([], 8);
        $data['news_purchase'] = $this->news->getNewOfTypePurchase([]);
        $data['news_purchase_summary'] = $this->news->getNewOfTypePurchaseSummary([]);
        $data['galleries'] = $this->gallery->limit(
            [
                'sort' => 'ASC',
                'id' => 'DESC',
            ],
            6,
        );
        $data['books'] = $this->book->limit([], 10);
        //    $data['menu_side'] = $this->menuSide->prepare();
        $data['webboard'] = $this->webboard->limit([], 6);
        $data['box'] = $this->box->get(1);
        $data['videos'] = $this->video->limit(
            [
                'sort' => 'ASC',
                'id' => 'DESC',
            ],
            3,
        );

        $data['global_menus'] = [
            [
                'title' => 'กลุ่มอำนวยการ',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/folder.png'),
                'url' => '/personal/กลุ่มอำนวยการ',
            ],
            [
                'title' => 'กลุ่มบริหารงานบุคคล',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/hr.png'),
                'url' => '/personal/กลุ่มบริหารงานบุคคล',
            ],
            [
                'title' => 'กลุ่มนโยบายและแผน',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/insurance.png'),
                'url' => '/personal/กลุ่มนโยบายและแผน',
            ],
            [
                'title' => 'กลุ่มพัฒนาการศึกษา',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/students.png'),
                'url' => '/personal/กลุ่มพัฒนาการศึกษา',
            ],
            [
                'title' => 'กลุ่มนิเทศติดตามและประเมินผล',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/search.png'),
                'url' => '/personal/กลุ่มนิเทศติดตามและประเมินผล',
            ],
            [
                'title' => 'หน่วยตรวจสอบภายใน',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/evaluation.png'),
                'url' => '/personal/หน่วยตรวจสอบภายใน',
            ],
            [
                'title' => 'กลุ่มลูกเสือยุวกาชาดและกิจการนักศึกษา',
                'sub_title' => '',
                'cover' => asset('assets/images/global-menu/lifestyle.png'),
                'url' => '/personal/กลุ่มลูกเสือยุวกาชาดและกิจการนักเรียน',
            ],
        ];

        $data['assessments'] = [
            [
                'title' => 'แบบสอบถามความพึงพอใจเว็บไซต์',
                'cover' => asset('assets/images/assessment/link-1.png'),
                'url' => '#',
            ],
            [
                'title' => 'แบบสอบถามออนไลน์',
                'cover' => asset('assets/images/assessment/link-2.png'),
                'url' => '#',
            ],
            [
                'title' => 'แบบสอบถามความพึงพอใจการให้บริหาร',
                'cover' => asset('assets/images/assessment/link-3.png'),
                'url' => '#',
            ],
            [
                'title' => 'Q & A <br>ถาม-ตอบ ปัญหา',
                'cover' => asset('assets/images/assessment/link-4.png'),
                'url' => '#',
            ],
            [
                'title' => 'รับฟังความคิดเห็น',
                'cover' => asset('assets/images/assessment/link-5.png'),
                'url' => '#',
            ],
            [
                'title' => 'ช่องทางการร้องเรียน',
                'cover' => asset('assets/images/assessment/link-6.png'),
                'url' => '#',
            ],
        ];

        $data['outerlinks'] = [
            [
                'title' => 'My office',
                'description' => 'ระบบสำนักงานอิเล็กทรอนิกส์',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'http://61.7.213.234/myoffice/2568/',
            ],
            [
                'title' => 'eMENSCR',
                'description' => 'ระบบติดตามและประเมิน<br>ผลแห่งชาติ',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'https://emenscr.nesdc.go.th/auth/realms/master/protocol/openid-connect/auth?client_id=emenscr-nesdc&redirect_uri=https%3A%2F%2Femenscr.nesdc.go.th%2F&state=e65a1ff0-689b-490b-82f0-e6389010743c&response_mode=fragment&response_type=code&scope=openid&nonce=7bd725d0-32e1-47eb-85cd-84d8fc90b676',
            ],
            [
                'title' => 'BPSI',
                'description' => 'สำนักงานปลัดกระทรวง<br>ศึกษาธิการ',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'http://bpsi.moe.go.th/',
            ],
            [
                'title' => 'สลิปเงินเดือน',
                'description' => 'สำนักงานปลัดกระทรวง<br>ศึกษาธิการ',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'http://203.159.170.13/eoffice/',
            ],
            [
                'title' => 'Scout Info',
                'description' => 'ระบบสารสนเทศ<br>สำนักการลูกเสือ',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'https://bureausrs.moe.go.th/',
            ],
            [
                'title' => 'NISPA',
                'description' => 'ระบบสารสนเทศ<br>ยาเสพติดจังหวัด',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'https://nispa.nccd.go.th/2013/',
            ],
            [
                'title' => 'BRSS',
                'description' => 'ระบบติดตามตรวจสอบ<br>งบประมาณด้วยตัวเอง',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'http://61.7.213.234/brss/',
            ],
            [
                'title' => 'CATAS',
                'description' => 'ระบบดูแลและติดตาม<br>การใช้สารเสพติดในสถานศึกษา',
                'logo' => asset('assets/images/outlink/template.png'),
                'link' => 'https://www.catas.in.th/home/login',
            ],
        ];

        $data['stylesheets'] = [
            asset('assets/plugins/FlexSlider/css/flexslider.min.css'),
            asset('assets/plugins/node_modules/owl.carousel/dist/assets/owl.carousel.min.css'),
            asset('assets/plugins/node_modules/owl.carousel/dist/assets/owl.theme.default.min.css'),
            asset('https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css'),
            asset('assets/common/css/top-picture.css'),
            asset('assets/common/css/home.css')
        ];
        $data['scripts'] = [
            [
                'link' => asset('assets/plugins/FlexSlider/js/jquery.flexslider-min.js'),
                'defer' => 'defer',
            ],
            asset('assets/plugins/node_modules/owl.carousel/dist/owl.carousel.min.js'),
            asset('https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js'),
            asset('assets/common/js/index.js'),
            asset('assets/common/js/home.js'),
        ];

        // ดึงข้อมูลวิดีโอ TikTok ล่าสุด 6 รายการ
        $data['tiktok_videos'] = TiktokVideoModel::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->render('home::index', $data);
    }
}
