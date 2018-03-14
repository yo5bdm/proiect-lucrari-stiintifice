<div class="row">
    <div class="col-md-3">&nbsp;</div>
    <div class="col-md-6 text-center">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="card-title text-center">Înregistrare</h3>
            </div>
            <div class="panel-body">
                <form ng-app="myApp" ng-controller="myCtrl" action="" method="POST">
                    <p><input type="text" name="name" placeholder="Numele si prenumele" class="form-control"/></p>
                    <p><input type="text" name="username" placeholder="Numele de utilizator" class="form-control"/></p>
                    <p><input type="password" ng-model="password" name="password" placeholder="Parola" class="form-control"/></p>
                    <p><input type="password" ng-model="passverify" name="passwordVerify" placeholder="Verificare Parola" class="form-control"/></p>
                    <p>{{eroare}}</p>
                    <p><input type="submit" ng-disabled="verificare()" class="form-control btn btn-success" value="Salveaza"/></p>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
var app = angular.module("myApp", []);
app.controller("myCtrl", ['$scope', function($scope) {
    $scope.password;
    $scope.passverify;
    $scope.eroare="";
    $scope.verificare = function() {
        if($scope.password != $scope.passverify || $scope.password.length < 1) {
            $scope.eroare ="Verificati ca parola sa aiba minim 1 caracter si sa corespunda cu campul verificare";
            return true;
        }
        else {
            $scope.eroare ="";
            return false;
        }
    }
}]);
</script>