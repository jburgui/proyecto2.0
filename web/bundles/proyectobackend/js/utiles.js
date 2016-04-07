/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function longitud_campouno_en_campodos(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    text.value = text1.value.length;
    return true;
}

function marcar_si_primera_es_letra(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    if(text1.value==''){
        text.checked = false;
    }else{
        var aux= text1.value.replace(/ /g,'');
        if(aux.substr(0,1)=="_")
            text.checked = false;
        else
            text.checked = true;
    }
    return true;
}
function marcar_si_ultima_es_letra(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    if(text1.value==''){
        text.checked = false;
    }else{
        var aux= text1.value.replace(/ /g,'');
        if(aux.substr(-1,1)=="_")
            text.checked = false;
        else
            text.checked = true;
    }
    return true;
}

function marcar_si_dos_espacios_juntos(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    
    var aux= text1.value.replace(/ /g,'');
    if(aux.indexOf('__')==-1)
        text.checked = false;
    else
        text.checked = true;
    return true;
}

function marcar_si_tres_letras_juntas(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    
    var aux= text1.value.replace(/ /g,'');
    text.checked = /[a-zñ]{3}/.test(aux);

    return true;
}

function sacar_numero_letras_dadas(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    var aux= text1.value.replace(/ /g,'');
    var coincidencias = aux.match(/[a-zñ]/g); 
    text.value = coincidencias ? coincidencias.length : 0;
    return true;
}

function sacar_ratio_letras_dadas_eliminadas(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    var aux= text1.value.replace(/ /g,'');
    var coincidencias = aux.match(/[a-zñ]/g); 
    if((aux.length-(coincidencias ? coincidencias.length : 0)) == 0){
        text.value = 0;
    }else{
        text.value =(coincidencias ? coincidencias.length : 0)/(aux.length-(coincidencias ? coincidencias.length : 0));
    } 
    return true;
}

function sacar_ratio_vocales_consonantes(idin,idout){
    var text = document.getElementById(idout);
    var text1 = document.getElementById(idin);
    var aux= text1.value.replace(/ /g,'');
    var coincidenciasletras = aux.match(/[a-zñ]/g); 
    var coincidenciasvocales = aux.match(/[aeiouáéíóú]/g); 
    if(((coincidenciasletras ? coincidenciasletras.length : 0) - (coincidenciasvocales ? coincidenciasvocales.length : 0)) == 0){
        text.value = 0; 
    }else{ 
        text.value = (coincidenciasvocales ? coincidenciasvocales.length : 0) / ((coincidenciasletras ? coincidenciasletras.length : 0)-(coincidenciasvocales ? coincidenciasvocales.length : 0));
    }
    return true;
}

//esta función sirve para cargar las opciones del administrador en la ventana elejida para ello
function cargarEnGestion(url){
    ocultarid('gestion');
    mostrarid('cargando');
    $(".gestion").load(url);
}

function mostrarid(id){
    document.getElementById(id).style.display="block";
}
function ocultarid(id){
    document.getElementById(id).style.display="none";
}

function desbloquearid(id){
    $('#'+id).removeAttr('disabled');
    return true;
}

function cargarYmostrar(){
    var params= {target:'.gestion'}; 
    $('#editar').ajaxForm(params); 
    $('#eliminar').ajaxForm(params); 
    $('#filtrar').ajaxForm(params); 
    $('#crear').ajaxForm(params); 
    //para que los botones del formularios carguen la ventana "cargando"
    $(document).ready(function(){
        $('.cargando').click(function() {  
            ocultarid('gestion');
            mostrarid('cargando');
        });
    });
    //mostramos el contenido y ocultamos la espera
    ocultarid('cargando');
    mostrarid('gestion');
}

function comprobarEjes(tipoPregunta){
    $("#bottomcrear").show();
    $("#bottomcrear2").show();
    if($('[id*=grafica] option[value="1"]:selected').length > 1 && tipoPregunta > 1){
        
        $("#bottomcrear").hide();
        $("#bottomcrear2").hide();
        alert('Atención: No puede haber más de una columna para la gráfica.');
    }
}

function addTagForm(collectionHolder,name) {
    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    // get the new index
    var index = collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(name, index);

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a address" link li
    var $newFormLi = $('<div></div>').append(newForm); 
    collectionHolder.append($newFormLi); 
}