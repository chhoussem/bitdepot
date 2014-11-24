'use strict';

app.controller('WithdrawCtrl', ['$scope', '$location', '$modal', 'Withdraw', 'WithdrawOutput', function($scope, $location, $modal, Withdraw, WithdrawOutput) {

    $scope.withdraws = Withdraw.query();
    $scope.withdrawOutputs = WithdrawOutput.query({application: 10});

    $scope.openModalSignature = function(withdraw) {

        Withdraw.get({id: withdraw.id}, function(withdraw) {

            $scope.withdraw = withdraw;

            $modal({
                title:    'Withdraw detail',
                template: 'js/app/withdraw/modal.html',
                animation:'am-fade-and-scale',
                placement:'center',
                show:     true,
                scope:    $scope
            });

        });

    };

    /**
     * Update the withdraw in the list when an update is received.
     */
    $scope.$on('withdraw:update', function(e, withdraw) {
        var index = _.findIndex($scope.withdraws, {id: withdraw.id});

        $scope.withdraws[index] = withdraw;
    });

}]);