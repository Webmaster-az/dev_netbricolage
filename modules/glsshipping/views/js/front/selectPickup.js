function abrirIframe() {
	var s = 'https://www.asmred.com/Extranet/public/asmpshop/seleccionador.aspx?css=asm&cp=' + document.getElementById("codigoPostal").value + '&q=i&p=-1';
	document.getElementById('iframe1').src = s;
	var iframe = document.getElementById("iframe1");
	iframe.style.display = "block";
}
function esconderIframe() {
	var iframe = document.getElementById("iframe1");
	iframe.style.display = "none";
}
window.addEventListener("message", function(event) {
	if (typeof event.data != 'undefined' && event.data.length != 0){
		var nombre = '';
		var data = event.data+'';
		var res = data.split("|");
		var codigo = res[0];
		var nombre =res[1];
		var direccion = res[2];
		var cp = res[3];
		var localidad =res[4];
		if(nombre != '' && typeof nombre !== "undefined") {
		//console.log('nombre:' + nombre);
			var codigoForm = document.getElementById("parcel_codigo");
			codigoForm.value = codigo;
			var direccionForm = document.getElementById("parcel_direccion");
			direccionForm.value = direccion;
			var cpForm = document.getElementById("parcel_cp");
			cpForm.value = cp;
			var nombreForm = document.getElementById("parcel_nombre");
			nombreForm.value = nombre;
			var localidadForm = document.getElementById("parcel_localidad");
			localidadForm.value = localidad;
			
			
			var msg = '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title">'+"Punto de recogida seleccionado"+'</h3></div>'
					+ '<div class="panel-body">'+nombre+'</div></div>';

			document.getElementById("parcelinfo").innerHTML  = msg;
		
			ajaxurl = document.getElementById("glscurl").value;
			
			$.ajax({
				type: 'POST',
				headers: { "cache-control": "no-cache" },
				url: ajaxurl + '?rand=' + new Date().getTime(),
				data: 'method=saveParcel&codigo='+codigo+'&nombre='+nombre+'&direccion='+direccion+'&cp='+cp+'&localidad='+localidad,
				dataType: 'json',
				success: function () {
				}
			});
		}
		esconderIframe();
	}
});
jQuery(document).ready(function()
{
	jQuery('.carrier-extra-content').toggleClass('carrier-extra-content carrier-extra-content-gls');
	prestashop.on('updatedDeliveryForm', (params) => {
		if (typeof params.deliveryOption === 'undefined' || 0 === params.deliveryOption.length)
		{ return; }
		$(".carrier-extra-content-gls").hide();
		params.deliveryOption.next(".carrier-extra-content-gls").slideDown();
	});
	jQuery('#js-delivery').submit(function( event ) {
		if (parseInt(jQuery('#seldelop').val()) == parseInt(jQuery('#parcelshopid').val()) && jQuery('#parcel_codigo').val() == ''){
			var msg = '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title">Debe seleccionar un punto de recogida</h3></div>'
			document.getElementById("parcelinfo").innerHTML  = msg;
			event.preventDefault();
			return false;
		}
	});
})