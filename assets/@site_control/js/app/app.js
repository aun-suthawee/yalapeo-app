'use strict'

var app = angular.module("myApp", [], ['$httpProvider', '$interpolateProvider', function ($httpProvider, $interpolateProvider) {
    $httpProvider.defaults.headers.post['X-CSRF-TOKEN'] = $('meta[name=csrf-token]').attr('content');
    $interpolateProvider.startSymbol('{%');
    $interpolateProvider.endSymbol('%}');
}]).constant('BASE_URL', '/');

angular.isUndefinedOrNull = function (val) {
    return angular.isUndefined(val) || val === null || val === "";
};