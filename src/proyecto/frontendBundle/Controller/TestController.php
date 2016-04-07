<?php

   
namespace proyecto\frontendBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use proyecto\frontendBundle\Entity\Participante;
use proyecto\frontendBundle\Entity\TiempoRespuesta;
use proyecto\frontendBundle\Entity\Respuesta;
use proyecto\frontendBundle\Entity\DatosAsociados;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controlador de la realización del Estudio activo 
 * @author Javier Burguillo Sánchez
 */
class TestController extends Controller 
{ 
    
    /**
     * Mostrar el inicio de la realización del estudio activo.
     * @return vistaInicio
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $estudio = $em->getRepository('proyectobackendBundle:Estudio')->findOneBy(array('activa' => 1));
        //comprobamos que existe algún estudio activo. Si no, volvemos a index
        if(is_null($estudio)){
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        //creamos un array para ordenar las partes y distraciones y definir el orden desde el principio.
        $partes = $estudio->getPartesEstudio();
        $distracciones = $estudio->getDistracciones();
        $arrayOrden = array();//guardamos el orden que tendrá el estudio
        $j = 0;//controla el número de orden por el que vamos
        for ($i = 0; $i < $estudio->getnumSecciones(); ){
            $arrayAux = array();//ayuda a elejir orden si hay varios con el mismo número de orden
            $j++;
            $k = 0;//controla el indice del arrayAux
            foreach($partes as $parte){
                if($parte->getNumOrden()==$j){
                    $arrayAux[$k][0]='parte';
                    $arrayAux[$k][1]=$parte->getId();
                    $i++;
                    $k++;
                }
            }
            
            foreach($distracciones as $distraccion){
                if($distraccion->getNumOrden()==$j){
                    $arrayAux[$k][0]='distraccion';
                    $arrayAux[$k][1]=$distraccion->getId();
                    $i++;
                    $k++;
                }
            }
            
           shuffle($arrayAux);
           while(count($arrayAux)){
               array_push($arrayOrden,array_shift($arrayAux));
           }
        }
        
        
        //guardamos los datos necesarios para continuar con el participante
        $datosParticipacion = array();
        $datosParticipacion['idEstudio'] = $estudio->getId();
        $datosParticipacion['ordenSecciones'] = $arrayOrden;
        $datosParticipacion['ordenPreguntas'] = null;
        $datosParticipacion['sexo'] = null;
        $datosParticipacion['indexSeccion'] = 0;
        $datosParticipacion['indexPregunta'] = 0;
        $datosParticipacion['flagBienvenida'] = 0;
        $datosParticipacion['flagGuardarTiempo'] = false;
        $datosParticipacion['numPreguntasPrueba'] = 0;
        //guardamos los datos de estudio asociados a esta participación
        $datosEstudio = array();
        //guardamos las respuestas
        $respuestas = array();
        $tiempoRespuestas = array();
        $datospreguntas = array();
        $session = $this->getRequest()->getSession();
        $session->set('datosParticipacion', $datosParticipacion);
        $session->set('datosEstudio', $datosEstudio);
        $session->set('respuestas', $respuestas);
        $session->set('tiempoRespuestas', $tiempoRespuestas);
        $session->set('datospreguntas', $datospreguntas);
        return $this->render('proyectofrontendBundle:Test:Inicio.html.twig', array(
            'titulo' => $estudio->getNombre(),
            'aux' => count($arrayOrden),
        ));
    }

    /**
     * Mostrar el desarrollo de la realización del estudio activo. Carga la siguiente pregunta
     * @param Request contiene la respuesta dada a la pregunta expuesta
     * @return vistaDesarrollo
     */
    public function desarrolloAction(Request $request)
    {
        
        //obtenemos los datos de sesión
        $session = $this->getRequest()->getSession();
        $datosParticipacion = $session->get('datosParticipacion');
        $datosEstudio = $session->get('datosEstudio');
        $respuestas = $session->get('respuestas');
        $tiempoRespuestas = $session->get('tiempoRespuestas');
        $datospreguntas = $session->get('datospreguntas');
        $em = $this->getDoctrine()->getManager();
        $estudio = $em->getRepository('proyectobackendBundle:Estudio')->find($datosParticipacion['idEstudio']);
        
        $flagPrueba = 0;//indica a la plantilla si la pregunta es de prueba
        $reponerRespuestas = array();//tendrá las respuestas anteriores para mostrarlas en el caso de que no se valide bien
        //obtenemos la sección actual
        $seccionActual = $datosParticipacion['ordenSecciones'][$datosParticipacion['indexSeccion']];
        //obtenemos la parte actual
        $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find($seccionActual[1]);
        
        $pregunta = null;
        
        $errorValidacion = '';//mensajes de error en la validación
        //validamos y guardamos datos si no es de prueba
        if($datosParticipacion['numPreguntasPrueba']==0){
            $datosForm = $request->request->all();
            //y si hay datos que guardar(solo entrará si es parte, no distracción)
            if(count($datosForm)>0 && $datosParticipacion['indexPregunta']>-1){
                $tiempoRespuestasaux = array();
                $respuestasaux = array();
                
                if($datosParticipacion['flagGuardarTiempo'] == true){
                    $arrayTiempo['idPregunta'] = $datosParticipacion['ordenPreguntas'][$datosParticipacion['indexPregunta']];
                    $arrayTiempo['tiempo'] = array_shift($datosForm);
                    
                    array_push($tiempoRespuestasaux,$arrayTiempo);
                }   
                $flagValidacion = true;
                while(!empty($datosForm)){
                    //;
                    $tipo = array_shift($datosForm);
                    $id = array_shift($datosForm);
                    $respuesta = array_shift($datosForm);
                    array_push($reponerRespuestas,$respuesta);
                    
                    //Si hay 'sexo' cogemos el resultado en la misma variable
                    //1->Hombre -> 'H'
                    //2->Mujer -> 'M'
                    if($datosParticipacion['sexo']!=null && $datosParticipacion['sexo']==$id){
                        if($respuesta==1){
                            $datosParticipacion['sexo'] = 'H';
                        }else{
                            $datosParticipacion['sexo'] = 'M';
                        }
                        
                    }
                    
                    //en cada tipo de respuesta comprobamos la validación correspondiente
                    if($tipo == '1'){//tipo Text
                        
                    }elseif($tipo == '2'){//tipo Radio
                        if(empty($respuesta)){
                            $errorValidacion = $errorValidacion."Alguna pregunta de opciónes se ha quedado sin responder.<br>";
                            $flagValidacion = false;
                        }
                    }elseif($tipo == '3'){//tipo range
                        
                        if(is_float($respuesta)) {
                            $errorValidacion = $errorValidacion."Alguna pregunta de rango no contiene un número.<br>";
                            $flagValidacion = false;
                        }
                    }elseif($tipo == '4'){//tipo número entero
                        if(empty($respuesta)){
                            $errorValidacion = $errorValidacion."Alguna pregunta numérica se ha quedado sin responder.<br>";
                            $flagValidacion = false;
                        }
                        if(is_int($respuesta)) {
                            $errorValidacion = $errorValidacion."Alguna pregunta numérica no contiene un número.<br>";
                            $flagValidacion = false;
                        }
                    }elseif($tipo == '5'){//tipo fecha
                        if(empty($respuesta)){
                            $errorValidacion = $errorValidacion."Alguna pregunta de fecha se ha quedado sin responder.<br>";
                            $flagValidacion = false;
                        }
                        $respuesta = date("d/m/Y",strtotime($respuesta));
                        if($respuesta == false){
                            $errorValidacion = $errorValidacion."Alguna fecha no tiene formato correcto 'dia/mes/año'.<br>";
                            $flagValidacion = false;
                        }
                    }
                    //si todo va bien guardamos los datos
                    if($flagValidacion == true){
                        //guardamos el resultado
                        $arrayResultado['idSubpregunta'] = $id;
                        $arrayResultado['respuesta'] = $respuesta;
                        
                        array_push($respuestasaux,$arrayResultado);
                        
                        
                    }
                }
                
                
                
                //si se cumplen las validaciones en todas las respuestas guardamos los datos en session
                if($flagValidacion == true){
                    
                    //si el tipo de pregunta es adjetivo o fragmento obtenemos el id del fragmento de esa pregunta
                    //y guardamos 
                    
                    if($parte->getIdTipoPregunta()->getId()!=1){
                        $arrayDatos = array();
                        $pregunta = $em->getRepository('proyectobackendBundle:Pregunta')->find(($datosParticipacion['ordenPreguntas'][$datosParticipacion['indexPregunta']]));
                        $arrayDatos['idPregunta'] = $pregunta->getId();
                        $auxId = $pregunta->getId();
                        $auxArray = $datosParticipacion['ordenPreguntas'];
                        //obtenemos la primera pregunta por si se repite.
                        $primera=0;
                        for($w = 0;$w<count($datosParticipacion['ordenPreguntas']); $w++){
                            if($auxArray[$w] == $auxId){
                                $primera = $w;
                                break;
                            }
                        }
                        $actual = $datosParticipacion['indexPregunta'] - $primera;
                        $arrayDatos['idFragmento'] = $datosEstudio[$actual];
                        array_push($datospreguntas,$arrayDatos);
                    }
                    
                    $reponerRespuestas = array();//vaciamos la variable para que no muestre las anteriores respuestas
                    $tiempoRespuestas = array_merge($tiempoRespuestas,$tiempoRespuestasaux);
                    $respuestas = array_merge($respuestas,$respuestasaux);
                    
                }
                
            }
            
        }
        
        //solo se continua si el formulario se a validado bien. Si no cargamos otra vez la misma pregunta
        if($errorValidacion == ''){
            //miramos si hay que cambiar de sección (si $preguntaActual es false)

            $preguntaActual=false;

            //ordenPreguntas es null si acabamos de empezar y vacio si acabamos de terminar una distracción
            if(!is_null($datosParticipacion['ordenPreguntas']) || !empty($datosParticipacion['ordenPreguntas'])){

                //pasamos a la siguiente pregunta
                $datosParticipacion['indexPregunta']++;

                //return $datosParticipacion['numPreguntasPrueba'].' / '.$datosParticipacion['indexPregunta'];
                //si se acaban las preguntas de prueba volvemos a empezar la parte
                if($seccionActual[0]=='parte' && $datosParticipacion['numPreguntasPrueba'] > 0 && $datosParticipacion['numPreguntasPrueba'] == ($datosParticipacion['indexPregunta'])){

                    $flagPrueba = 2;
                    $datosParticipacion['numPreguntasPrueba'] = 0;
                    $datosParticipacion['indexPregunta'] = 0;
                    $datosParticipacion['flagBienvenida'] = 0;
                }


                //si no nos pasamos de indice cogemos la pregunta actual
                //si nos pasamos es por que hemos terminado la parte

                if(count($datosParticipacion['ordenPreguntas'])>$datosParticipacion['indexPregunta']){
                    $preguntaActual = $datosParticipacion['ordenPreguntas'][$datosParticipacion['indexPregunta']];
                }    
            }
            //si es cambio de sección avanzamos una
            if($preguntaActual==false ){
                //pasamos al siguiente si no acabamos de empezar (ordenPreguntas!=null)
                if(!is_null($datosParticipacion['ordenPreguntas'])){
                        $datosParticipacion['indexSeccion']++;
                        
                        //si el indice es igual o mayor al número de secciones es que hemos llegado al final
                        if(count($datosParticipacion['ordenSecciones'])<=$datosParticipacion['indexSeccion']){
                            //guardamos los datos antes de continuar
                            $session1 = $this->getRequest()->getSession();
                            $session1->set('datosParticipacion', $datosParticipacion);
                            $session->set('datosEstudio', $datosEstudio);
                            $session->set('respuestas', $respuestas);
                            $session->set('tiempoRespuestas', $tiempoRespuestas);
                            $session->set('datospreguntas', $datospreguntas);
                            return $this->render('proyectofrontendBundle:Test:Fin.html.twig', array(
                                'titulo' => $estudio->getNombre(),
                            ));
                        }
                        //si no obtenemos la siguiente sección.
                        $seccionActual = $datosParticipacion['ordenSecciones'][$datosParticipacion['indexSeccion']];
                        $datosParticipacion['ordenPreguntas']=null;
                        //desordenamos los datos en cada cambio de sección
                        if(!empty($datosEstudio)){
                            shuffle($datosEstudio);
                        }

                }
            }
        }else{
            $preguntaActual = $datosParticipacion['ordenPreguntas'][$datosParticipacion['indexPregunta']];
        }
        //*********según sea la sección se inicializa de forma diferente*******************************
        //si es una distracción
        if($seccionActual[0]=='distraccion'){
            $datosParticipacion['ordenPreguntas']=array();
        }
        //si es una parte
        if($seccionActual[0]=='parte'){

            //obtenemos la parte         
            $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find($seccionActual[1]);

            if($datosParticipacion['ordenPreguntas']==null){
                //generamos el array de datos de estudio la 1º vez que se acceda a una parte 
                //donde el tipo de pregunta sea adjetivo o fragmento
                if(empty($datosEstudio) && $parte->getIdTipoPregunta()->getId()!=1){
                    $fragmentos = $em->getRepository('proyectobackendBundle:Fragmento')->findAll();
                    $numMax = count($fragmentos)-1;
                    $arrayDatos = array();
                    //obtenemos id`s de adjetivos no repetidos
                    while(count($arrayDatos)<$estudio->getNumDatosEstudio()){
                        $num = 0;
                        do{
                            $num =rand(0, $numMax);
                            $num = $fragmentos[$num]->getId();
                        }while(in_array($num, $arrayDatos));
                        
                        //solo cogemos adjetivos acordes con el sexo
                        $fragmento = $em->getRepository('proyectobackendBundle:Fragmento')->find($num);
                        $adjetivo = $em->getRepository('proyectobackendBundle:Adjetivo')->find($fragmento->getIdAdjetivo());
                        
                        if($datosParticipacion['sexo'] == 'H' && (substr($adjetivo->getNombre(), -1)== 'o' || substr($adjetivo->getNombre(), -1)!= 'a' )){
                            array_push($arrayDatos,$num);
                        }elseif($datosParticipacion['sexo'] == 'M' && (substr($adjetivo->getNombre(), -1)== 'a' || substr($adjetivo->getNombre(), -1)!= 'o' )){
                            array_push($arrayDatos,$num);
                        }elseif($datosParticipacion['sexo'] == null ){
                            array_push($arrayDatos,$num);
                        }
                         
                    }
                    //desordenamos la lista de datos de estudio
                    shuffle($arrayDatos);
                    //la guardamos
                    $datosEstudio = $arrayDatos;
                    
                }
                
                //generamos el orden de las preguntas
                $preguntas = $parte->getPreguntas();
                $arrayOrden = array();//guardamos el orden que tendrá la parte
                $arrayRepetir = array();//guardamos en número de repeticiones que tendrá la pregunta
                $j = 0;//controla el número de orden por el que vamos
                for ($i = 0; $i < $parte->getNumPreguntas(); ){
                    $arrayAux = array();//ayuda a elejir orden si hay varios con el mismo número de orden
                    $j++;
                    $k = 0;//controla el indice del arrayAux
                    foreach($preguntas as $pregunta){
                        if($pregunta->getNumPregunta()==$j){
                            $arrayAux[$k] = $pregunta->getId();
                            $arrayRepetir[$k] = $pregunta->getRepetirPregunta();
                            $i++;
                            $k++;
                        }
                    }

                    shuffle($arrayAux);
                    while(count($arrayAux)){
                        $idPregunta = array_shift($arrayAux);
                        $numRepetir = array_shift($arrayRepetir);
                        //como minimo se asigna una vez
                        array_push($arrayOrden,$idPregunta);
                        //repetimos cada pregunta las veces que se haya indicado

                        for($q = 1; $q<$numRepetir; $q++){
                            array_push($arrayOrden,$idPregunta);
                       }

                   }

                }
                //guardamos el resultado en la variables de datos e inicializamos las variables necesarias
                $datosParticipacion['ordenPreguntas'] = $arrayOrden;
                $datosParticipacion['indexPregunta'] = 0;
                $preguntaActual = $datosParticipacion['ordenPreguntas'][$datosParticipacion['indexPregunta']];
                $datosParticipacion['flagBienvenida'] = 0;
                $datosParticipacion['flagGuardarTiempo'] = false;

                //inicializamos las preguntas de prueba.
                $datosParticipacion['numPreguntasPrueba'] = $parte->getNumPreguntasDePrueba();
                if($datosParticipacion['numPreguntasPrueba']>0){
                        $flagPrueba = 1;
                }
            }
        }



        //***********según sea el tipo se trata de forma diferente**************************************
        //
        //si es una parte
        if($seccionActual[0]=='parte'){

            $arraySubpreguntas = array();
            $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find($seccionActual[1]);
            $bienvenida = null;
            $textoPregunta = null;
            //lo 1º comprobamos si hay pantalla de bienvenida si flagBienvenida es cero
            if($datosParticipacion['flagBienvenida']==0){
                $bienvenidas = $parte->getPantallasBienvenida();

                if(count($bienvenidas)==1){
                    $bienvenida =$bienvenidas[0];
                }
                $datosParticipacion['flagBienvenida']=1;
            }            

            //si hay pantalla
            if(!is_null($bienvenida)){

                $mensaje = $bienvenida->getMensaje();
                //si hay pantalla restamos uno para que la proxima sea la pregunta cero
                $datosParticipacion['indexPregunta']--;
            //si no hay pantalla    
            }else{
                //asignamos null al mensaje para que en el template salga la parte de la pregunta
                $mensaje = null;
                //obtenemos la pregunta actual
                $pregunta = $em->getRepository('proyectobackendBundle:Pregunta')->find($preguntaActual);

                //obtenemos las subpreguntas
                $subpreguntas = $pregunta->getSubpreguntas();

                //obtenemos el orden del las subpreguntas

                $j = 0;//controla el número de orden por el que vamos
                for ($i = 0; $i < $pregunta->getNumSubpreguntas(); ){
                    $arrayAux = array();//ayuda a elejir orden si hay varios con el mismo número de orden
                    $j++;
                    $k = 0;//controla el indice del arrayAux
                    foreach($subpreguntas as $subpregunta){
                        if($subpregunta->getNumSubpregunta()==$j){
                            $arrayAux[$k]['id'] = $subpregunta->getId();
                            $arrayAux[$k]['subpregunta'] = $subpregunta->getSubpregunta();

                            //si hay una subpregunta sobre 'sexo' se guarda el id de la subpregunta 
                            //para que cuando se responda
                            //a esa pregunta se guarde la respuesta para la hora de elejir adjetivos
                            if(strtolower($arrayAux[$k]['subpregunta']) == 'sexo'){
                                $datosParticipacion['sexo'] = $arrayAux[$k]['id'];
                            }

                            $arrayAux[$k]['tipoRespuesta'] = $subpregunta->getIdTipoRespuesta()->getId();
                            //cargamos datos dependiendo del tipo de respuesta
                            if($arrayAux[$k]['tipoRespuesta'] == 3){
                                $rangos = $subpregunta->getRangosRespuesta();
                                $rango = $rangos[0];
                                $arrayAux[$k]['rangoMax'] = $rango->getRangoMax();
                                $arrayAux[$k]['rangoMin'] = $rango->getRangoMin();
                            }else if($arrayAux[$k]['tipoRespuesta'] == 2){
                                $opciones = $subpregunta->getOpcionesRespuesta();
                                $arrayOpciones = array();
                                foreach($opciones as $opcion){
                                    $valor['valor'] = $opcion->getValor();
                                    array_push($arrayOpciones,$valor);                                    
                                }
                                $arrayAux[$k]['opciones'] = $arrayOpciones;
                            }

                            $i++;
                            $k++;
                        }
                    }

                   shuffle($arrayAux);
                   while(count($arrayAux)){
                       array_push($arraySubpreguntas,array_shift($arrayAux));
                   }
                }

                $datosParticipacion['ordenSubreguntas'] = $arraySubpreguntas;

                //según el tipo de pregunta tratamos la pregunta de una manera diferente
                $textoPregunta = $pregunta->getPregunta();
                //si el tipo de pregunta es adjetivo
                if($parte->getIdTipoPregunta()->getId()==2){
                    if($datosParticipacion['numPreguntasPrueba'] > 0){
                        $adjetivos = $em->getRepository('proyectobackendBundle:Adjetivo')->findAll();
                        $numMax = count($adjetivos);
                        $random = rand(0,$numMax);
                        $adjetivo = $em->getRepository('proyectobackendBundle:Adjetivo')->find($random);
                    }else{
                        $auxId = $pregunta->getId();
                        $auxArray = $datosParticipacion['ordenPreguntas'];
                        //obtenemos la primera pregunta por si se repite.
                        $primera=0;
                        for($w = 0;$w<count($datosParticipacion['ordenPreguntas']); $w++){
                            if($auxArray[$w] == $auxId){
                                $primera = $w;
                                break;
                            }
                        }
                        $actual = $datosParticipacion['indexPregunta']-$primera;
                        $fragmento = $em->getRepository('proyectobackendBundle:Fragmento')->find($datosEstudio[$actual]);
                        $adjetivo = $em->getRepository('proyectobackendBundle:Adjetivo')->find($fragmento->getIdAdjetivo());
                    }
                    $textoPregunta =$textoPregunta."<b>".$adjetivo."</b>";
                }
                //si el tipo de pregunta es fragmento
                if($parte->getIdTipoPregunta()->getId()==3){
                    if($datosParticipacion['numPreguntasPrueba'] > 0){
                        $fragmentos = $em->getRepository('proyectobackendBundle:Fragmento')->findAll();
                        $numMax = count($fragmentos);
                        $random = rand(0,$numMax);
                        $fragmento = $em->getRepository('proyectobackendBundle:Fragmento')->find($random);
                    }else{
                        $auxId = $pregunta->getId();
                        $auxArray = $datosParticipacion['ordenPreguntas'];
                        //obtenemos la primera pregunta por si se repite.
                        $primera=0;
                        for($w = 0;$w<count($datosParticipacion['ordenPreguntas']); $w++){
                            if($auxArray[$w] == $auxId){
                                $primera = $w;
                                break;
                            }
                        }
                        $actual = $datosParticipacion['indexPregunta']-$primera;
                        $fragmento = $em->getRepository('proyectobackendBundle:Fragmento')->find($datosEstudio[$actual]);
                    }
                    $textoPregunta =$textoPregunta."<b>".$fragmento->getNombre()."</b>";
                } 

                //si hay que guardar tiempo de respuesta 
                if($parte->getGuardarTiempoRespuesta() == true){
                    $datosParticipacion['flagGuardarTiempo'] = true;
                }
            }

        }
        //si es distracción
        if($seccionActual[0]=='distraccion'){
            //se deja vacio por si en el futuro se quiere hacer algo en las distracciones
        }

        //rellenamos los datos de la barra de Secciones
        $barSeccion['total'] = $estudio->getnumSecciones();
        $barSeccion['seccion']=0;
        for($i=0;$i<$estudio->getnumSecciones();$i++){
            if($datosParticipacion['ordenSecciones'][$i][1]==$seccionActual[1] && $datosParticipacion['ordenSecciones'][$i][0]==$seccionActual[0]){
                $barSeccion['seccion'] = $i+1;
                break;
            }
        }
        $barSeccion['porcentaje'] = (100/$barSeccion['total'])*$barSeccion['seccion'].'%';

        $barPregunta = null;
        //solo reyenamos la barra de preguntas si es parte y no hay mensaje en esta iteración
        if($seccionActual[0]=='parte' && $mensaje==null){
            //si son preguntas de prueba cambiamos el total
            if($datosParticipacion['numPreguntasPrueba'] > 0){
                $barPregunta['total'] = $datosParticipacion['numPreguntasPrueba'];
            }else{
                $barPregunta['total'] = count($datosParticipacion['ordenPreguntas']);
            }
            $barPregunta['pregunta']=$datosParticipacion['indexPregunta']+1;
            $barPregunta['porcentaje'] = (100/$barPregunta['total'])*$barPregunta['pregunta'].'%';
        }



        //guardamos los datos antes de continuar
        $session1 = $this->getRequest()->getSession();
        $session1->set('datosParticipacion', $datosParticipacion);
        $session->set('datosEstudio', $datosEstudio);
        $session->set('respuestas', $respuestas);
        $session->set('tiempoRespuestas', $tiempoRespuestas);
        $session->set('datospreguntas', $datospreguntas);
        
        //según sea la sección se renderiza diferente
        //si es distracción
        if($seccionActual[0]=='distraccion'){
            return $this->render('proyectofrontendBundle:Test:Distraccion.html.twig', array(
                'titulo' => $estudio->getNombre(),
                'barSeccion' => $barSeccion,
            ));
        }

        //si es parte
        if($seccionActual[0]=='parte'){
            return $this->render('proyectofrontendBundle:Test:Desarrollo.html.twig', array(
                'titulo' => $estudio->getNombre(),
                'aux' => '',
                'barSeccion' => $barSeccion,
                'nombreParte' => $parte->getTitulo(),
                'mensaje' => $mensaje,
                'pregunta' => $textoPregunta,
                'barPregunta' => $barPregunta,
                'subpreguntas' => $arraySubpreguntas,
                'flagGuardarTiempo' => $datosParticipacion['flagGuardarTiempo'],
                'flagPrueba' => $flagPrueba,
                'errorValidacion' => $errorValidacion,
                'reponerRespuestas' => $reponerRespuestas,
            ));
        }
    }
    
    /**
     * Mostrar la distracción en la plantilla "distracion"
     * @return vistaDistraccionuno
     */
    public function distraccionunoAction()
    {
        return $this->render('proyectofrontendBundle:Test:Distraccionuno.html.twig');
    }
    
    /**
     * Guardar datos despues de finalizar la participación en el Estudio activo
     * @return Paginaprincipal
     */
    public function finAction()
    {
        //obtenemos los datos de sesión
        $session = $this->getRequest()->getSession();

        $respuestas = $session->get('respuestas');
        $tiempoRespuestas = $session->get('tiempoRespuestas');
        $datospreguntas = $session->get('datospreguntas');
        //comprobamos que tenemos respuestas, pues los otros se puede dar el caso que no haya nada.
        if($respuestas!=null){
            $em = $this->getDoctrine()->getManager();
            //guardamos elparticipante
            $participante = new Participante();
            $em->persist($participante);
            $em->flush();
            $idParticipante = $participante->getId();
            //guardamos los tiempos de respuesta si los hay
            foreach($tiempoRespuestas as $tiempo){
                $tiempoRespuesta = new TiempoRespuesta();
                
                $tiempoRespuesta->setIdPregunta($em->getRepository('proyectofrontendBundle:Pregunta')->find($tiempo['idPregunta']));
                $tiempoRespuesta->setTiempoRespuesta($tiempo['tiempo']);
                $tiempoRespuesta->setIdParticipante($em->getRepository('proyectofrontendBundle:Participante')->find($idParticipante));
                $em->persist($tiempoRespuesta);

            }
            
            //guardamos las respuestas
            foreach($respuestas as $respuesta){
                $respuestaaux = new Respuesta();
                
                $respuestaaux->setIdSubpregunta($em->getRepository('proyectofrontendBundle:Subpregunta')->find($respuesta['idSubpregunta']));
                $respuestaaux->setRespuesta($respuesta['respuesta']);
                $respuestaaux->setIdParticipante($em->getRepository('proyectofrontendBundle:Participante')->find($idParticipante));
                $em->persist($respuestaaux);

            }
            //guardamos los datos asociados a cada pregunta
            foreach($datospreguntas as $datospregunta){
                $dato = new DatosAsociados();
                
                $dato->setIdPregunta($em->getRepository('proyectofrontendBundle:Pregunta')->find($datospregunta['idPregunta']));
                $dato->setIdFragmento($em->getRepository('proyectofrontendBundle:Fragmento')->find($datospregunta['idFragmento']));
                $dato->setIdParticipante($em->getRepository('proyectofrontendBundle:Participante')->find($idParticipante));
                $em->persist($dato);

            }    
            
            
            $em->flush();
        }
        
        $session->clear();
        return $this->redirect($this->generateUrl('homepage'));
    }
}

?>
