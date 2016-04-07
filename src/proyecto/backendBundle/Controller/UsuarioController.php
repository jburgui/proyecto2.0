<?php

namespace proyecto\backendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use proyecto\backendBundle\Entity\Usuario;
use proyecto\backendBundle\Form\UsuarioType;

/**
 * Controlador Usuario 
 * @author Javier Burguillo Sánchez
 */
class UsuarioController extends Controller
{
    /**
     * Listar todas las entidades Usuario ordenadas en páginas 
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
                $entities = $em->getRepository('proyectobackendBundle:Usuario')->findAll();
                $pagination = $paginator->paginate(
                    $entities,
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else {//si hay filtro sin ordenación
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Usuario', 'a')
                ->join('a.rol', 'r')    
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        r.nombre LIKE '%$filtro%' OR
                        a.email LIKE '%$filtro%'");
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
                    ->from('proyectobackendBundle:Usuario', 'a')
                    ->join('a.rol', 'r')    
                    ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                    $qb->getQuery(),
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else{//si hay ordenación y filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Usuario', 'a')
                ->join('a.rol', 'r')    
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        r.nombre LIKE '%$filtro%' OR
                        a.email LIKE '%$filtro%'")
                ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*page number*/,
                10/*limit per page*/
                );
            }
        }
        return $this->render('proyectobackendBundle:Usuario:index.html.twig', array(
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
        $entity  = new Usuario();
        $form = $this->createForm(new UsuarioType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usuario_show', array('id' => $entity->getId())));
        }

        return $this->render('proyectobackendBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Visualizar un formulario para crear una nueva entidad Usuario.
     * @return vistaNew 
     */
    public function newAction()
    {
        $entity = new Usuario();
        $form   = $this->createForm(new UsuarioType(), $entity);

        return $this->render('proyectobackendBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Visualizar una entidad Usuario.
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

        $entity = $em->getRepository('proyectobackendBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('proyectobackendBundle:Usuario:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'admin' => $admin,
        ));
    }

    /**
     * Visualizar un formulario para editar una entidad Usuario existente.
     * @param int Identificador del registro a visualizar
     * @return vistaEdit 
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioType(), $entity);

        return $this->render('proyectobackendBundle:Usuario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Actualizar una entidad Usuario editada.
     * @param int Identificador del registro a actualizar
     * @param Request 
     * @return llamarShowAction
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usuario_show', array('id' => $id)));
        }

        return $this->render('proyectobackendBundle:Usuario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Eliminar una entidad Usuario.
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
            $entity = $em->getRepository('proyectobackendBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    /**
     * Crear un formulario para eliminnar una entidad Usuario por id
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
