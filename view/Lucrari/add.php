<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" class="row" ng-app="myApp" ng-controller="myCtrl">
        <div ng-show="salvat" class="alert alert-warning" role="alert">Modificările au fost salvate!</div>
        <!-- START NG-APP -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="card-title text-center">Adauga o lucrare noua</h3>
            </div>
            <div class="panel-body">
                <p><input name="titlu" ng-model="lucrare.titlu" placeholder="Titlu" class="form-control" type="text"></p>
                <p></p>
                <div class="input-group">
                    <select name="autori" ng-model="filtru.autori" class="form-control" placeholder="Autori" >
                        <option ng-repeat="x in json.autori" value="{{x.id}}">{{x.nume + ' ' + x.prenume}}</option>
                    </select>
                    <span class="input-group-btn">
                      <button class="btn btn-default" ng-click="addAutori()" type="button">Adauga</button>
                    </span>
                </div>
                <p></p>
                <ul class="list-group">
                    <li class="list-group-item" ng-repeat="x in lucrare.autori">{{getNameById(x)}}
                        <span class="glyphicon glyphicon-remove pull-right" title="Sterge autorul" ng-click="stergeAutorul($index)"></span>
                    </li>
                </ul>
                <hr/>
                <p>Indexare 
                    <select class="form-control" ng-model="lucrare.indexare">
                        <option ng-repeat="x in json.indexari" value="{{x.id}}">{{x.denumire}}</option>
                    </select>
                </p>
                <p><textarea placeholder="Abstract" class="form-control" rows="6" name="abstract" ng-model="lucrare.abstract"></textarea></p>
                <p><input name="volum" ng-model="lucrare.volum" placeholder="Volum" class="form-control" type="text"></p>
                <p><input name="pagini" ng-model="lucrare.pagini" placeholder="Pagini" class="form-control" type="text"></p>
                <p><input name="conferinta" ng-model="lucrare.conferinta" placeholder="Conferinta" class="form-control" type="text"></p>
                <p><input name="anulPublicarii" ng-model="lucrare.anulPublicarii" placeholder="AnulPublicarii" class="form-control" type="text"></p>
                <p><input name="link" ng-model="lucrare.link" placeholder="Link" class="form-control" type="text"></p>
                <p><input name="linkLocal" ng-model="lucrare.linkLocal" placeholder="LinkLocal" class="form-control" type="text"></p>
                <hr/>
                <div>
                    <button data-toggle="modal" data-target="#myModal" class="form-control btn btn-default" type="button">Adauga Citare</button>
                    <ul class="list-group" >
                        <li class="list-group-item" ng-repeat="x in lucrare.citari">
                            {{x.descriere}}, {{x.an}}, <a href="{{x.urlRemote}}">Link</a>, <a href="{{x.urlLocal}}">Link Local</a> 
                            <span class="glyphicon glyphicon-remove pull-right" title="Sterge selectia" ng-click="stergeCitarea($index)"></span>
                        </li>
                    </ul>
                </div>
                <hr/>
                <p></p>
                <p></p>
                <button class="form-control btn btn-success" ng-click="salveaza()">Salveaza</button>
            </div>
        </div>
    
<!-- MODAL ADAUGARE CITARE -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Adauga citare</h4>
        </div>
            <div class="modal-body">
            <?=Helpers::formular(new Citare(), 'citare')?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" ng-click="addCitare()">Salvează</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Închide</button>
        </div>
    </div>

  </div>
</div>
                <!-- END NG-APP -->
</div>
</div>

<script type="text/javascript"> 
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http','$timeout', function($scope,$http,$timeout) {
    $scope.json = {};
    $scope.salvat = false;
    
    $scope.getAutori = function() { 
        $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getusers"])?>').then(function(response){
            $scope.json.autori = response.data;
        }); 
    };
    $scope.getIndexari = function() {
        $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getcategorii"])?>').then(function(response){
            $scope.json.indexari = response.data;
        }); 
    };
            
Promise.all([
    $scope.getAutori(),
    $scope.getIndexari()
]).then(function(){
    $scope.currentUser = {"id":"<?=App::$app->user->getId()?>","nume":"<?=App::$app->user->getName()?>"};
    $scope.lucrare = {};
    $scope.lucrare.titlu="";
    $scope.lucrare.abstract="";
    $scope.lucrare.indexare='1';
    $scope.lucrare.volum="";
    $scope.lucrare.pagini="";
    $scope.lucrare.conferinta="";
    $scope.lucrare.anulPublicarii="";
    $scope.lucrare.link=""
    $scope.lucrare.linkLocal="";
    $scope.lucrare.autori=[];
    $scope.lucrare.citari=[];
    
    $scope.citare = {};
    $scope.citare.descriere="";
    $scope.citare.an="<?=date('Y')?>";
    $scope.citare.urlLocal="";
    $scope.citare.urlRemote="";
    
    $scope.filtru ={};
    $scope.filtru.autori="<?=App::$app->user->getId()?>";
    $scope.filtru.citari="";
    $scope.addAutori = function() {
        $scope.lucrare.autori.push($scope.filtru.autori);
    };
    $scope.stergeAutorul = function(index) {
        $scope.lucrare.autori.splice(index,1);
    };
    $scope.addCitare = function() {
        var citare = {
            descriere: $scope.citare.descriere,
            an: $scope.citare.an,
            urlLocal: $scope.citare.urlLocal,
            urlRemote: $scope.citare.urlRemote
        };
        $scope.lucrare.citari.push(citare);
        $scope.citare.descriere=""; //reset formular
        $scope.citare.an="";
        $scope.citare.urlLocal="";
        $scope.citare.urlRemote="";
    };
    $scope.stergeCitarea = function (index) {
        $scope.lucrare.citari.splice(index,1);
    };
    $scope.getNameById = function(ids) {
        var autori = $scope.json.autori;
        for(var i=0;i<autori.length;i++){
            if(autori[i].id==ids) return autori[i].nume+" "+autori[i].prenume;
        }
    };
    
    $scope.salveaza = function() {
        var param = {
            datele:$scope.lucrare
        };
        var config = {
            headers : {
                'Content-Type': 'application/json'
            }
        };
        $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'savelucrare'])?>",param,config).then(function(response){
            console.log(response);
            $scope.salvat = true;
            $timeout(function() { $scope.salvat=false; }, 3000);
        });
    };
});
}]);
</script>