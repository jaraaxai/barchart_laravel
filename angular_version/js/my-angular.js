var app = angular.module('myApp', []);
app.controller('customersCtrl', function($scope, $http) {
   $http.get("barchart.php/quotes/list")
   .then(function (response) {
      $scope.names = response.data;
      $scope.sortType = 'symbol';
      $scope.sortReverse = false;
      $scope.showForm = false;

      // form data
      $scope.formData = {};
      $scope.searchMsg = '';

      // sort change class
      $scope.sortClass = function(sortClass) {
        if( $scope.sortType == sortClass ){
          return $scope.sortReverse ? 'headerSortUp' : 'headerSortDown'
        }
        return '';
      };

      // sort by any column
      $scope.sortBy = function(sortType) {
        $scope.sortReverse = ($scope.sortType === sortType) ? !$scope.sortReverse : false;
        $scope.sortType = sortType;
      };

      // delete from list
      $scope.delete = function ( idx ) {
        $http.post('barchart.php/quotes/delete?id='+ idx.id)
          .then(function (response) {
            $scope.names.splice($scope.names.indexOf(idx), 1);
        });
      };

      // cancel form
      $scope.cancelForm = function () {
        $scope.showForm = false;
        $scope.errorSymbol = null;
        $scope.errorName = null;
        $scope.errorPrice = null;
        $scope.errorChange = null;
        $scope.errorPctChange = null;
        $scope.errorVolume = null;
      };

      // search form submit
      $scope.searchFunc = function() {
        if ($scope.keyword) {
          
          $scope.errorKeyword = false;
          
          $http({
              method  : 'POST',
              url     : 'barchart.php/quotes/apisearch',
              data    : { 'keyword' : $scope.keyword },
              //headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
          }).then(function(data) {
                  if (data.data.success) {
                      $scope.searchMsg            = data.data.message;
                      $scope.formData.symbol      = data.data.quote.symbol;
                      $scope.formData.name        = data.data.quote.name;
                      $scope.formData.last_price  = data.data.quote.last_price;
                      $scope.formData.prichange   = data.data.quote.prichange;
                      $scope.formData.pctchange   = data.data.quote.pctchange;
                      $scope.formData.volume      = data.data.quote.volume;
                      $scope.formData.id = data.data.quote.id;
                  } else {
                      $scope.searchMsg = data.data.message;
                      $scope.formData = {};
                      $scope.formData.symbol = $scope.keyword;
                  }

                  $scope.showForm = true;
              });



        }else{
          $scope.errorKeyword = true;
        }
      };

      // add/edit form submit
      $scope.processForm = function() {
        $http({
              method  : 'POST',
              url     : 'barchart.php/quotes/add',
              data    : $.param($scope.formData),  // pass in data as strings
              headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  
          }).then(function(data) {
                  console.log(data);
                  if (!data.data.success) {
                      $scope.errorSymbol = data.data.errors.symbol;
                      $scope.errorName = data.data.errors.name;
                      $scope.errorPrice = data.data.errors.price;
                      $scope.errorChange = data.data.errors.prichange;
                      $scope.errorPctChange = data.data.errors.pctchange;
                      $scope.errorVolume = data.data.errors.volume;
                  } else {
                    // if successful, bind success message to message
                      $scope.message = data.data.message;
                      $scope.errorSymbol = null;
                      $scope.errorName = null;
                      $scope.errorPrice = null;
                      $scope.errorChange = null;
                      $scope.errorPctChange = null;
                      $scope.errorVolume = null;
                      $scope.names = data.data.names;
                      $scope.formData = {};
                      $scope.cancelForm();
                  }
              });
      };

  });
});