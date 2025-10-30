<?php

namespace Modules\Sandbox\Services;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportService
{
    /**
     * Export data to CSV format
     * 
     * @param Collection $data
     * @param array $headers
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCSV(Collection $data, array $headers, string $filename = 'export.csv')
    {
        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($file, $headers);
            
            // Write data rows
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Export data to Excel format (XLSX)
     * 
     * @param Collection $data
     * @param array $headers
     * @param string $filename
     * @param string $sheetName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToExcel(Collection $data, array $headers, string $filename = 'export.xlsx', string $sheetName = 'Sheet1')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetName);
        
        // Set headers with styling
        $columnLetter = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnLetter . '1', $header);
            
            // Style header
            $sheet->getStyle($columnLetter . '1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
            
            // Auto-size column
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            
            $columnLetter++;
        }
        
        // Write data rows
        $rowNumber = 2;
        foreach ($data as $row) {
            $columnLetter = 'A';
            foreach ($row as $value) {
                $sheet->setCellValue($columnLetter . $rowNumber, $value);
                
                // Add borders to data cells
                $sheet->getStyle($columnLetter . $rowNumber)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                $columnLetter++;
            }
            $rowNumber++;
        }
        
        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        // Create writer and stream response
        $writer = new Xlsx($spreadsheet);
        
        return response()->stream(
            function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }

    /**
     * Prepare schools data for export
     * 
     * @param Collection $schools
     * @return array
     */
    public function prepareSchoolsData(Collection $schools): array
    {
        $headers = [
            'รหัสโรงเรียน',
            'ชื่อโรงเรียน',
            'สังกัด',
            'กระทรวง',
            'สำนักงาน/กรม',
            'เขตพื้นที่การศึกษา',
            'ประเภทโรงเรียน',
            'นักเรียนชาย',
            'นักเรียนหญิง',
            'รวมนักเรียน',
            'ครูชาย',
            'ครูหญิง',
            'รวมครู',
            'อำเภอ',
            'ตำบล',
            'โทรศัพท์',
            'อีเมล',
        ];

        $data = $schools->map(function($school) {
            return [
                $school->school_code ?? '-',
                $school->name,
                $school->department ?? '-',
                $school->ministry_affiliation ?? '-',
                $school->bureau_affiliation ?? '-',
                $school->education_area ?? '-',
                $school->school_type ?? '-',
                $school->male_students ?? 0,
                $school->female_students ?? 0,
                $school->total_students ?? 0,
                $school->male_teachers ?? 0,
                $school->female_teachers ?? 0,
                $school->total_teachers ?? 0,
                $school->district ?? '-',
                $school->subdistrict ?? '-',
                $school->phone ?? '-',
                $school->email ?? '-',
            ];
        });

        return [
            'headers' => $headers,
            'data' => $data,
        ];
    }

    /**
     * Prepare academic results data for export
     * 
     * @param Collection $results
     * @return array
     */
    public function prepareAcademicResultsData(Collection $results): array
    {
        $headers = [
            'ปีการศึกษา',
            'โรงเรียน',
            'สังกัด',
            'อำเภอ',
            'เขตพื้นที่การศึกษา',
            'มี NT',
            'คะแนน NT คณิต',
            'คะแนน NT ภาษาไทย',
            'เฉลี่ย NT',
            'มี RT',
            'คะแนน RT อ่านออกเสียง',
            'คะแนน RT อ่านรู้เรื่อง',
            'เฉลี่ย RT',
            'มี O-NET',
            'คะแนน O-NET คณิต',
            'คะแนน O-NET ภาษาไทย',
            'คะแนน O-NET อังกฤษ',
            'คะแนน O-NET วิทยาศาสตร์',
            'เฉลี่ย O-NET',
            'สถานะส่งข้อมูล',
            'วันที่ส่งข้อมูล',
        ];

        $data = $results->map(function($result) {
            return [
                $result->academic_year,
                $result->school->name ?? '-',
                $result->school->department ?? '-',
                $result->school->district ?? '-',
                $result->school->education_area ?? '-',
                $result->has_nt_test ? 'มี' : 'ไม่มี',
                $result->nt_math_score ?? '-',
                $result->nt_thai_score ?? '-',
                $result->nt_average ?? '-',
                $result->has_rt_test ? 'มี' : 'ไม่มี',
                $result->rt_reading_score ?? '-',
                $result->rt_comprehension_score ?? '-',
                $result->rt_average ?? '-',
                $result->has_onet_test ? 'มี' : 'ไม่มี',
                $result->onet_math_score ?? '-',
                $result->onet_thai_score ?? '-',
                $result->onet_english_score ?? '-',
                $result->onet_science_score ?? '-',
                $result->onet_average ?? '-',
                $result->submitted_at ? 'ส่งแล้ว' : 'ยังไม่ส่ง',
                $result->submitted_at ? $result->submitted_at->format('d/m/Y H:i') : '-',
            ];
        });

        return [
            'headers' => $headers,
            'data' => $data,
        ];
    }
}
