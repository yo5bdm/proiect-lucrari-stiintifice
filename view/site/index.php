<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-xs-12" >
        <h2 class="text-center"><?=App::$app->settings->numeAplicatie?></h2>
        <p ng-show="interogare==false">&nbsp</p>
        <div class="input-group">
            <input ng-model="interogareText" 
                   ng-model-options="{ getterSetter: true }"
                   class="form-control" 
                   placeholder="Caută lucrare, autor, grup [DE IMPLEMENTAT]" />
            <span class="input-group-btn">
              <button class="btn btn-default" ng-click="interogheaza()" type="button">Caută</button>
            </span>
        </div>
    </div>
    
    <div class="col-xs-12" ng-show="interogare==false">
        <hr/>
        <h4 class="text-center">{{json.autori.length}} autori au publicat {{lucrari.length}} lucrări, totalizând {{countCitari()}} citări</h4>
    </div>
    
    <div class="col-xs-12" ng-show="interogare==true">
        
        <div class="row"> 
            <div class="col-xs-12">
                <hr>
                <p>Lucrări:</p>
                <ul class="list-group"> <!-- START afisare lucrari gasite -->
                    <li class="list-group-item" ng-repeat="x in lucrariFiltrate = (lucrari | filter: interogareText)">
                        <div ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal">
                            <h3>"{{x.titlu}}" <small>{{autori(x.id)}}</small></h3>
                            <p>Anul publicarii {{x.anulPublicarii}}; </p>
                            <p>Citat de {{x.citari.length}} ori; Linkuri <a href='{{x.link}}'>REMOTE</a>; <a href='{{x.linkLocal}}'>LOCAL</a></p>
                        </div>
                    </li>
                    <li class="list-group-item" ng-hide="lucrariFiltrate.length">Nu există lucrări corespunzătoare acestui filtru.</li>
                </ul> <!-- END afisare lucrari gasite -->        
        
        
            </div>
        </div> 
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
            <hr>
            <p>{{md.abstract}}</p>
            <hr>
            <p>{{getText(md.volum,'Volumul')}} {{getText(md.pagini,'Pag')}}</p>
            <p>Publicat in {{md.anulPublicarii}}</p>
            <p>{{getText(md.conferinta,'Conferinta')}}</p>
            <p>Citări:</p>
            <canvas id="bar" class="chart chart-bar"
  chart-data="data" chart-labels="labels"> chart-series="series"
            </canvas>
            <ul class="list-group">
                <li class="list-group-item" ng-repeat="c in md.citari">
                    {{c.descriere}}, 
                    {{c.an}}, 
                    <a href="{{c.urlLocal}}">Link Local</a> 
                    <a href="{{c.urlRemote}}">Link Remote</a>
                </li>
            </ul>
            <p><a href='{{md.link}}'>Link</a>; <a href='{{md.linkLocal}}'>Link local</a></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

  </div>
</div> <!-- END modal -->
    
</div> <!-- END ng-app -->


<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.interogare = false;
    $scope.interogareText = "";
    $scope.controlShow = function() {
        if($scope.interogareText.length == 0) {
            $scope.interogare = false;
        } else {
            $scope.interogare = true;
        }
    }
    $scope.interogheaza = function() {
        $scope.controlShow();
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
        $scope.md = {};
        //graph data
        $scope.labels = []; //http://www.chartjs.org/docs/latest/
        $scope.data = [];   //http://jtblin.github.io/angular-chart.js/
        $scope.graphData = function() {
            //$scope.md - aici e lucrarea
            var min = $scope.getMinYear();
            var max = $scope.getMaxYear();
            $scope.labels = [];
            $scope.data = [];
            for(var i=min;i<=max;i++) {
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
        $scope.countCitari = function(){
            var lucrari = $scope.lucrari;
            var count = 0;
            for(var i=0;i<lucrari.length;i++) {
                count += lucrari[i].citari.length;
            }
            return count;
        };
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
        $scope.modal = function(id) {
            $scope.currentId = id;
            $scope.md = $scope.getLucrare(id);
            $scope.graphData();
        };
        $scope.$apply(function(){
            $scope.getAutorName($scope.myId);
            $scope.controlShow();
        });
    });
}]);
</script>