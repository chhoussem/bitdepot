'use strict';

var app = angular.module('app', ['ngResource', 'ngRoute', 'ngSanitize', 'ngAnimate', 'mgcrea.ngStrap']);

app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider

        .when('/', {
            templateUrl : '/js/app/home.html',
            controller  : 'HomeCtrl'
        })

        .when('/deposits', {
            templateUrl : '/js/app/deposit/list.html',
            controller  : 'DepositCtrl'
        })

        .when('/withdraws', {
            templateUrl : '/js/app/withdraw/list.html',
            controller  : 'WithdrawCtrl'
        })

        .otherwise({redirectTo: '/404'})
    ;

    $locationProvider.html5Mode(true);

}]);