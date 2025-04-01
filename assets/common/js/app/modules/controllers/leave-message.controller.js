'use strict'

// $.validator.addMethod('filesize', function (value, element, arg) {
//     return this.optional(element) || (element.files[0].size <= param)
// }, 'File size must be less than {0}');

app.controller("leaveMessageController", function ($scope, $window, BASE_URL, leaveMessageService) {
    $scope.registerValidationOptions = {
        rules: {
            name: "required",
            address: "required",
            email: "required",
            message: "required",
        },
        messages: {
            name: "กรุณาระบุชื่อ-สกุล",
            address: "กรุณาระบุที่อยู่",
            email: "กรุณาระบุอีเมลล์",
            message: "กรุณาระบุข้อความ",
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

    $scope.onSubmit = function (event, form) {
        event.preventDefault();
        if (form.validate()) {
            leaveMessageService.onSubmit($scope.input).then(function (resp) {
                if (resp == "success") {
                    Swal.fire({
                        title: "ระบบทำการส่งข้อมูลได้สำเร็จ",
                        text: "โปรดรอการติดต่อกลับจากเจ้าหน้าที่",
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