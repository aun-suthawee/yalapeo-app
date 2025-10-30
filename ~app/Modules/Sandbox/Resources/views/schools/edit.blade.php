@extends('sandbox::layouts.master')

@section('title', 'แก้ไขข้อมูลโรงเรียน')

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-form.css') }}">
@endsection

@section('content')
    <div class="school-form-container">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <h1 class="page-title">แก้ไขข้อมูลโรงเรียน</h1>
                <div class="breadcrumb">
                    <a href="{{ route('sandbox.dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('sandbox.schools.index') }}">จัดการโรงเรียน</a>
                    <span>/</span>
                    <span>แก้ไข {{ $school->name }}</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <form action="{{ route('sandbox.schools.update', $school->id) }}" method="POST" id="schoolForm">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="form-section">
                        <h2 class="section-title">ข้อมูลพื้นฐาน</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="school_code" class="form-label required">รหัสสถานศึกษา</label>
                                <input type="text" id="school_code" name="school_code"
                                    class="form-control @error('school_code') is-invalid @enderror" 
                                    value="{{ old('school_code', $school->school_code) }}"
                                    placeholder="เช่น 1095010001" required maxlength="50">
                                @error('school_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">รหัสสถานศึกษา 10 หลักหรือรหัสอื่นที่ไม่ซ้ำกัน</small>
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-label required">ชื่อโรงเรียน</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $school->name) }}" placeholder="เช่น โรงเรียนอิสลาฮุดดีนวิทยา"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="school_type" class="form-label">ประเภท/ขนาดสถานศึกษา</label>
                                <select id="school_type" name="school_type"
                                    class="form-control @error('school_type') is-invalid @enderror">
                                    <option value="">-- เลือกประเภท/ขนาด --</option>
                                    <option value="ขนาดเล็ก" {{ old('school_type', $school->school_type) == 'ขนาดเล็ก' ? 'selected' : '' }}>ขนาดเล็ก (นักเรียนไม่เกิน 120 คน)</option>
                                    <option value="ขนาดกลาง" {{ old('school_type', $school->school_type) == 'ขนาดกลาง' ? 'selected' : '' }}>ขนาดกลาง (121-600 คน)</option>
                                    <option value="ขนาดใหญ่" {{ old('school_type', $school->school_type) == 'ขนาดใหญ่' ? 'selected' : '' }}>ขนาดใหญ่ (601-1,500 คน)</option>
                                    <option value="ขนาดใหญ่พิเศษ" {{ old('school_type', $school->school_type) == 'ขนาดใหญ่พิเศษ' ? 'selected' : '' }}>ขนาดใหญ่พิเศษ (มากกว่า 1,500 คน)</option>
                                </select>
                                @error('school_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Affiliation Information -->
                    <div class="form-section">
                        <h2 class="section-title">ข้อมูลสังกัด</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ministry_affiliation" class="form-label">สังกัดกระทรวง</label>
                                <select id="ministry_affiliation" name="ministry_affiliation"
                                    class="form-control @error('ministry_affiliation') is-invalid @enderror">
                                    <option value="">-- เลือกสังกัดกระทรวง --</option>
                                    <option value="กระทรวงศึกษาธิการ" {{ old('ministry_affiliation', $school->ministry_affiliation) == 'กระทรวงศึกษาธิการ' ? 'selected' : '' }}>กระทรวงศึกษาธิการ</option>
                                    <option value="สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน" {{ old('ministry_affiliation', $school->ministry_affiliation) == 'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน' ? 'selected' : '' }}>สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน</option>
                                    <option value="กระทรวงมหาดไทย" {{ old('ministry_affiliation', $school->ministry_affiliation) == 'กระทรวงมหาดไทย' ? 'selected' : '' }}>กระทรวงมหาดไทย</option>
                                </select>
                                @error('ministry_affiliation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="bureau_affiliation" class="form-label">สังกัดสำนักงาน/กรม</label>
                                <select id="bureau_affiliation" name="bureau_affiliation"
                                    class="form-control @error('bureau_affiliation') is-invalid @enderror">
                                    <option value="">-- เลือกสังกัดสำนักงาน/กรม --</option>
                                    <option value="สำนักงานคณะกรรมการการศึกษาขั้นพื้นฐาน (สพฐ.)" {{ old('bureau_affiliation', $school->bureau_affiliation) == 'สำนักงานคณะกรรมการการศึกษาขั้นพื้นฐาน (สพฐ.)' ? 'selected' : '' }}>สำนักงานคณะกรรมการการศึกษาขั้นพื้นฐาน (สพฐ.)</option>
                                    <option value="สำนักงานการศึกษาเอกชนจังหวัดยะลา" {{ old('bureau_affiliation', $school->bureau_affiliation) == 'สำนักงานการศึกษาเอกชนจังหวัดยะลา' ? 'selected' : '' }}>สำนักงานการศึกษาเอกชนจังหวัดยะลา</option>
                                    <option value="กรมส่งเสริมการปกครองท้องถิ่น" {{ old('bureau_affiliation', $school->bureau_affiliation) == 'กรมส่งเสริมการปกครองท้องถิ่น' ? 'selected' : '' }}>กรมส่งเสริมการปกครองท้องถิ่น</option>
                                    <option value="องค์การบริหารส่วนจังหวัดยะลา" {{ old('bureau_affiliation', $school->bureau_affiliation) == 'องค์การบริหารส่วนจังหวัดยะลา' ? 'selected' : '' }}>องค์การบริหารส่วนจังหวัดยะลา</option>
                                </select>
                                @error('bureau_affiliation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="education_area" class="form-label">เขตพื้นที่การศึกษา</label>
                                <input type="text" id="education_area" name="education_area"
                                    class="form-control @error('education_area') is-invalid @enderror"
                                    value="{{ old('education_area', $school->education_area) }}"
                                    placeholder="เช่น สพป.ยะลา เขต 1">
                                @error('education_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="department" class="form-label required">สังกัด</label>
                                <select id="department" name="department"
                                    class="form-control @error('department') is-invalid @enderror" required>
                                    <option value="">-- เลือกสังกัด --</option>
                                    <option value="สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1"
                                        {{ old('department', $school->department) == 'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1' ? 'selected' : '' }}>
                                        สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1</option>
                                    <option value="สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2"
                                        {{ old('department', $school->department) == 'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2' ? 'selected' : '' }}>
                                        สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2</option>
                                    <option value="สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3"
                                        {{ old('department', $school->department) == 'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3' ? 'selected' : '' }}>
                                        สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3</option>
                                    <option value="สำนักงานส่งเสริมการศึกษาเอกชน"
                                        {{ old('department', $school->department) == 'สำนักงานส่งเสริมการศึกษาเอกชน' ? 'selected' : '' }}>
                                        สำนักงานส่งเสริมการศึกษาเอกชน</option>
                                    <option value="เทศบาลนครยะลา"
                                        {{ old('department', $school->department) == 'เทศบาลนครยะลา' ? 'selected' : '' }}>
                                        เทศบาลนครยะลา</option>
                                    <option value="องค์การบริหารส่วนจังหวัดยะลา"
                                        {{ old('department', $school->department) == 'องค์การบริหารส่วนจังหวัดยะลา' ? 'selected' : '' }}>
                                        องค์การบริหารส่วนจังหวัดยะลา</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Student Statistics -->
                    <div class="form-section">
                        <h2 class="section-title">จำนวนนักเรียน</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="male_students" class="form-label required">นักเรียนชาย</label>
                                <input type="number" id="male_students" name="male_students"
                                    class="form-control @error('male_students') is-invalid @enderror"
                                    value="{{ old('male_students', $school->male_students) }}" min="0" required>
                                @error('male_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="female_students" class="form-label required">นักเรียนหญิง</label>
                                <input type="number" id="female_students" name="female_students"
                                    class="form-control @error('female_students') is-invalid @enderror"
                                    value="{{ old('female_students', $school->female_students) }}" min="0" required>
                                @error('female_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">รวมนักเรียน</label>
                                <input type="text" id="total_students" class="form-control" readonly
                                    value="{{ $school->total_students }}">
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Statistics -->
                    <div class="form-section">
                        <h2 class="section-title">จำนวนครู/บุคลากร</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="male_teachers" class="form-label required">ครูชาย</label>
                                <input type="number" id="male_teachers" name="male_teachers"
                                    class="form-control @error('male_teachers') is-invalid @enderror"
                                    value="{{ old('male_teachers', $school->male_teachers) }}" min="0" required>
                                @error('male_teachers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="female_teachers" class="form-label required">ครูหญิง</label>
                                <input type="number" id="female_teachers" name="female_teachers"
                                    class="form-control @error('female_teachers') is-invalid @enderror"
                                    value="{{ old('female_teachers', $school->female_teachers) }}" min="0" required>
                                @error('female_teachers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">รวมครู</label>
                                <input type="text" id="total_teachers" class="form-control" readonly
                                    value="{{ $school->total_teachers }}">
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="form-section">
                        <h2 class="section-title">ข้อมูลที่ตั้ง <span class="optional">(ไม่บังคับ)</span></h2>

                        <div class="form-group">
                            <label for="address" class="form-label">ที่อยู่</label>
                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                placeholder="ที่อยู่ของโรงเรียน">{{ old('address', $school->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="subdistrict" class="form-label">ตำบล</label>
                                <input type="text" id="subdistrict" name="subdistrict"
                                    class="form-control @error('subdistrict') is-invalid @enderror"
                                    value="{{ old('subdistrict', $school->subdistrict) }}"
                                    placeholder="เช่น สะเตง">
                                @error('subdistrict')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="district" class="form-label">อำเภอ</label>
                                <input type="text" id="district" name="district"
                                    class="form-control @error('district') is-invalid @enderror"
                                    value="{{ old('district', $school->district) }}"
                                    placeholder="เช่น เมืองยะลา">
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="latitude" class="form-label">ละติจูด (Latitude)</label>
                                <input type="text" id="latitude" name="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $school->latitude) }}"
                                    placeholder="เช่น 6.541667"
                                    step="any">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">พิกัดละติจูด (ค่าระหว่าง -90 ถึง 90)</small>
                            </div>

                            <div class="form-group">
                                <label for="longitude" class="form-label">ลองจิจูด (Longitude)</label>
                                <input type="text" id="longitude" name="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $school->longitude) }}"
                                    placeholder="เช่น 101.280556"
                                    step="any">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">พิกัดลองจิจูด (ค่าระหว่าง -180 ถึง 180)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="form-section">
                        <h2 class="section-title">ข้อมูลการติดต่อ <span class="optional">(ไม่บังคับ)</span></h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                <input type="tel" id="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $school->phone) }}" placeholder="073-123456">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $school->email) }}" placeholder="school@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('sandbox.schools.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                        <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> ดูรายละเอียด
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/school-form.js') }}"></script>
@endsection
