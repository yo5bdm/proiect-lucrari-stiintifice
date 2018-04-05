<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-xs-12">
        <div ng-show="form.modificari" class="alert alert-warning" role="alert">Există modificări, vă rugăm salvați înainte de a părăsi pagina! <button ng-show="form.modificari" class="btn btn-danger" ng-click="saveUnitate()">Salvează modificările</button></div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Facultăți</h4></div>
                    <div class="panel-body">
                        <ul class="list-group" >
                            <li class="list-group-item" ng-repeat="fac in json.facultati">Facultatea de <strong>{{fac.denumire}}</strong>
                            <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" 
                                  ng-click="stergeFacultate(fac.id)"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Departamente</h4></div>
                    <div class="panel-body">
                        <ul class="list-group" >
                            <li class="list-group-item" data-toggle="tooltip" title="{{dep.denumire}}"
                            ng-repeat="dep in json.departamente">Departamentul <strong>{{dep.prescurtare}}</strong>, Fac. <strong>{{json.facultati[dep.parinte].denumire}}</strong>
                                <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" 
                                  ng-click="stergeDepartament(dep.id)"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Grupuri</h4></div>
                    <div class="panel-body">
                        <ul class="list-group" >
                            <li class="list-group-item" data-toggle="tooltip" title="{{grp.denumire}}"
                            ng-repeat="grp in json.grupuri">Grupul <strong>{{grp.prescurtare}}</strong>, Dep. <strong>{{json.departamente[grp.parinte].prescurtare}}</strong>
                                <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" 
                                  ng-click="stergeGrup(grp.id)"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading" ng-click="form.visible=!form.visible"><h4>Adaugă <span class="glyphicon glyphicon-plus pull-right" ng-show="!form.visible"></span> <span ng-show="form.visible" class="glyphicon glyphicon-minus pull-right"></span></h4></div>
                    <div class="panel-body" ng-show="form.visible">
                        <select class="form-control" ng-model="form.optiune">
                            <option value="1" selected="selected">Facultate</option>
                            <option value="2">Departament</option>
                            <option value="3">Grup</option>
                        </select> <br/>
                        <input class="form-control" type="text" placeholder="Denumire" ng-model="form.denumire"/> <br/>
                        <input class="form-control" type="text" placeholder="Prescurtarea" ng-model="form.prescurtare"/> <br/>
                        <select class="form-control" ng-model="form.suboptiune"> 
                            <option ng-repeat="x in getSuboption()" value="{{x.id}}">{{x.denumire}}</option>
                        </select> <br/>
                        <button class="form-control btn btn-success" ng-click="salveaza()">Adaugă</button>
                        <br/>
                        <br/>
                        <button ng-show="form.modificari" class="form-control btn btn-danger" ng-click="saveUnitate()">Salvează modificările</button>
                    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.json = {};
    $scope.json.departamente=[];
    $scope.json.facultati=[];
    $scope.json.grupuri=[];
    $scope.form={};
    $scope.form.visible=false;
    $scope.form.optiune='1';
    $scope.form.denumire='';
    $scope.form.prescurtare='';
    $scope.form.suboptiune = '1';
    $scope.form.modificari = false;
    $scope.getUnitate = function() { 
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getunitate"])?>').then(function(response){
            $scope.json = response.data;
        }); 
    };
    Promise.all([
        $scope.getUnitate()
    ]).then(function(data){
        //afisare
        $scope.getSuboption = function () {
            switch($scope.form.optiune) {
                case '1': //facultate
                    return null;
                case '2': //departament
                    return $scope.json.facultati;
                case '3': //grup
                    return $scope.json.departamente;
                default:
            }
        };
        //actionarea butonului salveaza din formularul de adaugare
        $scope.salveaza = function() {
            switch($scope.form.optiune) {
                case '1':
                    $scope.addFacultate();
                    break;
                case '2':
                    $scope.addDepartament();
                    break;
                case '3':
                    $scope.addGrup();
                    break;
                default:
                    //nimic;
            }
            $scope.form.modificari = true;
        };
        //functii adaugare
        $scope.addFacultate = function() {
            var fac = {
                id:$scope.getID('facultati'),
                denumire:$scope.form.denumire,
                prescurtare:$scope.form.prescurtare
            };
            $scope.json.facultati.push(fac);
        };
        $scope.stergeFacultate = function(ids) {
            var tab = $scope.json.facultati;
            for(var i=0;i<tab.length;i++){
                if(tab[i].id == ids) {
                    $scope.json.facultati.splice(i,1);
                    $scope.form.modificari = true;
                    return;
                }
            }
        };
        $scope.addDepartament = function() {
            var dep = {
                id:$scope.getID('departamente'),
                denumire:$scope.form.denumire,
                prescurtare:$scope.form.prescurtare,
                parinte:$scope.form.suboptiune
            };
            $scope.json.departamente.push(dep)
        };
        $scope.stergeDepartament = function(ids) {
            var tab = $scope.json.departamente;
            for(var i=0;i<tab.length;i++){
                if(tab[i].id == ids) {
                    $scope.json.departamente.splice(i,1);
                    $scope.form.modificari = true;
                    return;
                }
            }
        };
        $scope.addGrup = function() {
            var grp = {
                id:$scope.getID('grupuri'),
                denumire:$scope.form.denumire,
                prescurtare:$scope.form.prescurtare,
                parinte:$scope.form.suboptiune
            };
            $scope.json.grupuri.push(grp);
        };
        $scope.stergeGrup = function(ids) {
            var tab = $scope.json.grupuri;
            for(var i=0;i<tab.length;i++){
                if(tab[i].id == ids) {
                    $scope.json.grupuri.splice(i,1);
                    $scope.form.modificari = true;
                    return;
                }
            }
        };
        //cauta urmatorul id pentru inserarea in baza de date
        $scope.getID = function(opt) {
            var tablou = $scope.json[opt];
            var id=-1;
            for(var i=0;i<tablou.length;i++){
                if(tablou[i].id > id) id = tablou[i].id;
            }
            return id+1;                
        };
        //trimite prin POST tot elementul JSON catre DB
        $scope.saveUnitate = function() {
            var param = {
                datele:$scope.json
            };
            var config = {
                headers : {
                    'Content-Type': 'application/json' //'application/x-www-form-urlencoded;charset=UTF-8'
                }
            };
            $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'saveunitate'])?>",param,config).then(function(response){
                console.log(response);
            });
        };
    });
        
}]);
</script>