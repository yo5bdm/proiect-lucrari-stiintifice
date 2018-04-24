<style>
    .btn{
        white-space:normal !important;
        word-wrap: break-word; 
        word-break: normal;
        margin-bottom: 4px;
    }
</style>

<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-8">
        <div ng-show="salvat" class="alert alert-warning" role="alert">Modificările au fost salvate!</div>
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
        <div class="row">
            <div class="col-md-6">
                <p><button data-toggle="modal" data-target="#modalCitare" class="form-control btn btn-default">Adaugă citare</button></p>
                <p><button data-toggle="modal" data-target="#modalLink" class="form-control btn btn-default" title="Adaugă un link remote la lucrare">Adaugă link remote</button></p>
                <p><button data-toggle="modal" data-target="#modalDB" class="form-control btn btn-default" title="Adaugă bază de date ce indexează lucrarea">Adaugă DB</button></p>
            </div>
            <div class="col-md-6">
                <p><button ng-click="editeaza(md.id)" class="form-control btn btn-default">Editează</button></p>
                <p><button ng-click="sterge(md.id)" class="form-control btn btn-default">Șterge</button></p>
                <p ng-show="modificari"><button class="form-control btn btn-danger" ng-click="salveaza()">Salvează modificările</button></p>
            </div>
        </div>
        
        
        
        <hr/>
        <h4 ng-show="md.linkLocal.length || md.linkuri.length">Linkuri</h4>
        <ul class="list-group">
            <li class="list-group-item" ng-show="md.linkLocal.length">
                <a href='{{md.linkLocal}}'>Link Local</a>
            </li>
            <li class="list-group-item" ng-repeat="c in md.linkuri">
                <a href="{{c}}">Link remote {{$index}}</a>
                <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" ng-click="stergeDB($index)"></span>
            </li>
        </ul>
        <h4 ng-show="md.bazededate.length">Baze de date ce indexează lucrarea</h4>
        <ul class="list-group">
            <li class="list-group-item" ng-repeat="x in md.bazededate">
                <a href="{{x.link}}">{{getDBText(x.id).denumire}}</a>
                <span class="glyphicon glyphicon-remove pull-right" ng-click="stergeDB($index)"></span>
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
                <a ng-show="c.urlLocal.length" href="{{c.urlLocal}}">LOCAL</a> 
                <a ng-show="c.urlLocal.length" href="{{c.urlRemote}}">REMOTE</a>
                <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" ng-click="stergeCitarea($index)"></span>
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

<!-- MODAL ADAUGARE LINK REMOTE -->
<div id="modalLink" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Link Remote la lucrare`</h4>
        </div>
        <div class="modal-body">
            <input type="text" class="form-control" ng-model="linkRemote" placeholder="Link"/>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" ng-click="addLinkRemote()">Salvează</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Închide</button>
        </div>
    </div>

  </div>
</div>
    
    
</div>
<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http','$timeout', function($scope,$http,$timeout) {
    $scope.lucrareCurenta = <?=$this->data['id']?>;
    $scope.modificari = false;
    $scope.salvat = false;
    $scope.json = {};
    $scope.md = null;
    $scope.citare = {
        descriere:"",
        an:"<?=date('Y')?>",
        urlLocal:"",
        urlRemote:""
    };
    $scope.bazadedate = {
        id:'1',
        link:''
    };
    $scope.linkRemote='';
    
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
    $scope.getBazeDeDate = function() {
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getbazededate"])?>').then(function(response){
            $scope.json.bazededate = response.data;
        }); 
    };
    
    Promise.all([
        $scope.getLucrari(),
        $scope.getAutori(),
        $scope.getIndexari(),
        $scope.getBazeDeDate()
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
        
        $scope.addCitare = function() {
            var citare = {
                descriere: $scope.citare.descriere,
                an: $scope.citare.an,
                urlLocal: $scope.citare.urlLocal,
                urlRemote: $scope.citare.urlRemote
            };
            $scope.md.citari.push(citare);
            $scope.citare.descriere=""; //reset formular
            $scope.citare.an="";
            $scope.citare.urlLocal="";
            $scope.citare.urlRemote="";
            $scope.graphData();
            $scope.modificari = true;
        };
        $scope.stergeCitarea = function (index) {
            $scope.md.citari.splice(index,1);
            $scope.graphData();
            $scope.modificari = true;
        };
        
        $scope.addDB = function() {
            var db = {
                id: $scope.bazadedate.id,
                link:$scope.bazadedate.link
            };
            $scope.md.bazededate.push(db);
            $scope.bazadedate.link=""; //reset formular
            $scope.graphData();
            $scope.modificari = true;
        };
        $scope.stergeDB = function(index) {
            console.log(index);
            $scope.md.bazededate.splice(index,1);
            $scope.graphData();
            $scope.modificari = true;
        };
        $scope.getDBText = function(id) {
            return $scope.json.bazededate.find(function(val){
                return val.id == id;
            });
        };
        
        $scope.addLinkRemote = function() {
            $scope.md.linkuri.push($scope.linkRemote);
            $scope.linkRemote = '';
            $scope.graphData();
            $scope.modificari = true;
        };
        $scope.stergeLinkulRemote = function(index) {
            console.log(index);
            $scope.md.linkuri.splice(index,1);
            $scope.graphData();
            $scope.modificari = true;
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
        
        $scope.salveaza = function() {
        var param = {
            lucrareid:$scope.md.id,
            datele:$scope.md
        };
        var config = {
            headers : {
                'Content-Type': 'application/json' //'application/x-www-form-urlencoded;charset=UTF-8'
            }
        };
        $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'updatelucrare'])?>/",param,config).then(function(response){
            if(response.data==1) {
                $scope.salvat = true;
                $scope.modificari = false;
                $timeout(function() { $scope.salvat=false; }, 3000);
            }
            else {alert("Eroare la salvare");}
        });
    };
        
    });
    
}]);
</script>