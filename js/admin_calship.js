/**
 * Pedro Caicedo (pcaicedo@cuado.co)
 * 
 * See: https://api.jquery.com/jquery.noconflict/
 */

var $ = jQuery.noConflict();

//console.log(JSON.stringify(calship));

$(function() {
	 $( "#aereo_min_lb" ).spinner({
	      min: 0,
	      max: 2500000,
	      step: 0.01,
	      numberFormat: "n"
	    });
	    
    $( "#aereo_cost_lb" ).spinner({
        min: 0,
        max: 2500000,
        step: 0.01,
        numberFormat: "n"
      });
    
    $( "#maritimo_min_pie" ).spinner({
        min: 0,
        max: 2500000,
        step: 0.01,
        numberFormat: "n"
      });
    
    $( "#maritimo_cost_pie" ).spinner({
        min: 0,
        max: 2500000,
        step: 0.01,
        numberFormat: "n"
      });
    
    $( "#money_cost_usd" ).spinner({
        min: 0,
        max: 2500000,
        step: 0.01,
        numberFormat: "n"
	      });
    
    $("#money_unit" ).selectmenu();
});