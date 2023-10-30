/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

define([
    'Magento_Ui/js/grid/columns/column'
], function (
    Column
) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Opengento_WebapiLogger/ui/grid/cells/text'
        },

        getStatusColor: function (row) {
            const responseCode = parseInt(row.response_code);
            if (responseCode >= 200 && responseCode < 300) {
                return '#90EE90';
            }
            if (responseCode >= 400 && responseCode < 600) {
                return '#ff7a7a';
            }

            return '#ffd97a';
        }
    });
});
