<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-9">
        <div class="row">    
            <div class="col-xs-12" >
                <div class="row">
                    <div class="col-xs-8">
                        <h3>Lucrarile autorului <?=App::$app->user->getName()?></h3>
                    </div>
                </div>
            </div>
            <div class="col-xs-12" >
                <hr>
                <p ng-show="lucrari.length<1">Nu exista inregistrari</p>
                <p ng-show="lucrari.length>0">Sortează după: 
                    <button ng-click="propertyName = 'titlu'" 
                            ng-class="{'btn btn-success':propertyName=='titlu', 'btn btn-default':propertyName!='titlu'}">Titlu</button>
                    <button ng-click="propertyName = 'indexare'" ng-class="{'btn btn-success':propertyName=='indexare', 'btn btn-default':propertyName!='indexare'}">Indexare</button>
                    <button ng-click="propertyName = 'autori'" ng-class="{'btn btn-success':propertyName=='autori', 'btn btn-default':propertyName!='autori'}">Autor</button>
                    <button ng-click="propertyName = 'anulPublicarii'" ng-class="{'btn btn-success':propertyName=='anulPublicarii', 'btn btn-default':propertyName!='anulPublicarii'}">Anul publicării</button> | 
                    <button ng-click="reverse = false" ng-class="{'btn btn-success':reverse!=true, 'btn btn-default':reverse==true}"><span class="glyphicon glyphicon-arrow-up"></span></button> 
                    <button ng-click="reverse = true" ng-class="{'btn btn-success':reverse==true, 'btn btn-default':reverse!=true}"><span class="glyphicon glyphicon-arrow-down"></span></button>
                </p>
                <table class="table-bordered table table-condensed" ng-show="lucrari.length>0">
                    <tr>
                        <th ng-show="vizibil.titlu">Titlu</th>
                        <th ng-show="vizibil.autori">Autori</th>
                        <th ng-show="vizibil.anPublicare">An publicare</th>
                        <th ng-show="vizibil.indexare">Indexare</th>
                        <th ng-show="vizibil.linkuri">Linkuri</th>
                        <th ng-show="vizibil.actiuni">Actiuni</th>
                    </tr>
                    <tr ng-repeat="x in lucrariFiltrate() | orderBy:propertyName:reverse">
                        <td  ng-show="vizibil.titlu" ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal">{{x.titlu}}</td>
                        <td ng-show="vizibil.autori">{{autori(x.id)}}</td>
                        <td ng-show="vizibil.anPublicare">{{x.anulPublicarii}}</td>
                        <td ng-show="vizibil.indexare">{{getIndexareText(x.indexare)}}</td>
                        <td ng-show="vizibil.linkuri"><a href='{{x.link}}'>Remote</a> <a href='{{x.linkLocal}}'>Local</a></td>
                        <td ng-show="vizibil.actiuni">
                            <a ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal"><span title="Vizualizeaza" class="glyphicon glyphicon-zoom-in"></span></a>&nbsp;
                            <a ng-click="editeaza(x.id)"><span title="Editeaza" class="glyphicon glyphicon-wrench"></span></a>&nbsp;
                            <a ng-click="sterge(x.id)"><span title="Sterge" class="glyphicon glyphicon-remove"></span></a>
                        </td>
                    </tr>
                    
                </table>

        <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{md.titlu}}</h4>
                    </div>
                        <div class="modal-body">
                        <p>Autori: {{autori(md.id)}}</p>
                        <p>Indexare {{getIndexareText(md.indexare)}}</p>
                        <hr>
                        <p>{{md.abstract}}</p>
                        <hr>
                        <p>{{getText(md.volum,'Volumul')}} {{getText(md.pagini,'Pag')}}</p>
                        <p>Publicat in {{md.anulPublicarii}}</p>
                        <p>{{getText(md.conferinta,'Conferinta')}}</p>
                        <p>Linkuri lucrare 
                            <a href='{{md.link}}'>REMOTE</a>, 
                            <a href='{{md.linkLocal}}'>LOCAL</a>
                        </p>
                        <p>Citări:</p>
                        <canvas id="bar" 
                                class="chart chart-bar" 
                                chart-options="options"
                                chart-data="data" 
                                chart-labels="labels"> 
                                    chart-series="series"
                        </canvas>
                        <ul class="list-group">
                            <li class="list-group-item" ng-repeat="c in md.citari">
                                {{c.descriere}}<br/>
                                {{c.an}}, 
                                Linkuri 
                                <a href="{{c.urlLocal}}">LOCAL</a>, 
                                <a href="{{c.urlRemote}}">REMOTE</a>
                            </li>
                        </ul>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="col-md-3">
        <p>Filtrare după text:<input type="text" ng-model="filtru" class="form-control" placeholder="Text"/></p>
        <p>Ultimii x ani:<input type="number" ng-model="filtruAni" class="form-control" placeholder="Ultimii x ani"/></p>
        <p><small>{{anMinim}}</small> <a class="pull-right" ng-click="resetFiltrare()">Reset</a></p>
        <hr/>
        <p>Formatul dorit pentru nume autor:
            <select class="form-control" ng-model="formatNume">
                <option ng-repeat="x in optiuniFormatNume" value="{{$index}}">{{x}}</option>
            </select>
        </p>
        <hr/>
        <h6>Coloane vizibile</h6>
        <p><input type="checkbox" ng-model="vizibil.titlu"/> Titlu</p>
        <p><input type="checkbox" ng-model="vizibil.autori"/> Autori</p>
        <p><input type="checkbox" ng-model="vizibil.anPublicare"/> An publicare</p>
        <p><input type="checkbox" ng-model="vizibil.indexare"/> Indexare</p>
        <p><input type="checkbox" ng-model="vizibil.linkuri"/> Linkuri</p>
        <p><input type="checkbox" ng-model="vizibil.actiuni"/> Actiuni</p>
    </div>
</div> 

<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.vizibil ={
        titlu:true,
        autori:true,
        anPublicare:true,
        indexare:true,
        linkuri:false,
        actiuni:true
    };
    $scope.propertyName = 'anulPublicarii';
    $scope.formatNume = '1';
    $scope.optiuniFormatNume = formatNume;
    $scope.reverse = false;
    $scope.options ={ //optiuni pentru chart.js
        scales: {
            yAxes: [{
                type: 'linear',
                ticks: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }]
        }
    };            
    $scope.json={
        autori:[],
        indexari:[]
    };
    $scope.lucrari=[];
    $scope.myId = "<?=App::$app->user->getId()?>";
    $scope.filtru='';
    $scope.filtruAni='Toti anii';
    $scope.anMinim='Toti anii';
    $scope.currentId;
    $scope.md = {};
    $scope.resetFiltrare = function() {
        $scope.filtruAni = NaN;
        $scope.filtru='';
    };
    
    $scope.getLucrari = function() { 
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"utilizator","id"=>App::$app->user->getId()])?>').then(function(response){
            $scope.lucrari = response.data;
        }); 
    };
    $scope.getAutori = function() {
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getusers"])?>').then(function(response){
            $scope.json.autori = response.data;
        });
    };
    $scope.getIndexari = function() {
        $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getcategorii"])?>').then(function(response){
            $scope.json.indexari = response.data;
        }); 
    };
    
    //metode
    Promise.all([
        $scope.getLucrari(),
        $scope.getAutori(),
        $scope.getIndexari()
    ]).then(function(data){
        $scope.labels = []; //http://www.chartjs.org/docs/latest/
        $scope.data = [];   //http://jtblin.github.io/angular-chart.js/
        $scope.graphData = function() {
            //$scope.md - aici e lucrarea
            var min = $scope.getMinYear();
            var max = $scope.getMaxYear();
            $scope.labels = []; //clear old data
            $scope.data = [];
            for(var i=min;i<=max;i++) { //insert new data
                $scope.labels.push(Number(i));
                $scope.data.push($scope.countYear(Number(i)));
            }
        };
        $scope.getMinYear = function() {
            var year = 9999;
            for(var i=0;i<$scope.md.citari.length;i++) {
                if(Number($scope.md.citari[i].an) < year) year = Number($scope.md.citari[i].an);
            }
            if(year!=9999) return year;
            else return 0; //nu am gasit nimic
        };
        $scope.getMaxYear = function() {
            var year = 0;
            for(var i=0;i<$scope.md.citari.length;i++) {
                if(Number($scope.md.citari[i].an) > year) year = Number($scope.md.citari[i].an);
            }
            return year; //nu am gasit nimic
        };
        $scope.countYear = function(year) {
            var ret = 0;
            for(var i=0;i<$scope.md.citari.length;i++) {
                if(Number($scope.md.citari[i].an) == year) ret++;
            }
            return ret;
        };
        
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
                    return afiseazaNume(listaAutori[i].nume,listaAutori[i].prenume,$scope.formatNume);
            }
            return ids;
        };
        $scope.getText = function(text,add) {
            if(text == null || text.length == 0) return "";
            else return add+" "+text;
        };
        $scope.getIndexareText = function(ids) {
            if(ids) return $scope.json.indexari.find(ind => Number(ind.id) === Number(ids) ).denumire;
        };
            
        $scope.modal = function(id) {
            $scope.currentId = id;
            $scope.md = $scope.getLucrare(id);
            $scope.graphData();
        };
        $scope.editeaza = function(ids) {
            window.location.href = '<?=Helpers::generateUrl(["c"=>"lucrari","a"=>"edit"])?>/'+ids;
        };
        $scope.sterge = function(ids) {
            if(confirm("Sunteti sigur ca doriti sa stergeti inregistrarea "+ids+"?")) {
                $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"stergelucrarea"])?>/'+ids).then(function(response){
                    $scope.getLucrari();
                    $scope.getAutori();
                }); 
            }
            
        };
        
        $scope.lucrariFiltrate = function() {
            if((isNaN($scope.filtruAni) || $scope.filtruAni==0) && $scope.filtru.length==0) {
                $scope.anMinim='Toti anii';
                $scope.filtruAni = NaN;
                return $scope.lucrari; 
            }
            if(Number($scope.filtruAni)!=0 && !isNaN($scope.filtruAni)) {
                var anMinim = <?=date('Y')?>-$scope.filtruAni;
                $scope.anMinim = anMinim + " - " +<?=date('Y')?>;
            } else {
                $scope.anMinim='Toti anii';
                $scope.filtruAni = NaN;
                var anMinim = 0;
            }
            if($scope.filtru.length!=0) var filtru = $scope.filtru;
            else var filtru = null;
            return $scope.lucrari.filter(function(lucrare){
                return ((lucrare.titlu.indexOf(filtru) != -1 ||
                    lucrare.abstract.indexOf(filtru) != -1 ||
                    lucrare.volum.indexOf(filtru) != -1) &&
                    Number(lucrare.anulPublicarii) >= Number(anMinim)
                );
            });
        };
        
        $scope.$apply(function(){
            $scope.getAutorName($scope.myId);
        });
        
    });
}]);
</script>