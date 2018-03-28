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
                <ul class="list-group" ng-show="lucrari.length>0" >
                    <li class="list-group-item" ng-repeat="x in lucrari | filter: filtru">
                        <div class="row">
                            <div class="col-xs-8" ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal" >
                                <h3>"{{x.titlu}}" <small>{{autori(x.id)}}</small></h3>
                                <p>Anul publicarii {{x.anulPublicarii}}; {{getText(x.volum,'Vol:')}}, {{getText(x.pagini,'Pag:')}}</p>
                                <p>{{getText(x.conferinta,'Conferinta:')}}, Citări: {{x.citari.length}}</p>
                                <p></p>
                            </div>
                            <div class="col-xs-4">
                                <p><a href='{{x.link}}'>Link</a>; <a href='{{x.linkLocal}}'>Link local</a></p>
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
                        <hr>
                        <p>{{md.abstract}}</p>
                        <hr>
                        <p>{{getText(md.volum,'Volumul')}} {{getText(md.pagini,'Pag')}}</p>
                        <p>Publicat in {{md.anulPublicarii}}</p>
                        <p>{{getText(md.conferinta,'Conferinta')}}</p>
                        <p>Citări:</p>
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
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.json={};
    $scope.json.autori=[];
    $scope.lucrari=[];
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
            if(text == null || text.length == 0) return "";
            else return add+" "+text;
        };
        $scope.modal = function(id) {
            $scope.currentId = id;
            $scope.md = $scope.getLucrare(id);
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