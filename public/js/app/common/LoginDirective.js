'use strict';

/**
 * Show or hide the Login dialog when we receive events
 */
app.directive('loginDialog', function (AUTH_EVENTS) {
    return {
        restrict: 'A',
        template: '<div ng-if="visible" ng-include="\'/js/app/login.html\'">',
        link: function (scope) {
            var showDialog = function () {
                scope.visible = true;
            };

            scope.visible = false;
            scope.$on(AUTH_EVENTS.notAuthenticated, showDialog);
            scope.$on(AUTH_EVENTS.sessionTimeout, showDialog);
            scope.$on(AUTH_EVENTS.logoutSuccess, showDialog);
            scope.$on(AUTH_EVENTS.loginSuccess, function() {
                scope.visible = false;
            });
        }
    };
});