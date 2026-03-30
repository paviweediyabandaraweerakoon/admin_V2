const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');
mix.copy('node_modules/popper.js/dist/popper.js','public/plugins/popper');
mix.copy('node_modules/popper.js/dist/popper.js.map', 'public/js');
mix.copyDirectory('resources/plugins/', 'public/plugins/');
mix.copyDirectory('resources/img/', 'public/img/');

mix.js('resources/js/custom/notifications.js', 'public/js');
mix.js('resources/js/custom/modal.js', 'public/js');
mix.js('resources/js/custom/dataTable.js', 'public/js');
mix.copy('resources/js/custom/FormOptions.js', 'public/js');
mix.copy('resources/js/custom/customMethods.js', 'public/js');

mix.copy('node_modules/easy-pie-chart/dist/jquery.easypiechart.js','public/plugins/easy-pie-chart');
mix.copy('node_modules/echarts/dist','public/plugins/echarts');
mix.copy('node_modules/chart.js/dist','public/plugins/chart.js');
mix.copy('node_modules/jquery-slimscroll/jquery.slimscroll.js','public/plugins/jquery-slimscroll');
mix.copy('node_modules/jquery-sparkline/jquery.sparkline.js','public/plugins/jquery-sparkline');
mix.copy('node_modules/jquery-toast-plugin/dist','public/plugins/jquery-toast-plugin');
mix.copy('node_modules/jquery-toggles','public/plugins/jquery-toggles');
mix.copy('node_modules/jquery.counterup','public/plugins/jquery.counterup');
mix.copy('node_modules/morris.js/','public/plugins/morris.js/');
mix.copy('node_modules/peity/jquery.peity.js','public/plugins/peity');
mix.copy('node_modules/raphael/raphael.js','public/plugins/raphael');
mix.copy('node_modules/waypoints/lib/jquery.waypoints.js','public/plugins/waypoints');
mix.copy('node_modules/owl.carousel/dist/assets','public/plugins/owl.carousel');
mix.copy('node_modules/jquery-form/dist/jquery.form.min.js','public/plugins/jquery-form');
mix.copy('node_modules/sweetalert2/dist','public/plugins/sweetalert2');
mix.copy('node_modules/jquery-validation/dist/jquery.validate.js','public/plugins/jquery-validation');
mix.copy('node_modules/jquery-validation/dist/additional-methods.min.js','public/plugins/jquery-validation');
mix.copy('node_modules/select2/dist/css/select2.css','public/plugins/select2/css');
mix.copy('node_modules/select2/dist/js/select2.js','public/plugins/select2/js');
mix.copy('node_modules/select2/dist/js/select2.full.js','public/plugins/select2/js');
mix.copy('node_modules/moment/min/moment.min.js','public/plugins/moment');
mix.copy('node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js', 'public/plugins/bootstrap-datepicker')
    .copy('node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css', 'public/plugins/bootstrap-datepicker');
mix.copy('node_modules/switchery-latest/dist/switchery.min.js', 'public/plugins/switchery')
    .copy('node_modules/switchery-latest/dist/switchery.min.css', 'public/plugins/switchery');
mix.copy('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js','public/plugins/tagsinput/');
mix.copy('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css','public/plugins/tagsinput/');
mix.copyDirectory('node_modules/feather-icons/dist/','public/plugins/feather-icons/');
mix.copyDirectory('resources/plugins/jquery-flot','public/plugins/jquery-flot');

// DataTable plugin
mix.copy('node_modules/datatables/media/css','public/plugins/datatables');
mix.copy('node_modules/datatables/media/js','public/plugins/datatables');
mix.less('node_modules/datatables-bootstrap3-plugin/media/css/datatables-bootstrap3.less','public/plugins/datatables/plugins/css');
mix.js('node_modules/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.js','public/plugins/datatables/plugins/js');
mix.copyDirectory('node_modules/datatables/media','public/plugins/datatables');
mix.copyDirectory('node_modules/datatables.net-plugins','public/plugins/datatables/plugins');
mix.copyDirectory('node_modules/datatables-bootstrap3-plugin/media','public/plugins/datatables/plugins/bootstrap3-plugin');
mix.copy('node_modules/jquery-datatables-checkboxes/js/dataTables.checkboxes.js', 'public/plugins/jquery-datatables-checkboxes')
    .copy('node_modules/jquery-datatables-checkboxes/css/dataTables.checkboxes.css', 'public/plugins/jquery-datatables-checkboxes');
mix.copy('node_modules/datatables.net-buttons/js/dataTables.buttons.js','public/plugins/datatables/plugins/datatables.net-buttons/dataTables.buttons.js');
mix.copy('node_modules/datatables.net-buttons/js/buttons.print.min.js','public/plugins/datatables/plugins/datatables.net-buttons/buttons.print.min.js');
