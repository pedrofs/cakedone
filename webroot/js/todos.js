(function () {
	'use strict';

	angular.module('todos', ['common', 'angularMoment'])
		.factory("TodosData", [function () {
			return {
				todos: [],
				remove: function (id) {
					var i;
					for (i=0;i<this.todos.length;i++) {
						if (this.todos[i].id == id) {
							this.todos = this.todos.slice(0,i).concat(this.todos.slice(i+1));
							return;
						}
					}
				}
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
						api.addTodo(todo).then(function (todo) {
							TodosData.todos.push(todo);
							$scope.todo.content = '';
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
							TodosData.remove($scope.todoId);
						})
					}
				}]
			}
		}]);
})();