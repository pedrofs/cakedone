(function () {
	'use strict';

	angular.module('auth', ['ngStorage'])
		.config(['$provide', '$httpProvider', function ($provide, $httpProvider) {
			$provide.factory('authInterceptor', ['$q', '$localStorage', function ($q, $localStorage) {
				var user = {
					token: null
				};

				return {
					response: function (response) {
						var data = response.data;

						if (data.token && !user.token) {
							user.token = data.token;
							$localStorage.token = data.token;
						}

						return response;
					},
					request: function (config) {
						if (user.token || $localStorage.token) {
							user.token = $localStorage.token;
							config.headers.Authorization = "Bearer " + user.token;
						}

						return config;
					},
					responseError: function (response) {
						console.log(response);

						return $q.when(response);
					}
				};
			}]);

			$httpProvider.interceptors.push('authInterceptor');
		}])
})();