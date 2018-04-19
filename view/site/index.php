<script data-require="ui-bootstrap@*" data-semver="0.12.1" src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.12.1.min.js"></script>
<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div ng-class="{'col-md-8':advanced, 'col-md-12':!advanced}" >
        <h2 class="text-center"><?=App::$app->settings->numeAplicatie?></h2>
        <p ng-show="interogare==false">&nbsp</p>
        <div ng-hide="advanced">
            <input ng-model="interogareText" 
                   ng-model-options="{ getterSetter: true }"
                   class="form-control" 
                   placeholder="Caută titlu lucrare, autor, grup" />
            <a class="pull-right" ng-click="advanced=!advanced">Căutare {{advanced==true?'simplă':'avansată'}}</a>
        </div>
        
        <hr/>
        
        <div ng-show="interogare==false">
            <h4 class="text-center">{{json.autori.length}} autori au publicat {{lucrari.length}} lucrări, totalizând {{countCitari()}} citări</h4>
        </div>
        
        <div ng-show="interogare==true">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#lucrari">Lucrari ({{lucrariFiltrateNP.length}})</a></li>
                <li><a data-toggle="tab" href="#autori">Autori</a></li>
            </ul>
            <div class="tab-content">
                <div id="lucrari" class="tab-pane fade in active">
                    <div class="row"> 
                        <div class="col-xs-12">
                            <pagination
                            ng-model="curPage"
                            total-items="lucrariFiltrateNP.length"
                            max-size="maxSize"
                            boundary-links="true">
                            </pagination>
                            <table class="table table-bordered table-condensed">
                                <tr ng-hide="lucrariFiltrate.length">
                                    <td><p>Nu există rezultate pentru filtrul curent</p></td>
                                </tr>
                                <tr ng-repeat="x in lucrariFiltrate">
                                    <td><strong>{{x.titlu}}</strong></td>
                                    <td>{{autori(x.id)}}</td>
                                    <td>{{x.anulPublicarii}}</td>
                                </tr>
                            </table> <!-- END afisare lucrari gasite -->        


                        </div>
                    </div> 
                </div>
                <div id="autori" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-4"><!-- START afisare autori gasiti -->
                            <hr>
                            <p>Autori:</p>
                            <ul class="list-group">
                                <li class="list-group-item" ng-repeat="x in autoriFiltru = (json.autori | filter:interogareText)">
                                    <h4>{{x.functia}} {{x.nume}} {{x.prenume}}</h4>
                                    <p>Lucrari publicate: </p>
                                </li>
                            </ul>
                        </div> <!-- END afisare autori gasiti -->
                        <div class="col-md-4"> <!-- START afisare grupuri, etc -->
                            &nbsp;
                        </div> <!-- END afisare grupuri, etc -->
                        <div class="col-md-4">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" ng-show="advanced">
        <h4>Căutare avansată</h4>
        <p><input ng-model="filtruAvansat.titlu" class="form-control" placeholder="Titlu"/></p>
        <p><input ng-model="filtruAvansat.abstract" class="form-control" placeholder="Abstract"/></p>
        <p><input ng-model="filtruAvansat.keywords" class="form-control" placeholder="Cuvinte cheie"/></p>
        <p><input ng-model="filtruAvansat.autor" class="form-control" placeholder="Autor"/></p>
        <p><input ng-model="filtruAvansat.volum" class="form-control" placeholder="Volum"/></p>
        <p><input ng-model="filtruAvansat.conferinta" class="form-control" placeholder="Conferinta"/></p>
        <p><input ng-model="filtruAvansat.anulPublicarii" class="form-control" placeholder="Anul publicarii"/></p>
        <a style="text-align: right;" ng-click="advanced=!advanced">Căutare {{advanced==true?'simplă':'avansată'}}</a>
    </div>
    
</div> <!-- END ng-app -->


<script type="text/javascript">
var app = angular.module("myApp", ['ui.bootstrap']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.filtruAvansat = {
        titlu:'',
        abstract:'',
        keywords:'',
        autor:'',
        volum:'',
        conferinta:'',
        anulPublicarii:''
    };
            
    $scope.advanced = false;
    $scope.lucrariFiltrate = [];
    $scope.curPage = 1;
    $scope.numPerPage=10;
    $scope.maxSize = 5;
    $scope.lucrariFiltrateNP = []; //nepaginate
            
    $scope.interogare = false;
    $scope.interogareText = "";
    $scope.controlShow = function() {
        if($scope.interogareText.length == 0) {
            $scope.interogare = false;
        } else {
            $scope.interogare = true;
        }
    };
    
    $scope.json={};
    $scope.json.autori=[];
    $scope.json.unitate=[];
    $scope.lucrari=[];
    $scope.getLucrari = function() { 
        $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"listalucrari"])?>').then(function(response){
            $scope.lucrari = response.data;
        }); 
    };
    $scope.getAutori = function() {
        $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getusers"])?>').then(function(response){
            $scope.json.autori = response.data;
        });
    };
    $scope.getUnitate = function() { 
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getunitate"])?>').then(function(response){
            $scope.json.unitate = response.data;
        }); 
    };
    //metode
    Promise.all([
        $scope.getLucrari(),
        $scope.getAutori(),
        $scope.getUnitate()
    ]).then(function(data){
        //filtre si altele
        $scope.myId = "<?=App::$app->user->getId()?>";
        $scope.filtru="";
        $scope.currentId;
        //metode diverse
        $scope.getLucrare = function(ids) {
            var lucrare;
            for(var i=0;i<$scope.lucrari.length;i++) {
                lucrare = $scope.lucrari[i];
                if(lucrare.id === ids) {
                    return lucrare;
                }
            }
            return null;
        };
        $scope.autori = function(ids) {
            var ret ="";
            var lucrare = $scope.getLucrare(ids);
            if(lucrare == null) return ret;
            for(var i=0;i<lucrare.autori.length;i++) {
                ret += $scope.getAutorName(lucrare.autori[i]);
                if(i<lucrare.autori.length-1) ret +=", ";
            }
            return ret;
        };
        $scope.getAutorName = function(ids) {
            var listaAutori = $scope.json.autori;
            for(var i=0; i<listaAutori.length; i++) {
                if(listaAutori[i].id == Number(ids)) 
                    return listaAutori[i].nume + " " + listaAutori[i].prenume;
            }
            return ids;
        };
        $scope.getText = function(text,add) {
            if(text == null || text.length == 0) return "<Necompletat>";
            else return add+" "+text;
        };
        
        $scope.update = function () {
            var begin = (($scope.curPage - 1) * $scope.numPerPage),
            end = begin + $scope.numPerPage;
            $scope.filtrare();
            $scope.lucrariFiltrate = $scope.lucrariFiltrateNP.slice(begin, end);
        };
        
        $scope.filtrare = function() {
            if(!$scope.advanced) {
                $scope.lucrariFiltrateNP = $scope.lucrari.filter(function(lucrare){
                    return lucrare.titlu.toLowerCase().indexOf($scope.interogareText)!=-1;
                }); 
            } else {
                let f = $scope.filtruAvansat;
                $scope.lucrariFiltrateNP = $scope.lucrari.filter(function(lucrare){
                    return (
                        lucrare.titlu.toLowerCase().indexOf(f.titlu)!=-1 ||
                        lucrare.abstract.toLowerCase().indexOf(f.abstract)!=-1
                            );
                }); 
            }
        };
        
        $scope.$watch('curPage + interogareText',function(){
            $scope.controlShow();
            $scope.update();
        });
        $scope.$apply(function(){
            $scope.getAutorName($scope.myId);
            $scope.controlShow();
        });
    });
}]);
</script>