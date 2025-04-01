'use strict'

app.controller("productController", function ($scope, $window, productService) {
    $scope.searchProduct = function () {
        $scope.records = {};
        $scope.name = "";

        angular.element('#searchProductModal').modal({
            'backdrop': 'static',
            'keyboard': false
        }).on('shown.bs.modal', function () {
            document.getElementById("name").focus();
        });
    }

    $scope.submitSearchProduct = function (name) {
        $scope.records = {};
        if (!angular.isUndefinedOrNull(name)) {
            productService.searchData({
                name: name
            }).then(function (resp) {
                $scope.records = resp;
            });
        }

    }

    $scope.addProducItem = function (product) {
        angular.element('#searchProductModal').modal('hide');

        productService.addData({
            product: product,
        }).then(function (resp) {
            if (resp.status) {
                $window.location.reload();
            }
        });
    }
});