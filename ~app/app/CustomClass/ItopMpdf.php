<?php


namespace App\CustomClass;


class ItopMpdf
{
    protected $config;

    function __construct(array $config = [])
    {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        if (empty($config)) {
            $config = [
                'format' => 'A4',
                'fontDir' => array_merge($fontDirs, [
                    public_path('assets/PdfFonts')
                ]),
                'fontdata' => $fontData + [
                        "angsana" => [
                            'R' => "angsau.ttf",
                            'B' => "angsaub.ttf",
                            'useOTL' => 0xFF,
                            'useKashida' => 75
                        ],
                        "thsarabun" => [
                            'R' => "THSarabunNew.ttf",
                            'B' => "THSarabunNew Bold.ttf",
                            'useOTL' => 0xFF,
                            'useKashida' => 75
                        ]
                    ],
                'default_font' => 'thsarabun',
                'default_font_size' => 16,
                'tempDir' => '/tmp',
//                'margin_left' => 10,
//                'margin_right' => 10,
//                'margin_bottom' => 20,
//                'margin_top' => 18,
//                'margin_header' => 10,
//                'margin_footer' => 10
            ];
        }

        $this->config = $config;
    }

    public function handle()
    {
        return new \Mpdf\Mpdf($this->config);
    }
}