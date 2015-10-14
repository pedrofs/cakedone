(function () {
	'use strict';

	var cakedone = angular.module('cakedone', [
		'ngRoute',
		'ngAnimate',
		'ui.router',
		'angular-loading-bar',
		'auth',
		'public',
		'todos'
	]);

	cakedone.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
		$urlRouterProvider.otherwise('/');

		$stateProvider
			.state('public', {template: '<public></public>', abstract: true})
			.state('public.login', {url: '/', template: '<login></login>'})
			.state('public.register', {url: '/register', template: '<register></register>'})
			.state('private', {template: '<private></private>', abstract: true})
			.state('private.todos', {
				url: '/todos',
				template: '<todos></todos>',
				resolve: {
					todos: ['api', 'TodosData', function (api, TodosData) {
						return api.todos()
							.then(function (data) {
								TodosData.todos = data.todos;
								TodosData.paging = data.paging;
							});
					}]
				}
			});
	}]);
})();