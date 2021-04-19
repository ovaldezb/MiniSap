
<div class="container" ng-controller="myCtrCargMasiva" data-ng-init="init()">
    <div class="notification">
        <h1 class="title is-4 has-text-centered">Carga Masiva de Datos</h1>
    </div>
    <div class="box">
        <div class="columns">
            <div class="column">
                <fieldset>
                    <legend>Productos</legend>
                    <div class="columns">
                        <div class="column">
                            <input type="file" name="fileProduct" accept=".csv" id="fileproducto" >
                        </div>
                        <div class="column">
                            <a href="../img/PlantillaProducto.xlsx">
                                <img src="../img/excel-icon.png" style="width:30"  title="Descargar Plantilla de Productos">
                            </a>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <button class="button is-success" ng-click=uploadProducto()>Enviar</button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <fieldset>
                    <legend>Clientes</legend>
                    <div class="columns">
                        <div class="column">
                            <input type="file" name="file" id="filecliente" accept=".csv" >
                        </div>
                        <div class="column">
                            <a href="../img/PlantillaProducto.xlsx"><img src="../img/excel-icon.png" style="width:30"  title="Descargar Plantilla de Productos"></a>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <button class="button is-success" ng-click=uploadCliente()>Enviar</button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <fieldset>
                    <legend>Proveedores</legend>
                    <div class="columns">
                        <div class="column">
                            <input type="file" accept=".csv" name="file" id="fileproveedor">
                        </div>
                        <div class="column">
                            <a href="../img/PlantillaProducto.xlsx"><img src="../img/excel-icon.png" style="width:30"  title="Descargar Plantilla de Productos"></a>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <button class="button is-success" ng-click=uploadProveedor()>Enviar</button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>


