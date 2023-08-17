(function () {
    'use strict';

    BX.namespace('BX.Sale.OrderAjaxComponentExt');

    BX.Sale.OrderAjaxComponentExt = BX.Sale.OrderAjaxComponent;
    BX.Sale.OrderAjaxComponentExt.parentEditActiveRegionBlock = BX.Sale.OrderAjaxComponent.editActiveRegionBlock;
    BX.Sale.OrderAjaxComponentExt.editActiveRegionBlock = function (activeNodeMode) {

        if (this.initialized.region) {
            // return;
            //console.log(this.initialized);
        }
        BX.Sale.OrderAjaxComponent.parentEditActiveRegionBlock(activeNodeMode);
    };
})();