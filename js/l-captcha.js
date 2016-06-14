//$.get( "../staging/captcha/" ) function( data ) {
//  $( "div.l-captcha" ).html( data );
//  alert( "Load was performed." );
//});
//$("div.l-captcha").html("Next Step...");
$.ajax({
  url: "../staging/captcha/",
  cache: false,
  type: "GET",
  headers: {"Authorization": "77d240b5f5e441e3902b133ad0e7a281"}
})
  .done(function( data ) {
    $( ".result" ).html( data );
    alert( "Load was performed." );
});
