<div class="row" ng-app="myApp" ng-controller="myCtrl" >
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="card-title text-center">Adauga o lucrare noua</h3>
            </div>
            <div class="panel-body">
                <!-- {"titlu","autori":[],"abstract":"","volum":"","pagini":"","conferinta":"","anulPublicarii":"","link":"","linkLocal":"","citari":[],"bibliografie":[]} -->
                <p>Titlul lucrarii
                    <input type="text" class="form-control" ng-model="lucrare.titlu"/>
                </p>
                <p>Abstract
                    <textarea class="form-control" ng-model="lucrare.abstract"></textarea>
                </p>
                <p>Volum
                    <input type="text" class="form-control" ng-model="lucrare.volum"/>
                </p>
                <p>Pagini
                    <input type="text" class="form-control" ng-model="lucrare.pagini"/>
                </p>
                <p>Conferinta
                    <input type="text" class="form-control" ng-model="lucrare.conferinta"/>
                </p>
                <p>Anul publicarii
                    <input type="text" class="form-control"  ng-model="lucrare.anulPublicarii"/>
                </p>
                <p>Link
                    <input type="text" class="form-control"  ng-model="lucrare.link"/>
                </p>
                <p>Link local
                    <input type="text" class="form-control" ng-model="lucrare.linkLocal"/>
                </p>
                <p>Bibliografie
                    <input type="text" class="form-control"/>
                </p>
                <p>Citari
                    <input type="text" class="form-control"/>
                </p>
                <button class="form-control btn btn-success">Salveaza</button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="affix" data-spy="affix" data-offset-top="60" data-offset-bottom="200">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p><strong>{{lucrare.titlu}}</strong>, Autorii: </p>
                    <p>{{lucrare.abstract}}</p>
                    <p>{{lucrare.volum}}</p>
                    <p>{{lucrare.pagini}}</p>
                    <p>{{lucrare.conferinta}}</p>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script>
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope', '$window','$http', function($scope,$window,$http) {
    $scope.lucrare = {};
    $scope.lucrare.titlu="";
    $scope.lucrare.autori={};
    $scope.lucrare.abstract="";
    $scope.lucrare.volum="";
    $scope.lucrare.pagini="";
    $scope.lucrare.conferinta="";
    $scope.lucrare.anulPublicarii="";
    $scope.lucrare.link="";
    $scope.lucrare.linkLocal="";
    $scope.lucrare.citari={};
    $scope.lucrare.bibliografie={};
    
            
//    var promise = $http.get('json.php').then(function(response){
//        var json = response.data.records;
//        for(key in json) {
//            $scope[key] = json[key];
//        }
//    });
//    //metode
//    promise.then(function(data){
//        
//    }
}]);

</script>