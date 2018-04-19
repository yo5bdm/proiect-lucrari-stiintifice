<?php

/* 
 * Copyright Erdei Rudolf (www.erdeirudolf.com) - All rights reserved.
 * Code available under the GPL V2 license terms and conditions
 */

//id autor $this->data['idAutor'];
?>
<div class="row" ng-app="myApp" ng-controller="myCtrl">
    <div class="col-md-8">
        <h4>{{autor().nume+" "+autor().prenume}}</h4>
        <p>Email: {{autor().email}}</p>
        <hr/>
        <h4>Lucrari publicate:</h4>
        <table class="table table-bordered table-condensed">
            <tr>
                <th>Capul de tabel</th>
            </tr>
            <tr ng-repeat="x in lucrari()">
                <td>{{x.titlu}}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <h4>Sidebar</h4>
    </div>
</div>
<script type="text/javascript">
var app = angular.module("myApp", ['chart.js']);
app.controller("myCtrl", ['$scope','$http', function($scope,$http) {
    $scope.viewAutorId = <?=$this->data['idAutor']?>;
    $scope.json = {};
    
    $scope.getLucrari = function() { 
        $http.get('<?=Helpers::generateUrl(["c"=>"json","a"=>"listalucrari"])?>').then(function(response){
            $scope.json.lucrari = response.data;
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
    
    Promise.all([
        $scope.getLucrari(),
        $scope.getAutori(),
        $scope.getUnitate()
    ]).then(function(){
        console.log($scope.json);
        $scope.autor = function(){
            return $scope.json.autori.find(function(autor){
                return autor.id == $scope.viewAutorId;
            });
        };
        $scope.lucrari = function() {
            if(!$scope.json.lucrari) return undefined;
            return $scope.json.lucrari.filter(function(lucrare){
                return lucrare.autori.find(id=>id==$scope.viewAutorId) != undefined;
            });
        };
        $scope.$apply(function(){
            $scope.lucrari();
        });
    });
}]);

</script>