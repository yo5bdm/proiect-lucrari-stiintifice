<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-9">
        <div class="row">    
            <div class="col-xs-12" >
                <div class="row">
                    <div class="col-xs-8">
                        <h3>Lucrările autorului <?=App::$app->user->getName()?></h3>
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
                <p>{{lucrariFiltrate().length}} rezultate</p>
                <table class="table-bordered table table-condensed" ng-show="lucrari.length>0">
                    <tr>
                        <th ng-show="vizibil.titlu">Titlu</th>
                        <th ng-show="vizibil.autori">Autori</th>
                        <th ng-show="vizibil.anPublicare">An publicare</th>
                        <th ng-show="vizibil.indexare">Indexare</th>
                    </tr>
                    <tr ng-repeat="x in lucrariFiltrate() | orderBy:propertyName:reverse">
                        <td  ng-show="vizibil.titlu" ng-click="modal(x.id)" data-toggle="modal" data-target="#myModal"><a href="<?=Helpers::generateUrl(['c'=>'lucrari','a'=>'view'])?>/{{x.id}}">{{x.titlu}}</a></td>
                        <td ng-show="vizibil.autori">{{autori(x.id)}}</td>
                        <td ng-show="vizibil.anPublicare">{{x.anulPublicarii}}</td>
                        <td ng-show="vizibil.indexare">{{getIndexareText(x.indexare)}}</td>
                    </tr>
                    
                </table>
        </div>
    </div>
</div>
    <div class="col-md-3">
        <h4>Filtrare</h4>
        <p>După text:<input type="text" ng-model="filtru" class="form-control" placeholder="Text"/></p>
        <p>Ultimii x ani:<input type="number" ng-model="filtruAni" class="form-control" placeholder="Ultimii x ani"/></p>
        <p><small>{{anMinim}}</small> <a class="pull-right" ng-click="resetFiltrare()">Reset</a></p>
        <hr/>
        <p>Formatul dorit pentru nume autor:
            <select class="form-control" ng-model="formatNume">
                <option ng-repeat="x in optiuniFormatNume" value="{{$index}}">{{x}}</option>
            </select>
        </p>
        <hr/>
        <h4>Coloane vizibile</h4>
        <p><input type="checkbox" ng-model="vizibil.titlu"/> Titlu</p>
        <p><input type="checkbox" ng-model="vizibil.autori"/> Autori</p>
        <p><input type="checkbox" ng-model="vizibil.anPublicare"/> An publicare</p>
        <p><input type="checkbox" ng-model="vizibil.indexare"/> Indexare</p>
        <hr/>
        <h4>Descărcări</h4>
        <button class="form-control btn btn-success" ng-click="descarcaCSV()">CSV</button>
    </div>
</div> 

<script type="text/javascript">
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.vizibil ={
        titlu:true,
        autori:true,
        anPublicare:true,
        indexare:true
    };
    $scope.propertyName = 'anulPublicarii';
    $scope.formatNume = '1';
    $scope.optiuniFormatNume = formatNume;
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
                if(filtru) {
                    return ((lucrare.titlu.toLowerCase().indexOf(filtru) != -1 ||
                        lucrare.abstract.indexOf(filtru) != -1 ||
                        lucrare.volum.indexOf(filtru) != -1) &&
                        Number(lucrare.anulPublicarii) >= Number(anMinim)
                    );
                } else {
                    return (Number(lucrare.anulPublicarii) >= Number(anMinim));
                }
            });
        };
        
        $scope.descarcaCSV = function() {
            //https://stackoverflow.com/questions/14964035/how-to-export-javascript-array-info-to-csv-on-client-side
            var v = $scope.vizibil;
            var csv = "data:text/csv;charset=utf-8,"; //csv data
            $scope.lucrariFiltrate().forEach(function(lucrare){ //lucrari to csv row
                let row = "";
                
                if(v.titlu) row += '"'+lucrare.titlu+'"';
                if(v.autori) row += ',"'+$scope.autori(lucrare.id)+'"';
                if(v.anPublicare) row += ',"'+lucrare.anulPublicarii+'"';
                if(v.indexare) row += ',"'+$scope.getIndexareText(lucrare.indexare)+'"';
                
                csv += row + "\r\n";
            });
            var encURI = encodeURI(csv);
            var link = document.createElement("a"); //generate button
            link.setAttribute("href", encURI);
            link.setAttribute("download", "tabel.csv");
            document.body.appendChild(link); // Required for FF
            link.click(); // This will download the data file named "tabel.csv".
        };
        
        
        $scope.$apply(function(){
            $scope.getAutorName($scope.myId);
        });
        
    });
}]);
</script>