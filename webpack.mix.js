let mix = require('laravel-mix');


 // BABEL config
mix.webpackConfig({
    resolve: {
        modules: [
            path.resolve("./resources/assets"),
            path.resolve("./node_modules")
        ]
    },
    module: {
        rules: [{
            test: /\.jsx?$/,
            use: {
                loader: "babel-loader",
                options: {
                    presets: ["env"]
                }
            }
        }]
    }
});
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js("resources/assets/js/app.js", "public/js")
  .sass("resources/assets/sass/app.scss", "public/css")
  // Custom Gentelella
  //    .styles('public/css/gentelella/custom.css', 'public/all.css')
  //    .js('public/js/gentelella/custom.css', 'public/js')

  // NProgress
  //    .styles('vendors/nprogress/nprogress.css', 'public/all.css')
  //    .js('vendors/nprogress/nprogress.js', 'public/js')

  .styles(
    [
      "public/vendors/select2/dist/css/select2.css",

      "public/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.css",
      "public/vendors/datatables.net-bs/css/dataTables.bootstrap.css",

      "public/vendors/bootstrap-daterangepicker/daterangepicker.css",

      "public/vendors/animatecss/animate.css",
      "public/vendors/switchery/dist/switchery.css",
      "public/vendors/nprogress/nprogress.css",
      "public/vendors/scrollbar/jquery.mCustomScrollbar.css",
      "public/css/gentelella/custom.css"
    ],
    "public/css/all.css"
  )

  .scripts(
    [
      "public/vendors/jquery/dist/jquery.js",
      
      "public/vendors/datatables.net/js/jquery.dataTables.js",
      "public/vendors/datatables.net-bs/js/dataTables.bootstrap.js",
      "public/vendors/datatables.net-responsive/js/dataTables.responsive.js",
      "public/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js",


      "public/vendors/bootstrap-daterangepicker/daterangepicker.js",

      "public/vendors/bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js",

      "public/js/terbilang.js",
      "public/js/numberformat.js",

      "public/vendors/select2/dist/js/select2.full.min.js",
      "public/vendors/switchery/dist/switchery.js",
      "public/vendors/nprogress/nprogress.js",
      "public/vendors/chartjs/dist/Chart.js",
      "public/vendors/scrollbar/jquery.mCustomScrollbar.js",
      "public/js/gentelella/custom.js"
    ],
    "public/js/all.js"
  );
    