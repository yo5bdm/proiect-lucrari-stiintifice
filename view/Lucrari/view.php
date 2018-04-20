<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-8">
        
        <div ng-hide="md">
            <h1>Datele nu există pe server</h1>
        </div>
        <div ng-show="md">
            <h2>{{md.titlu}}</h2>
            <h2><small>{{autori(md.id)}}</small></h2>
            <p><small>Cuvinte cheie: <strong><i>{{md.keywords}}</i></strong></small></p>
            <p>Anul publicării: <strong>{{md.anulPublicarii}}</strong></p>
            <hr>
            <p>{{md.abstract}}</p>
            <hr>
            <p>Indexare <strong>{{getIndexareText(md.indexare)}}</strong>; {{getText(md.volum,'Volumul')}} {{getText(md.pagini,'Pag')}}</p>
            <p>{{getText(md.conferinta,'Conferinta')}}</p>
        </div>
    </div>
    <div class="col-md-4" ng-show="md">
        <p><button ng-click="editeaza(md.id)" class="form-control btn btn-default">Editează</button></p>
        <p><button ng-click="sterge(md.id)" class="form-control btn btn-default">Șterge</button></p>
        <p><button data-toggle="modal" data-target="#modalCitare" class="form-control btn btn-default">Adaugă citare</button></p>
        <p><button data-toggle="modal" data-target="#modalLink" class="form-control btn btn-default">Adaugă link remote la lucrare</button></p>
        <p><button data-toggle="modal" data-target="#modalDB" class="form-control btn btn-default">Adaugă bază de date ce indexează lucrarea</button></p>
        <hr/>
        <h4 ng-show="md.linkLocal.length || md.linkuri.length">Linkuri</h4>
        <ul class="list-group">
            <li class="list-group-item" ng-show="md.linkLocal.length">
                <a href='{{md.linkLocal}}'>Link Local</a>
            </li>
            <li class="list-group-item" ng-repeat="c in md.linkuri">
                <a href="{{x}}">Link remote {{$index}}</a>
            </li>
        </ul>
        <h4 ng-show="md.citari.length">Citări:</h4>
        <canvas id="bar" ng-show="md.citari.length"
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
    
    <!-- MODAL ADAUGARE CITARE -->
<div id="modalCitare" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Adauga citare</h4>
        </div>
            <div class="modal-body">
                <?php 
                    $form = new Form(new Citare());
                    echo $form->setNgModel('citare')->setAllRequired(false)->generate();
                ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" ng-click="addCitare()">Salvează</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Închide</button>
        </div>
    </div>

  </div>
</div>

<!-- MODAL ADAUGARE Baze de date -->
<div id="modalDB" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Bază de date ce indexează lucrarea</h4>
        </div>
        <div class="modal-body">
            <select class="form-control" ng-model="bazadedate.id">
                <option ng-repeat="x in json.bazededate" value="{{x.id}}">{{x.denumire}}</option>
            </select> <br/>
            <input type="text" class="form-control" ng-model="bazadedate.link" placeholder="Link"/>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" ng-click="addDB()">Salvează</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Închide</button>
        </div>
    </div>

  </div>
</div>
    
    
</div>
<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.lucrareCurenta = <?=$this->data['id']?>;
    $scope.json = {};
    $scope.md = null;
    $scope.getLucrari = function() { 
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"toatelucrarile","id"=>App::$app->user->getId()])?>').then(function(response){
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
            var autor = $scope.json.autori.find(aut=>{return aut.id == ids;});
            if(autor) return afiseazaNume(autor.nume,autor.prenume,0);
            else return ids;
        };
        
        $scope.autori = function(ids) {
            var ret ="";
            var lucrare = $scope.json.lucrari.find(lucrare=>{return lucrare.id==ids;});
            if(lucrare == null) return ret;
            for(var i=0;i<lucrare.autori.length;i++) {
                ret += $scope.getAutorName(lucrare.autori[i]);
                if(i<lucrare.autori.length-1) ret +=", ";
            }
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