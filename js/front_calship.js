/**
 * Pedro Caicedo (pcaicedo@cuado.co)
 * 
 * See: https://api.jquery.com/jquery.noconflict/
 */

var typeMoney = calship.money_unit;
var money_cost_usd = calship.money_cost_usd;
var aereo_min_lb = calship.aereo_min_lb;
var aereo_cost_lb = calship.aereo_cost_lb;
var maritimo_min_pie = calship.maritimo_min_pie;
var maritimo_cost_pie = calship.maritimo_cost_pie;

var $ = jQuery.noConflict();

//console.log(JSON.stringify(calship));

$(function() {
  $( "#alto" ).spinner({
	  min: 0,
      max: 2500000,
      step: 0.01,
      numberFormat: "n"
  });
  
  $( "#ancho" ).spinner({
	  min: 0,
      max: 2500000,
      step: 0.01,
      numberFormat: "n"
    });
  
  $( "#largo" ).spinner({
	  min: 0,
      max: 2500000,
      step: 0.01,
      numberFormat: "n"
    });
  
  $( "#peso" ).spinner({
	  min: 0,
      max: 2500000,
      step: 0.01,
      numberFormat: "n"
    });
  
  $("#type_shipping" ).selectmenu();
  
  $( "#dialog-price" ).dialog({
  	  autoOpen: false,
      modal: true,
      buttons: {
        Aceptar: function() {
          $( this ).dialog( "close" );
        }
      }
    });
  
 $( "#calcular" ).click(function() {
	  var value = 0;
	  
	  if($("#type_shipping" ).val()==1){
		  var lib = $('#alto').val()*$('#ancho').val()*$('#largo').val()/166;
		  lib = Math.max(lib, $('#peso').val(), aereo_min_lb);
		  value = lib * aereo_cost_lb * money_cost_usd;
	  }
	  
	  if( $("#type_shipping" ).val() == 2 ){
		  vol = $('#alto').val()*$('#ancho').val()*$('#largo').val()/1728;
		  vol = Math.max(vol, maritimo_min_pie);
		  value = vol * maritimo_cost_pie * money_cost_usd;
	  }
	  
	  $('#priceship').text(value.toFixed(2)+' '+typeMoney);
      $( "#dialog-price" ).dialog( "open" );
    });
});