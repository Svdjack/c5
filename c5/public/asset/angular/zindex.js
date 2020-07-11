/* global angular, angControllerOneClick, angComponentChangeCity */
angular.module('app', [])
  .config(['$sceDelegateProvider', ($sceDelegateProvider) => {
    $sceDelegateProvider.resourceUrlWhitelist([
      'self',
      'https://angularjs.org/**',
    ]);
  }])
  .controller('OneClick', ['$scope', '$http', angControllerOneClick])
  .component('changeCity', {
    bindings: {
      city: '<',
    },
    templateUrl: '/asset/angular/change-city.html',
    controller: ['$scope', '$http', angComponentChangeCity],
  });
