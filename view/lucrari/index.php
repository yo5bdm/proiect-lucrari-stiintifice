<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-8">
        <div class="row">    
            <div class="col-xs-12" >
                <div class="row">
                    <div class="col-xs-8">
                        <h3>Lucrarile utilizatorului <?=App::$app->user->getName()?></h3>
                    </div>
                    <div class="col-xs-4 filtrare">
                        <div class="navbar-form navbar-right">
                            <input type="text" ng-model="filtru" class="form-control" placeholder="Filtrează"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12" >
                <hr>
                <p ng-show="lucrari.length<1">Nu exista inregistrari</p>
                <p ng-show="lucrari.length>0">Sortează după: 
                    <button ng-click="propertyName = 'titlu'" 
                            ng-class="{'btn btn-success':propertyName=='titlu', 'btn btn-default':propertyName!='titlu'}">Titlu</button>
                    <button ng-click="propertyName = 'autori'" ng-class="{'btn btn-success':propertyName=='autori', 'btn btn-default':propertyName!='autori'}">Autor</button>
                    <button ng-click="propertyName = 'anulPublicarii'" ng-class="{'btn btn-success':propertyName=='anulPublicarii', 'btn btn-default':propertyName!='anulPublicarii'}">Anul publicării</button> | 
                    <button ng-click="reverse = false" ng-class="{'btn btn-success':reverse!=true, 'btn btn-default':reverse==true}"><span class="glyphicon glyphicon-arrow-up"></span></button> 
                    <button ng-click="reverse = true" ng-class="{'btn btn-success':reverse==true, 'btn btn-default':reverse!=true}"><span class="glyphicon glyphicon-arrow-down"></span></button>
                </p>
                <ul class="list-group" ng-show="lucrari.length>0" >
                    <li class="list-group-item" 
                        ng-repeat="x in lucrari | filter: filtru | orderBy:propertyName:reverse">
                        <div class="row">
                            <div class="col-xs-8" ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal" >
                                <h3>"{{x.titlu}}" <small>{{autori(x.id)}}</small></h3>
                                <p>Anul publicarii {{x.anulPublicarii}}; {{getText(x.volum,'Vol:')}}, {{getText(x.pagini,'Pag:')}}</p>
                                <p>{{getText(x.conferinta,'Conferinta:')}}, Citări: {{x.citari.length}}, Indexare {{getIndexareText(x.indexare)}}</p>
                                <p></p>
                            </div>
                            <div class="col-xs-4">
                                <p>Linkuri <a href='{{x.link}}'>REMOTE</a>; <a href='{{x.linkLocal}}'>LOCAL</a></p>
                                <p>
                                    <button ng-click="editeaza(x.id)" class="btn btn-success">Editeaza</button>&nbsp;
                                    <button ng-click="sterge(x.id)" class="btn btn-danger">Sterge</button></p>
                            </div>
                        </div>
                    </li>
                </ul>

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
    <div class="col-md-4">
        <h2>Sidebar</h2>
        <p>Actiuni lucrari, descarcari, exporturi, etc</p>
    </div>
</div>

<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.propertyName = 'anulPublicarii';
    $scope.reverse = true;
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
    $scope.filtru="";
    $scope.currentId;
    $scope.md = {};
    
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
                    return listaAutori[i].nume + " " + listaAutori[i].prenume;
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
                    console.log(response.data);
                    $scope.getLucrari();
                    $scope.getAutori();
                }); 
            }
            
        };
        $scope.$apply(function(){
            $scope.getAutorName($scope.myId);
        });
        
    });
}]);
</script>