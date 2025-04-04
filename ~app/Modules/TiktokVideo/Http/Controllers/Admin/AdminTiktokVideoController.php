<?php

namespace Modules\TiktokVideo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\TiktokVideo\Entities\TiktokVideoModel;

class AdminTiktokVideoController extends Controller
{
    public function index()
    {
        $videos = TiktokVideoModel::orderBy('created_at', 'desc')->paginate(15);
        
        // สร้างข้อมูล $body สำหรับ layout
        $body = [
            'title' => 'รายการวิดีโอ TikTok',
            'app_title' => [
                'h1' => '<i class="fab fa-tiktok"></i> รายการวิดีโอ TikTok',
                'p' => 'จัดการวิดีโอ TikTok ทั้งหมด'
            ]
        ];
        
        return view('tiktokvideo::admin.index', compact('videos', 'body'));
    }

    public function create()
    {
        // สร้างข้อมูล $body สำหรับ layout
        $body = [
            'title' => 'เพิ่มวิดีโอ TikTok',
            'app_title' => [
                'h1' => '<i class="fab fa-tiktok"></i> เพิ่มวิดีโอ TikTok',
                'p' => 'เพิ่มวิดีโอ TikTok ใหม่เข้าสู่ระบบ'
            ]
        ];
        
        return view('tiktokvideo::admin.create', compact('body'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            // เพิ่มกฎตรวจสอบอื่นๆ ตามต้องการ
        ]);

        // ดึง video_id จาก URL TikTok
        $videoId = $this->extractTikTokVideoId($request->url);
        
        if (!$videoId) {
            return redirect()->back()->withErrors(['url' => 'ไม่สามารถดึง video ID จาก URL นี้ได้'])->withInput();
        }
        
        // แปลง title เป็น slug โดยแทนที่ช่องว่างด้วยเครื่องหมายขีด (-)
        $slug = str_replace(' ', '-', $request->title);
        
        // บันทึกข้อมูล
        $video = new TiktokVideoModel();
        $video->title = $request->title;
        $video->url = $request->url;
        $video->video_id = $videoId;
        $video->detail = $request->detail;
        $video->slug = $slug;
        $video->is_active = 1; // กำหนดให้เป็น 1 (active) โดยอัตโนมัติ
        $video->save();
        
        return redirect()->route('admin.tiktokvideo.index')
            ->with('success', 'วิดีโอ TikTok ถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $video = TiktokVideoModel::findOrFail($id);
        
        // สร้างข้อมูล $body สำหรับ layout
        $body = [
            'title' => 'แก้ไขวิดีโอ TikTok',
            'app_title' => [
                'h1' => '<i class="fab fa-tiktok"></i> แก้ไขวิดีโอ TikTok',
                'p' => 'แก้ไขข้อมูลวิดีโอ TikTok'
            ]
        ];
        
        return view('tiktokvideo::admin.edit', compact('video', 'body'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            // เพิ่มกฎตรวจสอบอื่นๆ ตามต้องการ
        ]);
        
        $video = TiktokVideoModel::findOrFail($id);
        
        // ดึง video_id จาก URL TikTok
        $videoId = $this->extractTikTokVideoId($request->url);
        
        if (!$videoId) {
            return redirect()->back()->withErrors(['url' => 'ไม่สามารถดึง video ID จาก URL นี้ได้'])->withInput();
        }
        
        // หากมีการเปลี่ยนชื่อเรื่อง ให้อัปเดต slug ด้วย
        if ($video->title != $request->title) {
            // แปลง title เป็น slug โดยแทนที่ช่องว่างด้วยเครื่องหมายขีด (-)
            $video->slug = str_replace(' ', '-', $request->title);
        }
        
        // อัปเดตข้อมูลอื่นๆ
        $video->title = $request->title;
        $video->url = $request->url;
        $video->video_id = $videoId;
        $video->detail = $request->detail;
        $video->is_active = 1; // กำหนดให้เป็น 1 (active) ทุกครั้งที่อัปเดต
        $video->save();
        
        return redirect()->route('admin.tiktokvideo.index')
            ->with('success', 'วิดีโอ TikTok ถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $video = TiktokVideoModel::findOrFail($id);
        $video->delete();
        
        return redirect()->route('admin.tiktokvideo.index')
            ->with('success', 'วิดีโอ TikTok ถูกลบเรียบร้อยแล้ว');
    }

    /**
     * ดึง video ID จาก URL TikTok
     * 
     * @param string $url
     * @return string|null
     */
    private function extractTikTokVideoId($url)
    {
        // รูปแบบ URL: https://www.tiktok.com/@username/video/1234567890123456789
        if (preg_match('/tiktok\.com\/.*\/video\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // รูปแบบ URL: https://vt.tiktok.com/1234567890123456789
        if (preg_match('/vt\.tiktok\.com\/(\w+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // รูปแบบ URL: https://m.tiktok.com/v/1234567890123456789.html
        if (preg_match('/tiktok\.com\/v\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
