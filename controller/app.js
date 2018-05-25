var app = angular.module('cse',['ui.router']);
app.config(function($stateProvider, $urlRouterProvider) {
  // For any unmatched url, redirect to /registration
  $urlRouterProvider.otherwise("/home");
  // Now set up the states
  $stateProvider

    // home route
    .state('home', {
      url: "/home",
      templateUrl: "views/home.html"
    })

    // contact route
    .state('contact', {
      url: "/contact",
      templateUrl: "views/contact.html"
    })

    .state('features', {
      url: "/features",
      templateUrl: "views/features.html"
    })

    .state('pricing', {
      url: "/pricing",
      templateUrl: "views/pricing.html"
    })

    .state('tour', {
      url: "/tour",
      templateUrl: "views/tour.html"
    });
});