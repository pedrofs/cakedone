(function () {
	'use strict';

	angular.module('todos', ['common'])
		.factory("TodosData", [function () {
			return {
				todos: []
			};
		}])
		.directive("private", function () {
			return {
				restrict: "E",
				templateUrl: "/views/private/private.html",
				controller: ["$scope", function ($scope) {
					console.log('private state');
				}]
			};
		})
		.directive("todos", ['api', function (api) {
			return {
				restrict: "E",
				templateUrl: "/views/private/todos.html",
				controller: ['$scope', function ($scope) {
					console.log('todos state');
				}]
			}
		}]);
})();