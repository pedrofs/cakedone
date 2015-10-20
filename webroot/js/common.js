(function () {
	'use strict';

	angular.module('common', [])
		.config(['$provide', '$httpProvider', function ($provide, $httpProvider) {
			$provide.factory('formErrorsInterceptor', ['$q', function ($q) {
				return {
					responseError: function (rejection) {
						var data = rejection.data;

						if (data && data.errors) {
							angular.forEach(data.errors, function (errors, field) {
								var uierror = '';
								angular.forEach(errors, function (error, errorName) {
									uierror += uierror + error + ". ";
								});

								var parsleyField = $('#' + field).parsley();
           			window.ParsleyUI.addError(parsleyField, 'error' + field, uierror);
							})
						}

						return $q.reject(rejection);
					}
				};
			}]);

			$httpProvider.interceptors.push('formErrorsInterceptor');
		}])
		.factory('api', ['$http', function ($http) {
			var m = {
				register: register,
				login: login,
				todos: todos,
				addTodo: addTodo,
				removeTodo: removeTodo,
				editTodo: editTodo
			};

			function login(user) {
				return $http.post('/users/login.json', {email: user.email, password: user.password});
			}

			function editTodo(id, todo) {
				return $http.post('/todos/edit/'+id+'.json', todo).then(function (response) {
					return response.data;
				})
			}

			function removeTodo(id) {
				return $http.delete('/todos/delete/' + id + '.json');
			}

			function addTodo(todo) {
				return $http.post('/todos/add.json', todo)
					.then(function (response) {
						return response.data.todo;
					});
			}

			function todos(page) {
				return $http.get(addPageToUrl('/todos.json', page))
					.then(function (response) {
						return response.data;
					});
			};

			function register(user) {
				return $http.post('/users/add.json', {
					name: user.name,
					email: user.email,
					password: user.password,
					password_confirmation: user.password_confirmation
				});
			};

			function addPageToUrl(url, page) {
				if (page) {
					return url + '?page=' + page;
				}

				return url;
			};

			return m;
		}]);
})();