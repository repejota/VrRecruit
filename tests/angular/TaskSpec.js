describe('Dummy', function() {
	it('works', function() {
		expect(true).toBe(true);
	});
});

describe('TaskCtrl', function() {
	var $scope, $location, $controller;
	beforeEach(inject(function ($rootScope, _$controller_, _$location_) {
		$location = _$location_;
		$scope = $rootScope.$new();
		$controller = _$controller_;
	}));

	it('should ...', function() {
		expect($location).toBeDefined();
		$location.path('/');
		expect($location.path()).toBe('/');
		expect($scope).toBeDefined();
	});
});

describe('TaskMessagesCtrl', function() {
	var $scope, $location, $controller;
	beforeEach(inject(function ($rootScope, _$controller_, _$location_) {
		$location = _$location_;
		$scope = $rootScope.$new();
		$controller = _$controller_;
	}));

	it('should ...', function() {
		expect($location).toBeDefined();
		$location.path('/task/6');
		expect($location.path()).toBe('/task/6');
		expect($scope).toBeDefined();
	});
});