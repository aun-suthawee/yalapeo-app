'use strict'

app.service("leaveMessageService", function ($http, $log, BASE_URL) {

    this.onSubmit = function (data) {
        return $http({
            method: 'POST',
            url: BASE_URL + 'leave-message',
            data: data,
            dataType: 'json',
        }).then(function (response) {
            return response.data;
        }).catch(function (error) {
            $log.error('ERROR:', error);
            throw error;
        });
    }

});