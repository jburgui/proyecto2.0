<?php

namespace proyecto\backendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use proyecto\backendBundle\Entity\Adjetivo;
use proyecto\backendBundle\Form\AdjetivoType;

/**
 * Controlador Adjetivo 
 * @author Javier Burguillo Sánchez
 */
class AdjetivoController extends Controller
{
    /**
     * Listar todas las entidades Adjetivo ordenadas en páginas 
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
                $entities = $em->getRepository('proyectobackendBundle:Adjetivo')->findAll();
                $pagination = $paginator->paginate(
                    $entities,
                    $this->get('request')->query->get('page', $page)/*Número de página*/,
                    10/*registros por página*/
                );
            }else {//si hay filtro sin ordenación
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Adjetivo', 'a')
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        a.tipo LIKE '%$filtro%' OR
                        a.frecTeorica LIKE '%$filtro%' OR
                        a.numLetras LIKE '%$filtro%' OR
                        a.numSilabas LIKE '%$filtro%' OR
                        a.categoria LIKE '%$filtro%' OR
                        a.frecUsoComoAdj LIKE '%$filtro%' OR
                        a.numSignificados LIKE '%$filtro%'");
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*Número de página*/,
                10/*registros por página*/
                );
            }
            
        }else{
            if($filtro==false){//si hay ordenación sin filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                    ->from('proyectobackendBundle:Adjetivo', 'a')
                    ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                    $qb->getQuery(),
                    $this->get('request')->query->get('page', $page)/*Número de página*/,
                    10/*registros por página*/
                );
            }else{//si hay ordenación y filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Adjetivo', 'a')
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        a.tipo LIKE '%$filtro%' OR
                        a.frecTeorica LIKE '%$filtro%' OR
                        a.numLetras LIKE '%$filtro%' OR
                        a.numSilabas LIKE '%$filtro%' OR
                        a.categoria LIKE '%$filtro%' OR
                        a.frecUsoComoAdj LIKE '%$filtro%' OR
                        a.numSignificados LIKE '%$filtro%'")
                ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*Número de página*/,
                10/*registros por página*/
                );
            }
        }
        return $this->render('proyectobackendBundle:Adjetivo:index.html.twig', array(
            'pagination' => $pagination,
            'filtro' => $filtro,
            'sort' => $sort,
            'direction' => $direction,
        ));
    }

    /**
     * Crear una nueva entidad Adjetivo.
     * @param Request 
     * @return vistaShow 
     */
    public function createAction(Request $request)
    {
        $entity  = new Adjetivo();
        $form = $this->createForm(new AdjetivoType(), $entity);
        $form->bind($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('adjetivo_show', array('id' => $entity->getId())));
        }

        return $this->render('proyectobackendBundle:Adjetivo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Visualizar un formulario para crear una nueva entidad Adjetivo.
     * @return vistaNew 
     */
    public function newAction()
    {
        $entity = new Adjetivo();
        $form   = $this->createForm(new AdjetivoType(), $entity);

        return $this->render('proyectobackendBundle:Adjetivo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Visualizar una entidad Adjetivo.
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

        $entity = $em->getRepository('proyectobackendBundle:Adjetivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Adjetivo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('proyectobackendBundle:Adjetivo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),    
            'admin'=> $admin,
        ));
    }

    /**
     * Visualizar un formulario para editar una entidad Adjetivo existente.
     * @param int Identificador del registro a visualizar
     * @return vistaEdit 
     */
    public function editAction($id)
    {
        //denegamos el acceso a eliminar a los investigadores
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $admin=true;}
        else{
            $admin=false;
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Adjetivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Adjetivo entity.');
        }

        $editForm = $this->createForm(new AdjetivoType(), $entity);

        return $this->render('proyectobackendBundle:Adjetivo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'admin' => $admin,
        ));
    }

    /**
     * Actualizar una entidad Adjetivo editada.
     * @param int Identificador del registro a actualizar
     * @param Request 
     * @return llamarShowAction
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Adjetivo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Adjetivo entity.');
        }

        $editForm = $this->createForm(new AdjetivoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adjetivo_show', array('id' => $id)));
        }

        return $this->render('proyectobackendBundle:Adjetivo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Eliminar una entidad Adjetivo.
     * @param int Identificador del registro a actualizar
     * @param Request 
     * @return llamarIndexAction
     */
    public function deleteAction(Request $request, $id)
    {
        //denegamos el acceso a los investigadores
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){

            $form = $this->createDeleteForm($id);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('proyectobackendBundle:Adjetivo')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Adjetivo entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('adjetivo'));
    }

    /**
     * Crear un formulario para eliminnar una entidad Adjetivo por id
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
