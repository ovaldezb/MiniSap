app.controller('logoutCtrl', function($scope, $location,$window)
{
  $scope.indx = $location.absUrl().indexOf('login');
  $scope.myUrl = $location.absUrl().substring(0, $scope.indx-1);
  $scope.init = function()
  {
    $window.location.href = $scope.myUrl;
  }
});
