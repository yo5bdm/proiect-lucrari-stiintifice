<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-xs-12" >
        <div class="row">
            <div class="col-xs-8">
                <h1>Lista de utilizatori</h1>
            </div>
            <div class="col-xs-4 filtrare">
                <div class="navbar-form navbar-right">
                    <input type="text" ng-model="filtru" class="form-control" placeholder="Filtrează"/>
                </div>
            </div>
        </div>
    </div>   
    
    <div class="col-xs-12">
        
        <p ng-show="useri.length<1">Nu exista inregistrari</p>
        <ul class="list-group" ng-show="useri.length>0" >
            <li class="list-group-item" ng-repeat="x in useri | filter: filtru">
                <div ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal">
                    <h3>{{x.name}} <small>{{x.email}}</small></h3>
                    <p><button class="btn btn-success" ng-click="sterge(x.id)">Sterge userul</button></p>
                </div>
            </li>
        </ul>
        
        
    </div>
</div>
<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    var promise = $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"listauseri"])?>').then(function(response){
        $scope.useri = response.data;
    });
    //metode
    promise.then(function(data){
        $scope.filtru="";
        $scope.sterge = function(ids) {
            var url = '<?=Helpers::generateUrl(["c"=>"json","a"=>"stergeuser"])?>/'+ids;
            alert(url);
        };
    });
}]);
</script>