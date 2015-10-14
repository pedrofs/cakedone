(function () {
	'use strict';

	angular.module('public', ['common'])
		.directive('public', [function () {
			return {
				restrict: "E",
				templateUrl: '/views/public/public.html'
			};
		}])
		.directive('login', [function () {
			return {
				restrict: "E",
				templateUrl: '/views/public/login.html',
				controller: ['$scope', 'api', function ($scope, api) {
					console.log('login state');
				}]
			};
		}])
		.directive('register', [function () {
			return {
				restrict: "E",
				templateUrl: '/views/public/register.html',
				controller: ['$scope', '$state', 'api', function ($scope, $state, api) {
					$scope.register = function (user) {
						if (!$('form').parsley().validate()) {
							return;
						}

						api.register(user)
							.then(function (response) {
								$state.go('private.todos').then(function (asd) {
									console.log(asd);
								}).catch(function (asd) {
									console.log(asd);
								});
							});
					};
				}]
			};
		}]);
})();