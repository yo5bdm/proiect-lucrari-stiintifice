<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Adaugă / modifică opțiunile pentru tipul de indexare:</h4></div>
            <div class="panel-body">        
                <ul class="list-group">
                    <li class="list-group-item" ng-repeat="x in json.Categorii | orderBy:id">
                        {{x.id}}. {{x.denumire}}
                        <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" ng-click="stergeCategoria(x.id)"></span>
                        <span ng-show="$index > 0" ng-click="urca(x.id)" title="Urcă" class="glyphicon glyphicon-arrow-up pull-right"></span>&nbsp;
                        <span ng-show="$index < json.Categorii.length-1" ng-click="coboara(x.id)" title="Coboară" class="glyphicon glyphicon-arrow-down pull-right"></span>&nbsp;

                    </li>
                    <li class="list-group-item list-group-item-info" ng-hide="json.Categorii.length">Nu sunt categorii de afisat</li>
                </ul>
                <hr/>
                <p>Adaugă o categorie nouă:</p>
                <input class="form-control" ng-model="form.denumireCat" placeholder="Denumire categorie"/> <br/>
                <button ng-click="adaugaCategorie()" class="form-control btn btn-success">Adaugă categoria</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Baze de date:</h4></div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item" ng-repeat="x in json.BazeDeDate">
                        {{x.denumire}}
                        <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" ng-click="stergeDB(x.id)"></span>                        
                    </li>
                    <li class="list-group-item list-group-item-info" ng-hide="json.BazeDeDate.length">Nu sunt baze de date de afisat</li>
                </ul>
                <hr/>
                <p>Adaugă o bază de date nouă:</p>
                <input class="form-control" ng-model="form.denumireDB" placeholder="Denumire bază de date"/> <br/>
                <button ng-click="adaugaDB()" class="form-control btn btn-success">Adaugă categoria</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.json = {};
    $scope.form = {};
    var promise = $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"admin"])?>').then(function(response){
        $scope.json = response.data;
        console.log($scope.json);
    });
    //metode
    promise.then(function(data){
        $scope.form.denumireCat='';
        $scope.form.denumireDB ='';
        $scope.adaugaCategorie = function() {
            var cat = {
                id:$scope.getNextCategorieID(),
                denumire:$scope.form.denumireCat
            };
            $scope.json.Categorii.push(cat);
            $scope.salveazaCategorii();
        };
        
        $scope.getNextCategorieID = function() {
            var id = 0;
            $scope.json.Categorii.filter(function(cat){
                if(cat.id>id) id = cat.id;
                return false;
            });
            return id+1;
        };
        $scope.urca = function(ids) {
            for(var i=0;i<$scope.json.Categorii.length;i++) {
                if($scope.json.Categorii[i].id == ids) {
                    $scope.json.Categorii[i].id--;
                    $scope.json.Categorii[i-1].id++;
                    $scope.sorteaza();
                    $scope.salveazaCategorii();
                    break;
                }
            };
        };
        
        $scope.coboara = function(ids) {
            for(var i=0;i<$scope.json.Categorii.length;i++) {
                if($scope.json.Categorii[i].id == ids) {
                    $scope.json.Categorii[i].id++;
                    $scope.json.Categorii[i+1].id--;
                    $scope.sorteaza();
                    $scope.salveazaCategorii();
                    break;
                }
            };
        };
        $scope.sorteaza = function() {
            $scope.json.Categorii.sort(function(a,b){
                return a.id - b.id;
            });
        };
        $scope.stergeCategoria = function(ids) {
            for(var i=0;i<$scope.json.Categorii.length;i++) {
                if($scope.json.Categorii[i].id == ids) {
                    $scope.json.Categorii.splice(i,1);
                    $scope.salveazaCategorii();
                    break;
                }
            }
            for(var i=0;i<$scope.json.Categorii.length;i++) {
                $scope.json.Categorii[i].id = i+1;
            }
        }
        
        $scope.salveazaCategorii = function(){
            var param = {
            datele:$scope.json.Categorii
            };
            var config = {
                headers : {
                    'Content-Type': 'application/json'
                }
            };
            $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'savecategorii'])?>",param,config).then(function(response){
                console.log(response);
            });
        };
        
        //baze de date
        $scope.adaugaDB = function() {
            var db = {
                id:$scope.getNextDbID(),
                denumire:$scope.form.denumireDB
            };
            $scope.json.BazeDeDate.push(db);
            $scope.salveazaDB();
        };
        $scope.getNextDbId = function() {
            var id = 0;
            $scope.json.BazeDeDate.filter(function(cat){
                if(cat.id>id) id = cat.id;
                return false;
            });
            return id+1;
        };
        $scope.stergeDB = function() {
            for(var i=0;i<$scope.json.BazeDeDate.length;i++) {
                if($scope.json.BazeDeDate[i].id == ids) {
                    $scope.json.BazeDeDate.splice(i,1);
                    $scope.salveazaDB();
                    break;
                }
            }
        };
        $scope.salveazaDB = function() {
            var param = {
            datele:$scope.json.BazeDeDate
            };
            var config = {
                headers : {
                    'Content-Type': 'application/json'
                }
            };
            $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'savebazededate'])?>",param,config).then(function(response){
                console.log(response);
            });
        };
        
    });
}]);
</script>