'use strict';

angular.module('app').controller('WithdrawOutputModalAddCtrl', ['$scope', '$modal','WithdrawOutput', function($scope, $modal, WithdrawOutput) {

    $scope.withdrawOutput = {
        application_id: 1,
        is_accepted:    true
    };

    $scope.create = function()
    {
        WithdrawOutput.save($scope.withdrawOutput, function(response) {
            $scope.$emit('withdraw_output:create', response);
            $scope.$hide();
        });
    };

}]);