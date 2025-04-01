'use strict'

app.service("bookingService", function ($http, $log, BASE_URL) {

    this.addData = function (data) {
        return $http({
            method: 'POST',
            url: BASE_URL + 'booking/submit',
            data: data,
            dataType: 'json',
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined,
                'Process-Data': false
            }
        }).then(function (response) {
            return response.data;
        }).catch(function (error) {
            $log.error('ERROR:', error);
            throw error;
        });
    }

});