<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-xs-12" >
        <div class="row">
            <div class="col-xs-8">
                <h1>Lista de Lucrari</h1>
            </div>
            <div class="col-xs-4 filtrare">
                <div class="navbar-form navbar-right">
                    <input type="text" ng-model="filtru" class="form-control" placeholder="Filtrează"/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <hr>
        <p ng-show="lucrari.length<1">Nu exista inregistrari</p>
        <ul class="list-group" ng-show="lucrari.length>0" >
            <li class="list-group-item" ng-repeat="x in lucrari | filter: filtru">
                <div ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal">
                    <h3>"{{x.titlu}}" <small>{{autori(x.id)}}</small></h3>
                    <p>Anul publicarii {{x.anulPublicarii}}; {{getText(x.volum,'Vol:')}}, {{getText(x.pagini,'Pag:')}}</p>
                    <p>{{getText(x.conferinta,'Conferinta:')}}</p>
                    <p><a href='{{x.link}}'>Link</a>; <a href='{{x.linkLocal}}'>Link local</a></p>
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
            <hr>
            <p>{{md.abstract}}</p>
            <hr>
            <p>{{getText(md.volum,'Volumul')}} {{getText(md.pagini,'Pag')}}</p>
            <p>Publicat in {{md.anulPublicarii}}</p>
            <p>{{getText(md.conferinta,'Conferinta')}}</p>
            <p><a href='{{md.link}}'>Link</a>; <a href='{{md.linkLocal}}'>Link local</a></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

  </div>
</div>
    
    </div>
</div>


<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.json={};
    $scope.json.autori=[];
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
    //metode
    Promise.all([
        $scope.getLucrari(),
        $scope.getAutori()
    ]).then(function(data){
        $scope.myId = "<?=App::$app->user->getId()?>";
        $scope.filtru="";
        $scope.currentId;
        $scope.md = {};
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
        };
    });
}]);
</script>