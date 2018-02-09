var app = angular.module('cseVote');
app.controller ("voteController", function($scope) {
	console.log("cse voting");
});

app.controller('category', ['$scope',  function ($scope, $http, dataApi, $routeParams) {
    $http.get('http://localhost/getsessionid').then(function(res){
        var ssid = res.data.sessid;
        if (ssid === "") {
            $state.go('login');
        }else{
            console.log(ssid);
        }
    });
}]);


//Registration page data biniding
app.controller('voteRegister', ['$scope',  function ($scope, $http, dataApi, $routeParams) {
    $scope.SendData  = function () {
      
            var data = $.param({
                username: $scope.username,
                email: $scope.email,
                password: $scope.password,
                phone: $scope.phone        
            });

           $http.post('', data, config)
            .success(function (data, status, headers, config) {
                $scope.PostDataResponse = data;
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data;
                    
            });

    };
}]);

app.controller('voteLogin', ['$scope', function($scope,$http) {
    $scope.SendData  = function () {
            var data = $.param({
                email: $scope.email,
                password: $scope.password,
            });
            console.log(data);
           $http.post('', data, config)
            .success(function (data, status, headers, config) {
                $scope.PostDataResponse = data;
            })
            .error(function (data, status, header, config) {
                $scope.ResponseDetails = "Data: " + data;     
            });
    };
}]);


