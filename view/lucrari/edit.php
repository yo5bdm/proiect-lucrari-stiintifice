<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" class="row" ng-app="myApp" ng-controller="myCtrl">
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
                <p>Autori selectati: 
                    <span ng-repeat="x in lucrare.autori"> {{getNameById(x)}},</span>
                </p>
                <p></p><p><textarea placeholder="Abstract" class="form-control" rows="6" name="abstract" ng-model="lucrare.abstract"></textarea></p>
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
app.controller("myCtrl", ['$scope','$http',function($scope,$http) {
    
    $scope.json = {};
    $scope.lucrare = {};
    $scope.lucrareID = <?=$this->data['id']?>;
    
    $scope.getAutori = function() {
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getusers"])?>').then(function(response){
            $scope.json.autori = response.data;
        }); 
    };
    
    $scope.getLucrarea = function() {
        return $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"getlucrare"])?>/'+$scope.lucrareID).then(function(response){
            $scope.lucrare = response.data;
        });
    };
            
Promise.all([
    $scope.getAutori(),
    $scope.getLucrarea()
]).then(function(){
    $scope.currentUser = {"id":"<?=App::$app->user->getId()?>","nume":"<?=App::$app->user->getName()?>"};
    
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
    $scope.addCitare = function() {
        var citare = {
            descriere: $scope.citare.descriere,
            an: $scope.citare.an,
            urlLocal: $scope.citare.urlLocal,
            urlRemote: $scope.citare.urlRemote
        };
        $scope.lucrare.citari.push(citare);
        //console.log(citare);
        $scope.citare.descriere=""; //reset formular
        $scope.citare.an="";
        $scope.citare.urlLocal="";
        $scope.citare.urlRemote="";
    };
    $scope.getNameById = function(ids) {
        var autori = $scope.json.autori;
        for(var i=0;i<autori.length;i++){
            if(autori[i].id==ids) return autori[i].nume+" "+autori[i].prenume;
        }
    };
    
    $scope.salveaza = function() {
        var param = {
            lucrareid:$scope.lucrareID,
            datele:$scope.lucrare
        };
        console.log($scope.lucrare);
        var config = {
            headers : {
                'Content-Type': 'application/json' //'application/x-www-form-urlencoded;charset=UTF-8'
            }
        };
        $http.post("<?= Helpers::generateUrl(['c'=>'json','a'=>'updatelucrare'])?>",param,config).then(function(response){
            console.log(response);
        });
    };
    $scope.$apply(function(){
        $scope.getNameById($scope.currentUser.id);
    });
});
}]);
</script>