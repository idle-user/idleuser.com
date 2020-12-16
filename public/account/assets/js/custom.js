$(document).ready(function () {
    var path = window.location.pathname + window.location.search;
    var page = path.split("/").pop();
    $('ul.nav a[href="'+ page +'"]').addClass('active');
});
