<div class="row">
    <div class="col-xs-12" ng-app="myApp" ng-controller="myCtrl">
        <h1>Lista de Lucrari 
            <div class="navbar-form navbar-right ">
                <input type="text" ng-model="filtru" class="form-control" placeholder="Cauta lucrare, autor"/>
            </div>
        </h1>
        <p ng-show="lucrari.length<1">Nu exista inregistrari</p>
        <ul class="list-group" ng-show="lucrari.length>0" >
            <li class="list-group-item" ng-repeat="x in lucrari | filter: filtru">
                <h3>{{x.titlu}}</h3>
                <p>{{x.abstract}}</p>
                <button class="btn" ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal">Vizualizeaza</button>
            </li>
        </ul>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>ID-ul selectat {{currentId}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    
    </div>
</div>


<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    var promise = $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"listalucrari"])?>').then(function(response){
        $scope.lucrari = response.data;
    });
    //metode
    promise.then(function(data){
        $scope.filtru="";
        $scope.currentId;
        $scope.md = {};
        $scope.getLucrare = function(ids) {
            var lucrare;
            for(var i=0;i<$scope.lucrari.length;i++) {
                lucrare = $scope.lucrari[i];
                if(lucrare.id === ids) {
                    $scope.md = lucrare;
                }
            }
        };
        
        $scope.modal = function(id) {
            $scope.currentId = id;
            $scope.md = $scope.getLucrare(id);
        };
    });
}]);
</script>