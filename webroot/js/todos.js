(function () {
	'use strict';

	angular.module('todos', ['common', 'angularMoment'])
		.factory("TodosData", [function () {
			return {
				todos: [],
				paging: {}
			};
		}])
		.directive("private", function () {
			return {
				restrict: "E",
				templateUrl: "/views/private/private.html"
			};
		})
		.directive("todos", ['api', function (api) {
			return {
				restrict: "E",
				templateUrl: "/views/private/todos.html",
				controller: ['$scope', 'TodosData', 'api', function ($scope, TodosData, api) {
					$scope.TodosData = TodosData;

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