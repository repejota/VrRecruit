angular.module('taskConfirmationApp', ['ui.router', 'ngResource'])
    .config(function ($stateProvider, $urlRouterProvider, $locationProvider) {

        // Use hashtags in URL
        $locationProvider.html5Mode(false);

        // Default route fallback
        $urlRouterProvider.otherwise("/");

        // Routes
        $stateProvider

            // GET '/' route
            .state('index', {
                url: "/",
                templateUrl: "/taskConfirmationApp/templates/index.html",
                controller: 'TaskCtrl'
            })

            // GET '/task/:id route
            .state('task', {
                url: "/task/:id",
                templateUrl: "/taskConfirmationApp/templates/task.html",
                controller: 'TaskMessagesCtrl'
            });
    })

    // Task factory service
    .factory('Task', function ($resource) {
        return $resource('/task/:id?format=json',
            {id: '@id'},
            {
                'get': {method: 'GET'},
                'save': {method: 'PUT'},
                'create': {method: 'POST'},
                'query': {method: 'GET', isArray: true},
                'remove': {method: 'DELETE'},
                'delete': {method: 'DELETE'}
            }
        );
    })

    // Message factory service
    .factory('Message', function ($resource) {
        return $resource('/message/:id?format=json',
            {id: '@id'},
            {
                'get': {method: 'GET'},
                'save': {method: 'PUT'},
                'create': {method: 'POST'},
                'query': {method: 'GET', isArray: true},
                'remove': {method: 'DELETE'},
                'delete': {method: 'DELETE'}
            }
        );
    })

    // Task controller
    .controller('TaskCtrl', function ($scope, Task) {
        $scope.tasks = Task.query();
    })

    // Messages controller
    .controller('TaskMessagesCtrl', function ($scope, $stateParams, Task, Message) {
        var id = $stateParams.id;
        $scope.task = Task.get({id: id});
        $scope.messages = Message.query({id: id});
    });
