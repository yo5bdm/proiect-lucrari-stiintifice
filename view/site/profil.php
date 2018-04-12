<style>
.input-group-addon {
    min-width:8em;
    text-align:right; 
}
</style>
<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div ng-show="salvat" class="alert alert-warning" role="alert">Modificările au fost salvate!</div>
    <div class="col-md-2">&nbsp;</div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center">Profilul meu</h4>
            </div>
            <div class="panel-body">
                <div class="input-group">
                    <span class="input-group-addon">Nume:</span>
                    <input type="text" class="form-control" ng-model="json.nume"/>
                </div><br/>
                <div class="input-group">
                    <span class="input-group-addon">Prenume:</span>
                    <input type="text" class="form-control" ng-model="json.prenume"/>
                </div><br/>
                <div class="input-group">
                    <span class="input-group-addon">Parola:</span>
                    <input type="password" class="form-control" ng-model="json.password"/>
                </div><br/>
                <div class="input-group">
                    <span class="input-group-addon">E-mail:</span>
                    <input type="text" class="form-control" ng-model="json.email"/>
                </div><br/>
                <div class="input-group">
                    <span class="input-group-addon">Functia:</span>
                    <input type="text" class="form-control" ng-model="json.functia"/>
                </div>
                <p>Grupuri:</p>
                <ul class="list-group">
                    <li class="list-group-item" ng-show="json.group.length == 0">Nici un grup</li>
                    <li class="list-group-item" ng-repeat="x in json.group">{{printGrup(x).denumire}}
                        <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" ng-click="stergeGrupul(x)"></span>
                    </li>
                </ul>
                <div class="input-group">
                    <span class="input-group-addon">Adauga grup:</span>
                    <select class="form-control" ng-model="grupSelectie">
                        <option ng-repeat="x in grupuri" value="{{x.id}}">{{x.denumire}}</option>
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-default" ng-click="adaugaGrup()" type="button">Adauga</button>
                    </span>
                </div>
                <br/>
                <p><button class="form-control btn btn-success" ng-click="salveaza()">Salvează modifiările</button></p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http','$timeout', function($scope,$http,$timeout) {
    $scope.json = {};
    $scope.salvat = false;
    $scope.getMyUser = function() {
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getmyuser"])?>').then(function(response){
            $scope.json = response.data;
        }); 
    };
    $scope.getGrupuri = function() {
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getgrupuri"])?>').then(function(response){
            $scope.grupuri = response.data;
        });
    };
    
    Promise.all([
        $scope.getMyUser(),
        $scope.getGrupuri()
    ]).then(function(data){
        $scope.grupSelectie='0';
        $scope.adaugaGrup = function() {
            $scope.json.group.push($scope.grupSelectie);
        };
        $scope.printGrup = function(ids) {
            console.log(ids);
            return $scope.grupuri.find(function(element){
                return element.id === Number(ids);
            });
        };
        $scope.stergeGrupul = function(ids) {
            var grupuri = $scope.json.group;
            for(var i=0;i<grupuri.length;i++) {
                if(grupuri[i] == Number(ids)) {
                    $scope.json.group.splice(i,1);
                    return;
                }
            }
        };
        
        
        $scope.salveaza = function() {
            var param = {
                datele:$scope.json
            };
            var config = {
                headers : {
                    'Content-Type': 'application/json' //'application/x-www-form-urlencoded;charset=UTF-8'
                }
            };
            $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'updateuser'])?>",param,config).then(function(response){
                console.log(response);
                $scope.salvat = true;
                $timeout(function() { $scope.salvat=false; }, 3000);
            });
        };
        $scope.$apply(function(){
            $scope.printGrup(0);
        });
    });
}]);
</script>