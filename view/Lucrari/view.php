<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-8">
        
        <div ng-hide="md">
            <h1>Datele nu există pe server</h1>
        </div>
        <div ng-show="md">
            <h2>{{md.titlu}}</h2>
            <h2><small>{{autori(md.id)}}</small></h2>
            <p>Publicat in {{md.anulPublicarii}}</p>
            <hr>
            <p>{{md.abstract}}</p>
            <hr>
            <p>Indexare {{getIndexareText(md.indexare)}}</p>
            <p>{{getText(md.volum,'Volumul')}} {{getText(md.pagini,'Pag')}}</p>

            <p>{{getText(md.conferinta,'Conferinta')}}</p>
        </div>
    </div>
    <div class="col-md-4" ng-show="md">
        <button ng-click="editeaza(md.id)" class="btn btn-success">Editează</button> 
        <button ng-click="sterge(md.id)" class="btn btn-success">Șterge</button><br/>
        <hr/>
        <h4>Linkuri</h4>
        <a ng-show="md.linkLocal.length" href='{{md.linkLocal}}'>LOCAL</a> 
        <a ng-show="md.link.length" href='{{md.link}}'>REMOTE</a>
        <h4>Citări:</h4>
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
                {{c.an}}
                <a ng-show="c.urlLocal.length" href="{{c.urlLocal}}">LOCAL</a>, 
                <a ng-show="c.urlLocal.length" href="{{c.urlRemote}}">REMOTE</a>
            </li>
        </ul>
        
    </div>
</div>
<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.lucrareCurenta = <?=$this->data['id']?>;
    $scope.json = {};
    $scope.md = null;
    $scope.getLucrari = function() { 
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"utilizator","id"=>App::$app->user->getId()])?>').then(function(response){
            $scope.json.lucrari = response.data;
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
    
    Promise.all([
        $scope.getLucrari(),
        $scope.getAutori(),
        $scope.getIndexari()
    ]).then(function(){
        $scope.md = $scope.json.lucrari.find(lucrare=>{return lucrare.id==$scope.lucrareCurenta;});
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
                if(Number($scope.md.citari[i].an) === Number(year)) ret++;
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
        $scope.autori = function(ids) {
            var ret ="";
            var lucrare = $scope.json.lucrari.find(lucrare=>{return lucrare.id==ids;});
            if(lucrare == null) return ret;
            for(var i=0;i<lucrare.autori.length;i++) {
                ret += $scope.getAutorName(lucrare.autori[i]);
                if(i<lucrare.autori.length-1) ret +=", ";
            }
            console.log(ret);
            return ret;
        };
        $scope.getText = function(text,add) {
            if(text == null || text.length == 0) return "";
            else return add+" "+text;
        };
        $scope.getIndexareText = function(ids) {
            if(ids) return $scope.json.indexari.find(ind => Number(ind.id) === Number(ids) ).denumire;
        };
        
        $scope.editeaza = function(ids) {
            window.location.href = '<?=Helpers::generateUrl(["c"=>"lucrari","a"=>"edit"])?>/'+ids;
        };
        $scope.sterge = function(ids) {
            if(confirm("Sunteti sigur ca doriti sa stergeti inregistrarea "+ids+"?")) {
                $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"stergelucrarea"])?>/'+ids).then(function(response){
                    window.location.href = '<?=Helpers::generateUrl(["c"=>"lucrari","a"=>"index"])?>';
                }); 
            }
            
        };
    
        $scope.$apply(function(){
            $scope.graphData();
        });
        
    });
    
}]);
</script>