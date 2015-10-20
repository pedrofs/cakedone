(function () {
	'use strict';

	angular.module('auth', ['ngStorage'])
		.config(['$provide', '$httpProvider', function ($provide, $httpProvider) {
			$provide.factory('authInterceptor', ['$q', '$localStorage', '$window', function ($q, $localStorage, $window) {
				var user = {
					token: null
				};

				return {
					response: function (response) {
						var data = response.data;

						if (data && data.token && !user.token) {
							user.token = data.token;
							$localStorage.token = data.token;
						}

						return response;
					},
					request: function (config) {
						if (user.token || $localStorage.token) {
							user.token = $localStorage.token;
							config.headers.Authorization = "Bearer " + user.token;
							config.url += '?_token=' + user.token;
						}

						return config;
					},
					responseError: function (response) {
						if (response.status == 401) {
							$window.location = '/';
						}

						return $q.reject(response);
					}
				};
			}]);

			$httpProvider.interceptors.push('authInterceptor');
		}])
})();