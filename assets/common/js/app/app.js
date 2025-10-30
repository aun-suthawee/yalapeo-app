'use strict'

const app = angular.module("myApp", ['ngValidate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}).constant('BASE_URL', 'http://209.15.122.27');

angular.isUndefinedOrNull = function (val) {
    return angular.isUndefined(val) || val === null || val === "";
};

app.directive("fileInput", function ($parse) {
    return {
        link: function ($scope, element, attrs) {
            element.on("change", function (event) {
                const files = event.target.files;
                //console.log(files[0].name);
                $parse(attrs.fileInput).assign($scope, element[0].files);
                $scope.$apply();
            });
        }
    }
});