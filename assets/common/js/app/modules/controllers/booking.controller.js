'use strict'

// $.validator.addMethod('filesize', function (value, element, arg) {
//     return this.optional(element) || (element.files[0].size <= param)
// }, 'File size must be less than {0}');

app.controller("bookingController", function ($scope, $window, BASE_URL, bookingService) {
    $scope.registerValidationOptions = {
        rules: {
            government_service: "required",
            position: "required",
            booker: "required",
            department: "required",
            tel: "required",
            objective: "required",
            from: "required",
            to: "required",
            people_count: {
                required: true,
                number: true
            },
            sing_confirm: "required",
            'rooms[]': {
                required: true,
                minlength: 1
            },
            attach: {
                extension: "pdf|doc|docx",
            }
        },
        messages: {
            government_service: "กรุณาระบุส่วนราชการ",
            position: "กรุณาระบุตำแหน่ง",
            booker: "กรุณาระบุชื่อ-สกุล",
            department: "กรุณาระบุชื่อหน่วยงาน",
            tel: "กรุณาระบุหมายเลขโทรศัพท์",
            room: "กรุณาระบุห้อง",
            visual_equipment: "กรุณาระบุอุปกรณ์ที่ต้องการใช้",
            objective: "กรุณาระบุวัตถุประสงค์",
            from: "กรุณาระบุวันที่เริ่ม",
            to: "กรุณาระบุวันที่สิ้นสุด",
            people_count: {
                required: "กรุณาระบุจำนวนคน",
                number: "จำนวนต้องเป็นตัวเลข [0-9] เท่านั้น"
            },
            sing_confirm: "กรุณาระบุชื่อผู้รับรอง",
            'rooms[]': {
                required: "กรุณาเลือกประสงค์จะขอใช้สถานที่อย่างน้อย 1 รายการ",
                minlength: "เลือกประสงค์จะขอใช้สถานที่อย่างน้อย {0} รายการ"
            },
            attach: {
                extension: "ไฟล์แนบต้องมีนามสกุล *.docx, *.pdf เท่านั้น",
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            $.notify({
                message: error[0].innerText,
            }, {
                type: 'danger',
                element: 'body',
            });

            return true;
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').parents('.form-group').addClass('has-danger');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').parents('.form-group').removeClass('has-danger');
        },
    }

    $scope.submitForm = function (event, form) {
        event.preventDefault();
        if (form.validate()) {

            const form_data = new FormData();
            form_data.append('input', JSON.stringify($scope.input));
            angular.forEach($scope.files, function (file) {
                form_data.append('files', file);
            });

            bookingService.addData(form_data).then(function (resp) {
                if (resp.status) {
                    Swal.fire({
                        title: "บันทึกข้อมูลได้สำเร็จ",
                        text: "โปรดรอการอนุมัติรายการจากเจ้าหน้าที่",
                        type: 'success',
                        allowOutsideClick: false,
                        confirmButtonText: 'ตกลง'
                    }).then((res) => {
                        if (res.value) {
                            $window.location.href = BASE_URL;
                        }
                    });
                }
            });
        }
    }

});