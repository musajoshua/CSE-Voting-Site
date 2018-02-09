var app = angular.module ("cseVote", ['ui.router',]);

app.config(['$urlRouterProvider','$stateProvider','$locationProvider', function($urlRouterProvider,$stateProvider,$locationProvider){
	$locationProvider.html5Mode({
	  enabled: true,
	  requireBase: false
	});
	$urlRouterProvider.otherwise('/login');

	$stateProvider
	.state('login', {
		url: '/login',
		templateUrl: './partials/login.html',
		data: {
			pageTitle: 'chat'
		}
	})

	.state('register', {
		url: '/register',
		templateUrl: './partials/register.html',
		data: {
			pageTitle: 'register'
		}
	})

	.state('categories', {
		url: '/categories',
		templateUrl: './partials/categories.html',
		data: {
			pageTitle: 'categories'
		}
	})
	.state('contestant', {
		url: '/contestant',
		templateUrl: './contestant.html',
		data: {
			pageTitle: 'contestant'
		}
	})
}]);