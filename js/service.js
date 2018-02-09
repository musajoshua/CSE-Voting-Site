angular.module('cseVote.services', []);

cseVote.factory('dataApi', function dataApi($http, $q, $routeParams) {
	function getData() {
      return ($http.get('data/data.json').then(handleSuccess, handleError));
    }
    function handleSuccess(response){
	    return response.data;
	}
	function handleError(response) {
      if (!angular.isObject(response.data) || !response.data.message) {
        return ($q.reject("An unknown error occured"));
      }
      return ($q.reject(response.data.message));
    }
     var getRouteParams = function () {
      return $routeParams;
    }
    return ({
        getData: getData,
        getRouteParams: getRouteParams
    });
});

app.factory('dataHandler', ['$http', function($http){

	$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";


        var baseUrl = "http://localhost/cse-Voting-Site/";
        var handle = {};

        handle.get = function (uri) {
            return $http.get(baseUrl + uri).success(function (results) {
                return results.data;
            });
        };
        handle.post = function (uri, data) {
            return $http.post(baseUrl + uri, data).then(function (results) {
                return results.data;
            });
        };
        handle.put = function (uri, data) {
            return $http.put(baseUrl + uri, data).then(function (results) {
                return results.data;
            });
        };
        handle.delete = function (uri) {
            return $http.delete(baseUrl + uri).then(function (results) {
                return results.data;
            });
        };
        handle.test = function(){
            return "hello world";
        };
        handle.testData = function(data){
        	return data;
        }

        return handle;
}])