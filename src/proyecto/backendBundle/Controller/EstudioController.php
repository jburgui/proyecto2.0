<?php

namespace proyecto\backendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use proyecto\backendBundle\Entity\Estudio;
use proyecto\backendBundle\Form\EstudioType;

/**
 * Controlador Estudio 
 * @author Javier Burguillo Sánchez
 */
class EstudioController extends Controller
{
    /**
     * Listar todas las entidades Estudio ordenadas en páginas 
     * @param int El número de página a mostrar, por defecto "1"
     * @return vistaIndex 
     */
    public function indexAction($page)
    {
        
        //denegamos el acceso a eliminar a los investigadores
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $admin=true;}
        else{
            $admin=false;
        }
        
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
                $entities = $em->getRepository('proyectobackendBundle:Estudio')->findAll();
                $pagination = $paginator->paginate(
                    $entities,
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else {//si hay filtro sin ordenación
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Estudio', 'a')
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        a.numSecciones LIKE '%$filtro%' OR
                        a.numDatosEstudio LIKE '%$filtro%' OR
                        a.descripcion LIKE '%$filtro%' OR
                        a.activa LIKE '%$filtro%'");
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
                    ->from('proyectobackendBundle:Estudio', 'a')
                    ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                    $qb->getQuery(),
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else{//si hay ordenación y filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Estudio', 'a')
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        a.numSecciones LIKE '%$filtro%' OR
                        a.numDatosEstudio LIKE '%$filtro%' OR
                        a.descripcion LIKE '%$filtro%' OR
                        a.activa LIKE '%$filtro%'")
                ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*page number*/,
                10/*limit per page*/
                );
            }
        }
        return $this->render('proyectobackendBundle:Estudio:index.html.twig', array(
            'pagination' => $pagination,
            'filtro' => $filtro,
            'sort' => $sort,
            'direction' => $direction,
            'admin'=> $admin,
        ));
    }

    /**
     * Crear una nueva entidad Estudio.
     * @param Request 
     * @return vistaShow 
     */
    public function createAction(Request $request)
    {
        $entity  = new Estudio();
        $form = $this->createForm(new EstudioType(), $entity);
        if($request->isMethod("POST"))
        {
            $flag = 0;//controla la validación junto con el resto
            $errorNumDatosEstudio='';
            $errorRango = '';
            $errorNumPruebas = '';
            $form->bind($request);
            
            $em = $this->getDoctrine()->getManager();
            
            
            //comprobamos que los fragmentos existentes pueden dar soporte al numDatosEstudio, tanto si es masculino o femenino el participante
            $estudio = $request->request->get('Estudio');
            $numDatosEstudio = $estudio['numDatosEstudio'];
            
            $fragmentos = $em->getRepository('proyectobackendBundle:Fragmento')->findAll();
            $contMasculinos = 0;
            $contFemeninos = 0;
            $contNeutral = 0;
            foreach($fragmentos as $fragmento){
                $nombre = $em->getRepository('proyectobackendBundle:Adjetivo')->find($fragmento->getIdAdjetivo());
                if(substr($nombre, -1)=='a'){
                    $contFemeninos++;
                }elseif(substr($nombre, -1)=='o'){
                    $contMasculinos++;
                }else{
                    $contNeutral++;
                }
            }
            
            $num = $contFemeninos+$contNeutral;
            if($num>($contMasculinos+$contNeutral)){
                $num = $contMasculinos+$contNeutral;
            }
            if($numDatosEstudio>$num){
                $flag = 1;
                $errorNumDatosEstudio='Este campo no debe ser mayor que '.$num;
            }
            
            //validamos el form lo primero con las validaciones de symfony
            if ($form->isValid()){
                
                
                

                //comprobamos que si hay una subpregunta "Rango" el 1º número sea menor que el 2º
                
                //se comprueba con array_key_exists si existen los indices especificados
                if(array_key_exists('partesEstudio',$estudio)){
                    $partes = $estudio['partesEstudio'];
                    if(array_key_exists('distracciones',$estudio)){
                        $distracciones = $estudio['distracciones'];
                    }else{
                        $distracciones = array();
                    }

                    //por seguridad, esto ocurre solo si se modifica el código fuente
                    if((count($partes)+count($distracciones))!=$estudio['numSecciones'])return $this->redirect($this->generateUrl('logout'));
                    foreach($partes as $parte){
                        $contPreguntas = 0;//contamos el número de preguntas de la parte
                        if(array_key_exists('preguntas',$parte)){
                            $preguntas = $parte['preguntas'];
                            $contPreguntas += $parte['numPreguntas'];//sumamos el número de preguntas
                            //por seguridad, esto ocurre solo si se modifica el código fuente
                            if(count($preguntas)!=$parte['numPreguntas'])return $this->redirect($this->generateUrl('logout'));
                            foreach($preguntas as $pregunta){
                                $contPreguntas += $pregunta['repetirPregunta'];
                                if(array_key_exists('subpreguntas',$pregunta)){
                                    $subpreguntas = $pregunta['subpreguntas'];
                                    //por seguridad, esto ocurre solo si se modifica el código fuente
                                    if(count($subpreguntas)!=$pregunta['numSubpreguntas'])return $this->redirect($this->generateUrl('logout'));
                                    foreach($subpreguntas as $subpregunta){
                                        if($subpregunta['idTipoRespuesta']==3){
                                            //comprobamos que existe y solo es uno el rango
                                            if(array_key_exists('rangosRespuesta',$subpregunta) && count($subpregunta['rangosRespuesta'])==1){
                                                $rangos=$subpregunta['rangosRespuesta'];
                                                //cogemos el 1º elemento, aunque solo habrá uno.
                                                $rango = array_shift($rangos);
                                                if($rango['rangoMin']>=$rango['rangoMax']){
                                                    $flag = 1;
                                                    $errorRango='Compruebe los Rangos de las subpreguntas con ese tipo de respuesta. El rango mínimo debe ser menor que el máximo.';
                                                }
                                            }
                                            else{
                                                //por seguridad, esto ocurre solo si se modifica el código fuente
                                                return $this->redirect($this->generateUrl('logout'));
                                            }
                                        }else if($subpregunta['idTipoRespuesta']==2){
                                            if(!array_key_exists('opcionesRespuesta',$subpregunta) || count($subpregunta['opcionesRespuesta'])<2){
                                                //por seguridad, esto ocurre solo si se modifica el código fuente
                                                return $this->redirect($this->generateUrl('logout'));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //si el número de preguntas de prueba es mayor al número de preguntas que va a haber generará error
                        if($contPreguntas<$parte['numPreguntasDePrueba']){
                            $errorNumPruebas = 'Alguna parte tiene más preguntas de prueba que preguntas tendrá la parte.';
                            $flag = 1;
                        }
                    }
                }
            
            
                if ($flag==0) {

                    $em->persist($entity);
                    $em->flush();

                    return $this->redirect($this->generateUrl('estudio_show', array('id' => $entity->getId())));
                }
            }
        }
        //return $form->getErrorsAsString(0);
        return $this->render('proyectobackendBundle:Estudio:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errorNumDatosEstudio' => $errorNumDatosEstudio,
            'errorRango' => $errorRango,
            'numFragmentos' => $num,
            'errorNumPruebas' => $errorNumPruebas
        ));
        
    }

    /**
     * Visualizar un formulario para crear una nueva entidad Estudio.
     * @return vistaNew 
     */
    public function newAction()
    {
        $entity = new Estudio();
        $form   = $this->createForm(new EstudioType(), $entity);
        $em = $this->getDoctrine()->getManager();
        
        
        
        //obtenemos el máximo número de datos que puede tener atendiendo al género de los adjetivos
        $fragmentos = $em->getRepository('proyectobackendBundle:Fragmento')->findAll();
        $contMasculinos = 0;
        $contFemeninos = 0;
        $contNeutral = 0;
        foreach($fragmentos as $fragmento){
            $nombre = $em->getRepository('proyectobackendBundle:Adjetivo')->find($fragmento->getIdAdjetivo());
            if(substr($nombre, -1) == 'a'){
                $contFemeninos++;
            }elseif(substr($nombre, -1) == 'o'){
                $contMasculinos++;
            }else{
                $contNeutral++;
            }
        }
        if($contFemeninos <= $contMasculinos)
            $num = $contFemeninos + $contNeutral;
        else
            $num = $contMasculinos + $contNeutral;
            
        return $this->render('proyectobackendBundle:Estudio:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'numFragmentos' => $num,            
        ));
    }

    /**
     * Visualizar una entidad Estudio.
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

        $entity = $em->getRepository('proyectobackendBundle:Estudio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estudio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $partes = $entity->getPartesEstudio();
        $arraypartes = array();
        
        foreach($partes as $parte){
            array_push($arraypartes, $parte->getNombre());
        }
        
        return $this->render('proyectobackendBundle:Estudio:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'partes'      => $arraypartes,
            'admin'=> $admin,
        ));
    }


    /**
     * Eliminar una entidad Estudio.
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
            $entity = $em->getRepository('proyectobackendBundle:Estudio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Estudio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('estudio'));
    }

    /**
     * Crear un formulario para eliminnar una entidad Estudio por id
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
    
    /**
     * Activa o desactiva el estudio seleccionado por el id
     * 
     * @param integer  El id de la entidad
     *
     * @return vistaIndex
     */
    
    public function des_activarAction($id)
    {
        //denegamos el acceso a eliminar a los investigadores
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $admin=true;}
        else{
            $admin=false;
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Estudio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estudio entity.');
        }
        if($entity->getActiva()==true){
            $entity->setActiva(false);
            $em->persist($entity);
        }else{
            $entity2 = $em->getRepository('proyectobackendBundle:Estudio')->findOneBy(array('activa' => true));
            if(isset($entity2)){
                $entity2->setActiva(false);
                $em->persist($entity2);
            }
            $entity->setActiva(true);
            $em->persist($entity);
        }
        $em->flush();
        $paginator  = $this->get('knp_paginator');
        $entities = $em->getRepository('proyectobackendBundle:Estudio')->findAll();
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );        
        return $this->render('proyectobackendBundle:Estudio:index.html.twig', array(
            'pagination' => $pagination,
            'filtro' => '',
            'sort' => '',
            'direction' => '',
            'admin' => $admin,
        ));
    }
    
    /**
     * Exportar los resultados de una parte de un estudio a excel
     * 
     * @param Request  Los datos del formulario
     *
     * @return archivo
     */
     public function exportarParteAction(Request $request)
    {
         
         //obtenemos los datos de esta parte
         $datosForm = $request->request->all();
         if(count($datosForm)){
            $em = $this->getDoctrine()->getManager();
            $estudio = $em->getRepository('proyectobackendBundle:Estudio')->find(array_shift($datosForm));
            $parte = $em->getRepository('proyectobackendBundle:ParteEstudio')->find(array_shift($datosForm));
            $preguntas = $parte->getPreguntas();

            // ask the service for a Excel5
           $excelService = $this->get('xls.service_xls5');

           // create the object see http://phpexcel.codeplex.com documentation
           $excelService->excelObj->getProperties()->setTitle("Office 2005 XLSX Test Document")
                               ->setSubject("Office 2005 XLSX Test Document")
                               ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
                               ->setKeywords("office 2005 openxml php")
                               ->setCategory("Test result file");

           $indice =1;
           $excelService->excelObj->setActiveSheetIndex(0)
                       ->setCellValue('A'.$indice, 'Estudio')
                       ->setCellValue('B'.$indice, 'Parte')
                       ->setCellValue('C'.$indice, 'Número de pregunta')
                       ->setCellValue('D'.$indice, 'Pregunta')
                       ->setCellValue('E'.$indice, 'Número de subpregunta')
                       ->setCellValue('F'.$indice, 'Subpregunta')
                       ->setCellValue('G'.$indice, 'Participante')
                       ->setCellValue('H'.$indice, 'Respuesta');
           if($parte->getGuardarTiempoRespuesta() == true ){
               $excelService->excelObj->setActiveSheetIndex(0)
                       ->setCellValue('I'.$indice, 'Tiempo de respuesta');
               if($parte->getIdTipoPregunta()->getId() == 2){
                   $excelService->excelObj->setActiveSheetIndex(0)
                       ->setCellValue('J'.$indice, 'Adjetivo asociado');
               }elseif($parte->getIdTipoPregunta()->getId() == 3){
                   $excelService->excelObj->setActiveSheetIndex(0)
                       ->setCellValue('J'.$indice, 'Fragmento asociado');
               }
           }else{
               if($parte->getIdTipoPregunta()->getId() == 2){
                   $excelService->excelObj->setActiveSheetIndex(0)
                       ->setCellValue('I'.$indice, 'Adjetivo asociado');
               }elseif($parte->getIdTipoPregunta()->getId() == 3){
                   $excelService->excelObj->setActiveSheetIndex(0)
                       ->setCellValue('I'.$indice, 'Fragmento asociado');
               }
           }

           $indice++;
            foreach($preguntas as $indexPregunta => $pregunta){


                $subpreguntas = $pregunta->getSubpreguntas();
                foreach($subpreguntas as $indexSubpregunta => $subpregunta){

                    $respuestas = $em->getRepository('proyectobackendBundle:Respuesta')->findByIdSubpregunta($subpregunta->getId());

                    //iniciamos el contador que cuenta el número de veces que se repite una pregunta.
                    $cont = 0;
                    $flag=0;
                    foreach($respuestas as $respuesta){

                        if($cont == $pregunta->getRepetirPregunta()){
                            $cont = 0;
                            $flag=1;
                        }
                        $excelService->excelObj->setActiveSheetIndex(0)
                           ->setCellValue('A'.$indice, $estudio->getNombre())
                           ->setCellValue('B'.$indice, $parte->getNombre())
                           ->setCellValue('C'.$indice, $indexPregunta+1)
                           ->setCellValue('D'.$indice, $pregunta->getPregunta())
                           ->setCellValue('E'.$indice, $indexSubpregunta+1)
                           ->setCellValue('F'.$indice, $subpregunta->getSubpregunta())
                           ->setCellValue('G'.$indice, $respuesta->getIdParticipante()->getId())
                           ->setCellValue('H'.$indice, $respuesta->getRespuesta());


                        if($parte->getGuardarTiempoRespuesta() == true ){

                            $tiempo = $em->getRepository('proyectobackendBundle:TiempoRespuesta')->findBy(array('idParticipante' => $respuesta->getIdParticipante()->getId(), 'idPregunta' => $pregunta->getId()));

                           $excelService->excelObj->setActiveSheetIndex(0)
                               ->setCellValue('I'.$indice, $tiempo[$cont]->getTiempoRespuesta());
                           if($parte->getIdTipoPregunta()->getId() != 1){

                               $dato = $em->getRepository('proyectobackendBundle:DatosAsociados')->findBy(array('idParticipante' => $respuesta->getIdParticipante()->getId(), 'idPregunta' => $pregunta->getId()));
                               
                               $fragmento = $em->getRepository('proyectobackendBundle:Fragmento')->find($dato[$cont]->getIdFragmento());
                               if($parte->getIdTipoPregunta()->getId() == 2){
                                   $adjetivo = $em->getRepository('proyectobackendBundle:Adjetivo')->find($fragmento->getIdAdjetivo());
                                   $excelService->excelObj->setActiveSheetIndex(0)
                                       ->setCellValue('J'.$indice, $adjetivo->getNombre());
                               }elseif($parte->getIdTipoPregunta()->getId() == 3){
                                   $excelService->excelObj->setActiveSheetIndex(0)
                                       ->setCellValue('J'.$indice, $fragmento->getNombre());
                               }

                           }
                       }else{
                           if($parte->getIdTipoPregunta()->getId() != 1){


                               $dato = $em->getRepository('proyectobackendBundle:DatosAsociados')->findBy(array('idParticipante' => $respuesta->getIdParticipante()->getId(), 'idPregunta' => $pregunta->getId()));
                               $fragmento = $em->getRepository('proyectobackendBundle:Fragmento')->find($dato[$cont]->getIdFragmento());
                               if($parte->getIdTipoPregunta()->getId() == 2){
                                   $adjetivo = $em->getRepository('proyectobackendBundle:Adjetivo')->find($fragmento->getIdAdjetivo());
                                   $excelService->excelObj->setActiveSheetIndex(0)
                                       ->setCellValue('I'.$indice, $adjetivo->getNombre());
                               }elseif($parte->getIdTipoPregunta()->getId() == 3){
                                   $excelService->excelObj->setActiveSheetIndex(0)
                                       ->setCellValue('I'.$indice, $fragmento->getNombre());
                               }
                           }
                       }
                       $indice++;
                       $cont++;
                    }
                }
            }


            $excelService->excelObj->getActiveSheet()->setTitle('Resultados');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $excelService->excelObj->setActiveSheetIndex(0);

            //create the response
            $response = $excelService->getResponse();
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment;filename='.$estudio->getNombre().'_'.$parte->getNombre().'stdream2.xls');

            // If you are using a https connection, you have to set those two headers for compatibility with IE <9
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            return $response;      
        }
    }
    
    /**
     * Eliminar los datos recogidos de un estudio seleccionado
     * 
     * @param Request  Los datos del formulario
     *
     * @return vistaShow
     */
    public function eliminarDatosAction(Request $request)
    {
         //denegamos el acceso a eliminar a los investigadores
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $admin=true;}
        else{
            $admin=false;
        }
        
         //obtenemos los datos de esta parte
         $datosForm = $request->request->all();
         if(count($datosForm)){
             $idEstudio = array_shift($datosForm);
            $em = $this->getDoctrine()->getManager();
            $estudio = $em->getRepository('proyectobackendBundle:Estudio')->find($idEstudio);
            $partes = $estudio->getPartesEstudio();
            $idsParticipante = array();
            
            foreach($partes as $parte){
                $preguntas = $parte->getPreguntas();
                foreach($preguntas as $pregunta){
                    $subpreguntas = $pregunta->getSubpreguntas();
                    foreach($subpreguntas as $subpregunta){
                        $respuestas = $em->getRepository('proyectobackendBundle:Respuesta')->findBy(array('idSubpregunta' => $subpregunta->getId()));
                        foreach($respuestas as $respuesta){
                            if(!in_array($respuesta->getIdParticipante(), $idsParticipante)){
                                array_push($idsParticipante, $respuesta->getIdParticipante());
                            }
                        }
                        
                    }
                }
            }
            
            foreach($idsParticipante as $id){
                $entity = $em->getRepository('proyectobackendBundle:Participante')->find($id);
                $em->remove($entity);
            
            }
            $em->flush();
        }

        if (!$estudio) {
            throw $this->createNotFoundException('Unable to find Estudio entity.');
        }

        $deleteForm = $this->createDeleteForm($idEstudio);

        $partes = $estudio->getPartesEstudio();
        $arraypartes = array();
        foreach ($partes as $parte){
            array_push($arraypartes, $parte->getNombre());
        }
        
        return $this->render('proyectobackendBundle:Estudio:show.html.twig', array(
            'admin' => $admin,
            'entity'      => $estudio,
            'delete_form' => $deleteForm->createView(),
            'partes'      => $arraypartes,
            'alertDatosEliminados' => 1,
        ));
    }
}
