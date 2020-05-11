angular.module("myApp",[]).
factory('PermisoService', PermisoService);

PermisoService.$inject = ['$http'];
function PermisoService($http) {
    var service = {};
    service.UpdateP = UpdateP;
    service.UpdateA = UpdateA;
    service.UpdateB = UpdateB;
    service.UpdateM = UpdateM;
    service.UpdateC = UpdateC;
    return service;

    function UpdateP(valor,id)
    {
      $http.put(pathUsr+'UpdateP/'+valor).then(handleSuccess).catch(handleError('Error en UpdateP'));
    }

    function handleSuccess(res) {
        return res.data;
    }

    function handleError(error) {
        return function () {
            return { success: false, message: error };
        };
    }
}
