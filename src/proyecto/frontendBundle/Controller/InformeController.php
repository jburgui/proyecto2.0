<?php

namespace proyecto\frontendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Controlador Informe 
 * @author Javier Burguillo Sánchez
 */
class InformeController extends Controller
{
    /**
     * Listar todas las entidades Informe ordenadas en páginas 
     * @param int El número de página a mostrar, por defecto "1"
     * @return vistaIndex 
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $sort = $request->query->get('sort',false);
        $direction = $request->query->get('direction',false);
        $filtro = $request->request->get('filtro',null);
        if($filtro==null)
           $filtro = $request->query->get('filtro',false); 
        $paginator  = $this->get('knp_paginator');
        

        if($sort==false){
            if($filtro==false){//si no hay filtro ni ordenación
                $entities = $em->getRepository('proyectofrontendBundle:Informe')->findAll();
                $pagination = $paginator->paginate(
                    $entities,
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else {//si hay filtro sin ordenación
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectofrontendBundle:Informe', 'a')
                ->join('a.idParteEstudio', 'r')   
                ->where("a.id LIKE '%$filtro%' OR
                        a.tituloGrafico LIKE '%$filtro%' OR
                        r.nombre LIKE '%$filtro%'");
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*page number*/,
                10/*limit per page*/
                );
            }
            
        }else{
            if($filtro==false){//si hay ordenación sin filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                    ->from('proyectofrontendBundle:Informe', 'a')
                    ->join('a.idParteEstudio', 'r') 
                    ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                    $qb->getQuery(),
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else{//si hay ordenación y filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectofrontendBundle:Informe', 'a')
                ->join('a.idParteEstudio', 'r')   
                ->where("a.id LIKE '%$filtro%' OR
                        a.tituloGrafico LIKE '%$filtro%' OR
                        r.nombre LIKE '%$filtro%'")
                ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*page number*/,
                10/*limit per page*/
                );
            }
        }
        
        
        return $this->render('proyectofrontendBundle:Informe:index.html.twig', array(
            'pagination' => $pagination,
            'filtro' => $filtro,
            'sort' => $sort,
            'direction' => $direction,
        ));
    }

    /**
     * Visualizar una entidad Informe.
     * @param int Identificador del registro a visualizar
     * @return vistaShow 
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectofrontendBundle:Informe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Informe entity.');
        }
        
        //construimos la tabla
        $tabla = array();//serán las filas de datos de la tabla
        $cabecera = array();//serán los nombres de las columnas
        $ejeX = array();
        $ejeY = array();
        $ejeZ = array();
        $ejeW = array();
        $grafica = 0;
        $tratamiento = null;
        $columnas = $entity->getColumnas();
        $tipoPregunta = $entity->getIdParteEstudio()->getIdTipoPregunta()->getId();
        if($tipoPregunta == 1){
            $datos = array();
            foreach($columnas as $columna){
                array_push($cabecera, $columna->getNombreColumna().' ('.$columna->getTratamiento().')');
                $respuestas = $em->getRepository('proyectofrontendBundle:Respuesta')->findByIdSubpregunta($columna->getIdSubpregunta());
                $resultado = $this->calculo($respuestas, $columna->getTratamiento());
                array_push($datos, $resultado);
                if($columna->getGrafica() == 1){
                    array_push($ejeX, $columna->getNombreColumna().' ('.$columna->getTratamiento().')');
                    array_push($ejeY, $resultado);
                    $grafica = 1;
                }
                
            }
            array_push($tabla, $datos);
            
        }elseif($tipoPregunta == 2){
            //rellenamos la cabecera
            array_push($cabecera, '#','Tipo','Frec. teórica','Núm. letras','Num. sílabas','Categoría','Frec. uso como Adjetivo','Núm. significados');
            foreach($columnas as $columna){
                array_push($cabecera, $columna->getNombreColumna().' ('.$columna->getTratamiento().')');
            }
            //rellenamos la tabla
            $adjetivos = $em->getRepository('proyectofrontendBundle:Adjetivo')->findBy(array(), array('nombre'=>'asc'));
            foreach($adjetivos as $adjetivo){
                $datos = array();//representa cada fila de la tabla
                //guardamos los datos del adjetivo
                array_push($datos,$adjetivo->getNombre(),$adjetivo->getTipo(),$adjetivo->getFrecTeorica(),$adjetivo->getNumLetras(),$adjetivo->getNumSilabas(),$adjetivo->getCategoria(),$adjetivo->getFrecUsoComoAdj(),$adjetivo->getNumSignificados());
                $fragmento = $em->getRepository('proyectofrontendBundle:Fragmento')->findOneBy(array('idAdjetivo' => $adjetivo->getId()));
                if($fragmento != null){    
                    $idFragmento = $fragmento->getId();
                    foreach($columnas as $columna){
                        $respuestas = array();
                        $idPregunta = $columna->getidSubpregunta()->getIdPregunta()->getId();
                        //obtenemos los Participantes que han contestado a esa pregunta con ese adjetivo
                        $query = $em->createQuery(
                                "SELECT DISTINCT c.id
                                FROM proyectofrontendBundle:DatosAsociados p
                                JOIN p.idParticipante c
                                WHERE p.idFragmento = $idFragmento AND p.idPregunta = $idPregunta");
                        $participantes = $query->getResult();
                        foreach($participantes as $participante){
                                                      
                            //obtenemos los datos asociados a ese participante en esa pregunta
                            $arrayDatosAsociados = $em->getRepository('proyectofrontendBundle:DatosAsociados')->findBy(array('idPregunta' => $idPregunta, 'idParticipante' => $participante));

                            //obtenemos las respuestas 
                            $arrayRespuestas = $em->getRepository('proyectofrontendBundle:Respuesta')->findBy(array('idSubpregunta' => $columna->getIdSubpregunta()->getId(), 'idParticipante' => $participante));

                            foreach($arrayDatosAsociados as $clave => $dato){
                                if($dato->getIdFragmento()->getId() == $idFragmento){
                                    array_push($respuestas,$arrayRespuestas[$clave]);
                                }
                            }
                            //
                            //return count($arrayDatosAsociados).'//'.count($arrayRespuestas);
                        }
                        $resultado = $this->calculo($respuestas, $columna->getTratamiento());
                        array_push($datos, $resultado);
                        if($columna->getGrafica() == 1){
                            $tratamiento = $columna->getTratamiento();
                            array_push($ejeX, $adjetivo->getNombre());
                            array_push($ejeY, $resultado);
                            $grafica = 1;
                        }
                    }
                }
                array_push($tabla, $datos);
            }
        }elseif($tipoPregunta == 3){
            //rellenamos la cabecera
            array_push($cabecera, '#','Adjetivo','Primera letra','Última letra','Dos espacios juntos','Tres letras juntas','Ratio dadas / eliminadas','Letras dadas','ratio vocales / consonantes');
            foreach($columnas as $columna){
                if($columna->getTratamiento() == 'texto-fragmento'){
                    array_push($cabecera, $columna->getNombreColumna().'-aciertos', $columna->getNombreColumna().'-fallos-omisión', $columna->getNombreColumna().'-fallos-comisión');
                }else{
                    array_push($cabecera, $columna->getNombreColumna().' ('.$columna->getTratamiento().')');
                }
            }
            
            //rellenamos la tabla
            $fragmentos = $em->getRepository('proyectofrontendBundle:Fragmento')->findBy(array(), array('nombre'=>'asc'));
            foreach($fragmentos as $fragmento){
                $datos = array();//representa cada fila de la tabla
                //guardamos los datos del fragmento
                array_push($datos,$fragmento->getNombre(),$fragmento->getIdAdjetivo()->getNombre());
                if($fragmento->getPrimeraLetra())
                    array_push($datos,'Si');
                else 
                    array_push($datos,'No');
                if($fragmento->getUltimaLetra())
                    array_push($datos,'Si');
                else 
                    array_push($datos,'No');
                if($fragmento->getDosEspaciosJuntos())
                    array_push($datos,'Si');
                else 
                    array_push($datos,'No');
                if($fragmento->getTresLetrasJuntas())
                    array_push($datos,'Si');
                else 
                    array_push($datos,'No');
                
                array_push($datos,$fragmento->getRatioDadasEliminadas(),$fragmento->getLetrasDadas(),$fragmento->getRatioVocalesConsonantes());
                
                //guardamos los datos pedidos en las columnas
                $idFragmento = $fragmento->getId();
                foreach($columnas as $columna){
                    $respuestas = array();
                    $idPregunta = $columna->getidSubpregunta()->getIdPregunta()->getId();
                    //obtenemos los Participantes que han contestado a esa pregunta con ese adjetivo
                    $query = $em->createQuery(
                            "SELECT DISTINCT c.id
                            FROM proyectofrontendBundle:DatosAsociados p
                            JOIN p.idParticipante c
                            WHERE p.idFragmento = $idFragmento AND p.idPregunta = $idPregunta");
                    $participantes = $query->getResult();
                    
                    foreach($participantes as $participante){

                        //obtenemos los datos asociados a ese participante en esa pregunta
                        $arrayDatosAsociados = $em->getRepository('proyectofrontendBundle:DatosAsociados')->findBy(array('idPregunta' => $idPregunta, 'idParticipante' => $participante));

                        //obtenemos las respuestas 
                        $arrayRespuestas = $em->getRepository('proyectofrontendBundle:Respuesta')->findBy(array('idSubpregunta' => $columna->getIdSubpregunta()->getId(), 'idParticipante' => $participante));

                        foreach($arrayDatosAsociados as $clave => $dato){
                            if($dato->getIdFragmento()->getId() == $idFragmento){
                                array_push($respuestas,$arrayRespuestas[$clave]);
                            }
                        }
                    }
                    
                    //dependiendo del tratamiento se opera de diferente manera
                    if($columna->getTratamiento() == 'texto-fragmento'){
                        //metemos el adjetivo a examinar al final del array
                        array_push($respuestas, $fragmento->getIdAdjetivo()->getNombre());
                        $resultado = $this->calculo($respuestas, $columna->getTratamiento());
                        
                        array_push($datos, $resultado[0],$resultado[1],$resultado[2]);
                        
                        if($columna->getGrafica() == 1){
                            $tratamiento = $columna->getTratamiento();
                            array_push($ejeX, $fragmento->getNombre());
                            array_push($ejeY, $resultado[0]);
                            array_push($ejeZ, $resultado[1]);
                            array_push($ejeW, $resultado[2]);
                            $grafica = 1;
                        }
                    }else{
                        array_push($datos, $this->calculo($respuestas, $columna->getTratamiento()));
                        
                        if($columna->getGrafica() == 1){
                            $tratamiento = $columna->getTratamiento();
                            array_push($ejeX, $fragmento->getNombre());
                            array_push($ejeY, $resultado);
                            $grafica = 1;
                            $tipoPregunta = 2;
                        }
                    }
                    
                    
                    
                }
                //
                array_push($tabla, $datos);
            }
            
            
            
        }
        
        
        return $this->render('proyectofrontendBundle:Informe:show.html.twig', array(
            'entity'      => $entity,
            'tabla' => $tabla,
            'cabecera' => $cabecera,
            'ejeX' => $ejeX,
            'ejeY' => $ejeY,
            'ejeZ' => $ejeZ,
            'ejeW' => $ejeW,
            'grafica' => $grafica,
            'tipoPregunta' => $tipoPregunta,
            'tratamiento' => $tratamiento,
        ));
    }
    
    /**
     * Carlcular cálculo estadístico según tipo de tratamiento 
     * @param array Vector de datos para calcular
     * @param string tipo de tratamiento elejido
     * @return integer 
     */
    public function calculo($respuestas,$tipo)
    {
        $dato = 0;
        
        if($tipo == 'media-aritmética'){
            //calculamos la media aritmética
            
            foreach($respuestas as $respuesta){
                $dato += $respuesta->getRespuesta();
            }
            if($dato!=0)
                $dato /= count($respuestas);

        }elseif($tipo == 'media-geométrica'){
            //calculamos la media geometrica
            $dato = 1;

            foreach($respuestas as $respuesta){
                $dato *= $respuesta->getRespuesta();
            }

            if($dato!=0)
                $dato = sqrt($dato);

        }elseif($tipo == 'media-armónica'){
            //calculamos la media armónica

            foreach($respuestas as $respuesta){
                $dato += 1/$respuesta->getRespuesta();
            }

            if($dato!=0)
                $dato = count($respuestas)/$dato;

        }elseif($tipo == 'sumatorio'){

            foreach($respuestas as $respuesta){
                $dato += $respuesta->getRespuesta();
            }
            
        }elseif($tipo == 'texto-fragmento'){
            $dato = array();
            $aciertos = 0;
            $fallosOmision = 0;
            $fallosComision = 0;
            //En el último registro del array se encuentra el nombre del adjetivo original
            $adjetivo = array_pop($respuestas);
            
            foreach($respuestas as $respuesta){
                
                if($respuesta->getRespuesta() == ''){
                    $fallosOmision++;
                }elseif($respuesta->getRespuesta() == $adjetivo){
                    $aciertos++;
                }else{
                    $fallosComision++;
                }
            }
            
            array_push($dato, $aciertos, $fallosOmision, $fallosComision);            
            
        }
        
        return $dato;
    }

}
