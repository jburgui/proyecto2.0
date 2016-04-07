<?php

namespace proyecto\backendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use proyecto\backendBundle\Entity\Informe;
use proyecto\backendBundle\Form\InformeType;

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
                $entities = $em->getRepository('proyectobackendBundle:Informe')->findAll();
                $pagination = $paginator->paginate(
                    $entities,
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else {//si hay filtro sin ordenación
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Informe', 'a')
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
                    ->from('proyectobackendBundle:Informe', 'a')
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
                ->from('proyectobackendBundle:Informe', 'a')
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
        
        
        return $this->render('proyectobackendBundle:Informe:index.html.twig', array(
            'pagination' => $pagination,
            'filtro' => $filtro,
            'sort' => $sort,
            'direction' => $direction,
        ));
    }

    /**
     * Crear una nueva entidad Informe.
     * @param Request 
     * @return vistaShow 
     */
    public function createAction(Request $request)
    {
        $entity  = new Informe();
        $form = $this->createForm(new InformeType(), $entity);
        $form->bind($request);
        $em = $this->getDoctrine()->getManager();
        $arrayInforme = $request->request->get('informe');
        $mensajesError = '';
        $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find($arrayInforme['idParteEstudio']);
        $tipoPregunta = $parte->getIdTipoPregunta();
        if ($form->isValid()) {
            $flag=0;
            //realizamos las validacciones restantes
            
            if(array_key_exists('columnas',$arrayInforme)){
                    $columnas = $arrayInforme['columnas'];
                    //con los contadores controlamos que o hay 1 de cada eje o ninguno.
                    $cont = 0;
                    foreach($columnas as $indice =>$columna){
                        
                        //contamos el número de asignaciones al eje X y al Y
                        if($columna['grafica'] == '1'){
                            $cont++;
                        }
                        
                        
                        //comprobamos que las subpreguntas tienen un tratamiento acorde al tipo de respuesta asociado
                        $subpregunta = $em->getRepository('proyectobackendBundle:Subpregunta')->find($columna['idSubpregunta']);
                        $tipoRespuesta = $subpregunta->getIdTipoRespuesta();
                        //return $tipoRespuesta->getId().'/'.$columna['tratamiento'];
                        if($tipoRespuesta->getId() == 1 && ($columna['tratamiento'] == 'media' || $columna['tratamiento'] == 'sumatorio')){
                            $flag = 1;
                            $mensajesError .='No se permite la relación Subpregunta<=>tratamiento en: '.$indice.'<br>';
                        }elseif($tipoRespuesta->getId() > 1 && $columna['tratamiento'] == 'texto-fragmento'){
                            $flag = 1;
                            $mensajesError .='No se permite la relación Subpregunta<=>tratamiento en: '.$indice.'<br>';
                        }
                        
                    }
                    
                    //si hay más de 1 asignación con tipo de pregunta adjetivo o fragmento generamos error
                    if($cont > 1 && $tipoPregunta->getId() > 1){
                        $mensajesError .='Con el tipo de pregunta que tiene esta parte solo se puede asignar una columna a la gráfica. <br>';
                        $flag = 1;
                    }
                    
                    //si hay alguna columna para gráfica el campo titulografica no puede quedar nulo.
                    if( $cont >= 1  && $arrayInforme['tituloGrafico'] == null){
                        $mensajesError .='El título del gráfico no puede quedar vacio, ya que se han señalado columnas para la gráfica. <br>';
                        $flag = 1; 
                    }
                    
            }
            
            if($flag == 0){
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('informe_show', array('id' => $entity->getId())));
            }
        }
        //si no es válido, cogemos otra vez los datos para que se muestre todo correctamente
        
        //rellenamos el select de estudios
        $estudios = $em->getRepository('proyectobackendBundle:Estudio')->findAll();
        
        $idEstudio = $parte->getIdEstudio()->getId();
        
        $flagTipoPregunta = $tipoPregunta->getId();
        $preguntas = $parte->getPreguntas();
        $arraySubpreguntas = array();
        foreach($preguntas as $keyPregunta => $pregunta){

            $subpreguntas = $pregunta->getSubpreguntas();
            foreach($subpreguntas as $keySubpregunta => $subpregunta){

                $idTipoRespuesta = $subpregunta->getIdTipoRespuesta()->getId();
                if((1<$idTipoRespuesta && $idTipoRespuesta<5) || ($idTipoRespuesta==1 && $flagTipoPregunta == 3)){
                    $arrayAux['id']= $subpregunta->getId();
                    $arrayAux['tipoRespuesta'] = $idTipoRespuesta;
                    if($pregunta->getPregunta() == Null){
                        $arrayAux['pregunta']= 'Pregunta'.($keyPregunta+1);
                    }else{
                        $arrayAux['pregunta']= $pregunta->getPregunta();
                    }
                    if($subpregunta->getSubpregunta() == Null){
                        $arrayAux['subpregunta']= 'Subpregunta'.($keySubpregunta+1);
                    }else{
                        $arrayAux['subpregunta']= $subpregunta->getSubpregunta();
                    }

                    array_push($arraySubpreguntas,$arrayAux);
                }
            }
        }
        //
        return $this->render('proyectobackendBundle:Informe:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'estudios' => $estudios,
            'partes' => array(),
            'flagEstado' => 2,
            'flagTipoPregunta' => $flagTipoPregunta,
            'parte' => $arrayInforme['idParteEstudio'],
            'estudio' => $idEstudio,
            'selectSubpreguntas' => $arraySubpreguntas,
            'mensajesError' => $mensajesError,
        ));
    }

    /**
     * Visualizar un formulario para crear una nueva entidad Informe.
     * @return vistaNew 
     */
    public function newAction(Request $request)
    {
        
        $entity = new Informe();
        $form   = $this->createForm(new InformeType(), $entity);
        
        $datosForm = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $flagEstado = 0;//0 si acabamos de empezar. 1 si hemos seleccionado estudio. 2 si hemos seleccionado parte
        //
        //rellenamos el select de estudios
        $estudios = $em->getRepository('proyectobackendBundle:Estudio')->findAll();
        $idParte = 0;
        $idEstudio = 0;
        $partes = null;
        $arraySubpreguntas = array();
        $flagTipoPregunta = 0;//1=>texto; 2=>adjetivo; 3=>fragmento
        
        //si no es la primera vez que accedemos a la pantalla
        if(count($datosForm)>0){
            $idEstudio = $datosForm['estudio'];
            $idParte = $datosForm['parte'];
            $partes = $em->getRepository('proyectobackendBundle:ParteEstudio')->findByIdEstudio($idEstudio);
            //esto ocurre cuando se elije estudio
            if($idParte == 0){
                $flagEstado = 1;
                
                
            }else{//esto ocurre cuando se elije parte de estudio
                //obtenemos las subpreguntas que pueden ser operadas(de respuesta numérica
                //o textual solo si el tipo de pregunta es fragmento)
                $flagEstado = 2;
                $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find($idParte);
                $tipoPregunta = $parte->getIdTipoPregunta();
                $flagTipoPregunta = $tipoPregunta->getId();
                
                //
                $preguntas = $parte->getPreguntas();
                foreach($preguntas as $keyPregunta => $pregunta){
                    
                    $subpreguntas = $pregunta->getSubpreguntas();
                    foreach($subpreguntas as $keySubpregunta => $subpregunta){
                        
                        $idTipoRespuesta = $subpregunta->getIdTipoRespuesta()->getId();
                        //filtramos las subpreguntas, pues solo hay operaciones numéricas y resolución de fragmentos
                        if((1<$idTipoRespuesta && $idTipoRespuesta<5) || ($idTipoRespuesta==1 && $flagTipoPregunta == 3)){
                            $arrayAux['id'] = $subpregunta->getId();
                            $arrayAux['tipoRespuesta'] = $idTipoRespuesta;
                            if($pregunta->getPregunta() == Null){
                                $arrayAux['pregunta'] = 'Pregunta'.($keyPregunta+1);
                            }else{
                                $arrayAux['pregunta'] = $pregunta->getPregunta();
                            }
                            if($subpregunta->getSubpregunta() == Null){
                                $arrayAux['subpregunta'] = 'Subpregunta'.($keySubpregunta+1);
                            }else{
                                $arrayAux['subpregunta'] = $subpregunta->getSubpregunta();
                            }

                            array_push($arraySubpreguntas,$arrayAux);
                        }
                    }
                }
                
            }
        }
                
        
        
        return $this->render('proyectobackendBundle:Informe:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'estudios' => $estudios,
            'partes' => $partes,
            'flagEstado' => $flagEstado,
            'flagTipoPregunta' => $flagTipoPregunta,
            'parte' => $idParte,
            'estudio' => $idEstudio,
            'selectSubpreguntas' => $arraySubpreguntas,
        ));
    }

    /**
     * Visualizar una entidad Informe.
     * @param int Identificador del registro a visualizar
     * @return vistaShow 
     */
    public function showAction($id)
    {
        //denegamos el acceso a eliminar a los investigadores
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $admin=true;}
        else{
            $admin=false;
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Informe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Informe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        
        $numColumnas = count($entity->getColumnas());
        
        return $this->render('proyectobackendBundle:Informe:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),   
            'admin' => $admin,
            'numColumnas' =>$numColumnas,
        ));
    }

    /**
     * Visualizar un formulario para editar una entidad Informe existente.
     * @param int Identificador del registro a visualizar
     * @return vistaEdit 
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Informe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Informe entity.');
        }

        $editForm = $this->createForm(new InformeType(), $entity);
        
        
        $parte = $entity->getIdParteEstudio();
        $tipoPregunta = $parte->getIdTipoPregunta();
        $flagTipoPregunta = $tipoPregunta->getId();
        $arraySubpreguntas = array();
        //
        $preguntas = $parte->getPreguntas();
        foreach($preguntas as $keyPregunta => $pregunta){

            $subpreguntas = $pregunta->getSubpreguntas();
            foreach($subpreguntas as $keySubpregunta => $subpregunta){

                $idTipoRespuesta = $subpregunta->getIdTipoRespuesta()->getId();
                //filtramos las subpreguntas, pues solo hay operaciones numéricas y resolución de fragmentos
                if((1<$idTipoRespuesta && $idTipoRespuesta<5) || ($idTipoRespuesta==1 && $flagTipoPregunta == 3)){
                    $arrayAux['id'] = $subpregunta->getId();
                    $arrayAux['tipoRespuesta'] = $idTipoRespuesta;
                    if($pregunta->getPregunta() == Null){
                        $arrayAux['pregunta'] = 'Pregunta'.($keyPregunta+1);
                    }else{
                        $arrayAux['pregunta'] = $pregunta->getPregunta();
                    }
                    if($subpregunta->getSubpregunta() == Null){
                        $arrayAux['subpregunta'] = 'Subpregunta'.($keySubpregunta+1);
                    }else{
                        $arrayAux['subpregunta'] = $subpregunta->getSubpregunta();
                    }

                    array_push($arraySubpreguntas,$arrayAux);
                }
            }
        }
        //
        return $this->render('proyectobackendBundle:Informe:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'flagTipoPregunta' => $flagTipoPregunta,
            'selectSubpreguntas' => $arraySubpreguntas,
        ));
    }

    /**
     * Actualizar una entidad Informe editada.
     * @param int Identificador del registro a actualizar
     * @param Request 
     * @return llamarShowAction
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Informe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Informe entity.');
        }
        
        //guardamos las columnas originales
         $ColumnasOriginal = array();
        foreach ($entity->getColumnas() as $columna) {
            $ColumnasOriginal[] = $columna;
        }
        
        $editForm = $this->createForm(new InformeType(), $entity);
        $editForm->bind($request);

        
        $arrayInforme = $request->request->get('informe');
        $mensajesError = '';
        $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find($arrayInforme['idParteEstudio']);
        $tipoPregunta = $parte->getIdTipoPregunta();
        
        
        
        if ($editForm->isValid()) {
            $flag=0;
            //realizamos las validacciones restantes
            
            if(array_key_exists('columnas',$arrayInforme)){
                    $columnas = $arrayInforme['columnas'];
                    //con los contadores controlamos que o hay 1 de cada eje o ninguno.
                    $cont = 0;
                    foreach($columnas as $indice =>$columna){
                        
                        //contamos el número de asignaciones al eje X y al Y
                        if($columna['grafica'] == '1'){
                            $cont++;
                        }
                        
                        //comprobamos que las subpreguntas tienen un tratamiento acorde al tipo de respuesta asociado
                        $subpregunta = $em->getRepository('proyectobackendBundle:Subpregunta')->find($columna['idSubpregunta']);
                        $tipoRespuesta = $subpregunta->getIdTipoRespuesta();
                        //return $tipoRespuesta->getId().'/'.$columna['tratamiento'];
                        if($tipoRespuesta->getId() == 1 && ($columna['tratamiento'] == 'media' || $columna['tratamiento'] == 'sumatorio')){
                            $flag = 1;
                            $mensajesError .='No se permite la relación Subpregunta<=>tratamiento en: '.$indice.'<br>';
                        }elseif($tipoRespuesta->getId() > 1 && $columna['tratamiento'] == 'texto-fragmento'){
                            $flag = 1;
                            $mensajesError .='No se permite la relación Subpregunta<=>tratamiento en: '.$indice.'<br>';
                        }
                        
                    }
                    
                    //si hay más de 1 asignación con tipo de pregunta adjetivo o fragmento generamos error
                    if($cont > 1 && $tipoPregunta->getId() > 1){
                        $mensajesError .='Con el tipo de pregunta que tiene esta parte solo se puede asignar una columna a la gráfica. <br>';
                        $flag = 1;
                    }
                    
                    //si hay alguna columna para gráfica el campo titulografica no puede quedar nulo.
                    if( $cont >= 1  && $arrayInforme['tituloGrafico'] == null){
                        $mensajesError .='El título del gráfico no puede quedar vacio, ya que se han señalado columnas para la gráfica. <br>';
                        $flag = 1; 
                    }
                    
            }
            
            if($flag == 0){
                
                //filtramos las columnas originales
                foreach ($entity->getColumnas() as $columna) {
                    foreach ($ColumnasOriginal as $key => $toDel) {
                        if ($toDel->getId() === $columna->getId()) {
                            unset($ColumnasOriginal[$key]);
                        }
                    }
                }
                //eliminamos las borradas en el form
                foreach ($ColumnasOriginal as $columna) {
                    $em->remove($columna);
                }
                
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('informe_show', array('id' => $id)));
            }
        }
        
        $flagTipoPregunta = $tipoPregunta->getId();
        $preguntas = $parte->getPreguntas();
        $arraySubpreguntas = array();
        foreach($preguntas as $keyPregunta => $pregunta){

            $subpreguntas = $pregunta->getSubpreguntas();
            foreach($subpreguntas as $keySubpregunta => $subpregunta){

                $idTipoRespuesta = $subpregunta->getIdTipoRespuesta()->getId();
                if((1<$idTipoRespuesta && $idTipoRespuesta<5) || ($idTipoRespuesta==1 && $flagTipoPregunta == 3)){
                    $arrayAux['id']= $subpregunta->getId();
                    $arrayAux['tipoRespuesta'] = $idTipoRespuesta;
                    if($pregunta->getPregunta() == Null){
                        $arrayAux['pregunta']= 'Pregunta'.($keyPregunta+1);
                    }else{
                        $arrayAux['pregunta']= $pregunta->getPregunta();
                    }
                    if($subpregunta->getSubpregunta() == Null){
                        $arrayAux['subpregunta']= 'Subpregunta'.($keySubpregunta+1);
                    }else{
                        $arrayAux['subpregunta']= $subpregunta->getSubpregunta();
                    }

                    array_push($arraySubpreguntas,$arrayAux);
                }
            }
        }
        
        return $this->render('proyectobackendBundle:Informe:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'flagTipoPregunta' => $flagTipoPregunta,
            'selectSubpreguntas' => $arraySubpreguntas,
            'mensajesError' => $mensajesError,
        ));
    }

    /**
     * Eliminar una entidad Informe.
     * @param int Identificador del registro a actualizar
     * @param Request 
     * @return llamarIndexAction
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('proyectobackendBundle:Informe')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Informe entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('informe'));
    }

    /**
     * Crear un formulario para eliminnar una entidad Informe por id
     * 
     * @param mixed  El id de la entidad
     *
     * @return Symfony\Component\Form\Form El formulario
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
