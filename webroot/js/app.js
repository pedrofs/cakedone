(function () {
	'use strict';

	var cakedone = angular.module('cakedone', [
		'ngRoute',
		'ngAnimate',
		'ui.router'
	]);

	cakedone.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
		$urlRouterProvider.otherwise('/');

		$stateProvider
			.state('public', {template: '<public></public>', abstract: true})
			.state('public.login', {url: '/', template: '<login></login>'})
			.state('public.register', {url: '/register', template: '<register></register>'});
	}]);
})();

(function () {
	'use strict';

	angular.module('cakedone')
		.directive('public', [function () {
			return {
				restrict: "E",
				templateUrl: '/views/public/public.html',
				controller: ['$scope', function ($scope) {
					console.log('public state');
				}]
			};
		}])
		.directive('login', [function () {
			return {
				restrict: "E",
				templateUrl: '/views/public/login.html',
				controller: ['$scope', function ($scope) {
					console.log('login state');
				}]
			};
		}])
		.directive('register', [function () {
			return {
				restrict: "E",
				templateUrl: '/views/public/register.html',
				controller: ['$scope', function ($scope) {
					console.log('register state');
				}]
			};
		}]);
})();