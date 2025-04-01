'use strict'

app.service("productService", function ($http, $log, BASE_URL) {

    this.searchData = function (data) {
        return $http({
                method: 'POST',
                url: BASE_URL + 'api/product/search',
                data: data,
                headers: {
                    Accept: 'application/json'
                }
            })
            .then(function (response) {
                return response.data;
            })
            .catch(function (error) {
                $log.error('ERROR:', error);
                throw error;
            });
    }

    this.addData = function (data) {
        return $http({
                method: 'POST',
                url: BASE_URL + 'api/product/add',
                data: data,
                headers: {
                    Accept: 'application/json'
                }
            })
            .then(function (response) {
                return response.data;
            })
            .catch(function (error) {
                $log.error('ERROR:', error);
                throw error;
            });
    }
});