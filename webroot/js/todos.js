(function () {
	'use strict';

	angular.module('todos', ['common', 'angularMoment', 'ngStorage'])
		.factory("TodosData", [function () {
			return {
				todos: [],
				paging: {}
			};
		}])
		.directive("private", function () {
			return {
				restrict: "E",
				templateUrl: "/views/private/private.html",
				controller: ['$scope', '$window', '$localStorage', function ($scope, $window, $localStorage) {
					$scope.logout = function () {
						$localStorage.token = null;
						$window.location = "/";
					}
				}]
			};
		})
		.directive("todos", ['$filter', 'api', function ($filter, api) {
			return {
				restrict: "E",
				templateUrl: "/views/private/todos.html",
				link: function (scope, e) {e.find('.dropdown-button').dropdown({})},
				controller: ['$scope', 'TodosData', 'api', function ($scope, TodosData, api) {
					$scope.TodosData = TodosData;

					function filterTodos(v) {
						return $filter('filter')(TodosData.todos, {is_done: v});
					}

					$scope.pendingTodos = function () {
						return filterTodos(false);
					}

					$scope.doneTodos = function () {
						return filterTodos(true);
					}

					$scope.add = function (todo) {
						todo.is_done = false;
						api.addTodo(todo).then(function () {
							$scope.todo.content = '';

							api.todos().then(function (data) {
								TodosData.todos = data.todos;
								TodosData.paging = data.paging;
							})
						});
					};

					$scope.loadMore = function () {
						api.todos(TodosData.paging.page+1)
							.then(function (data) {
								TodosData.paging = data.paging;
								TodosData.todos = TodosData.todos.concat(data.todos);
							});
					}
				}]
			}
		}])
		.directive("checkDone", ['api', function (api) {
			return function (scope, element, attrs) {
				element.on('click', function (e) {
					var done = e.target.checked ? 1 : 0;

					api.editTodo(attrs.todoId, {is_done: done});
				});
			}
		}])
		.directive("removeTodo", [function () {
			return {
				restrict: "A",
				scope: {
					todoId: '='
				},
				link: function (scope, element) {
					element.on('click', function (e) {
						scope.remove();
					});
				},
				controller: ['$scope', '$timeout', 'TodosData', 'api', function ($scope, $timeout, TodosData, api) {
					$scope.remove = function () {
						api.removeTodo($scope.todoId).then(function () {
							api.todos().then(function (data) {
								TodosData.todos = data.todos;
								TodosData.paging = data.paging;
							})
						})
					}
				}]
			}
		}]);
})();