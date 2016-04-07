<?php

namespace proyecto\backendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use proyecto\backendBundle\Entity\Fragmento;
use proyecto\backendBundle\Form\FragmentoType;

/**
 * Controlador Fragmento 
 * @author Javier Burguillo Sánchez
 */
class FragmentoController extends Controller
{
    /**
     * Listar todas las entidades Fragmento ordenadas en páginas 
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
                $entities = $em->getRepository('proyectobackendBundle:Fragmento')->findAll();
                $pagination = $paginator->paginate(
                    $entities,
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else {//si hay filtro sin ordenación
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Fragmento', 'a')
                ->join('a.idAdjetivo', 'r')    
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        a.primeraLetra LIKE '%$filtro%' OR
                        a.ultimaLetra LIKE '%$filtro%' OR
                        a.dosEspaciosJuntos LIKE '%$filtro%' OR
                        a.tresLetrasJuntas LIKE '%$filtro%' OR
                        a.ratioDadasEliminadas LIKE '%$filtro%' OR
                        a.letrasDadas LIKE '%$filtro%' OR                            
                        a.ratioVocalesConsonantes LIKE '%$filtro%' OR
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
                    ->from('proyectobackendBundle:Fragmento', 'a')
                    ->join('a.idAdjetivo', 'r')    
                    ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                    $qb->getQuery(),
                    $this->get('request')->query->get('page', $page)/*page number*/,
                    10/*limit per page*/
                );
            }else{//si hay ordenación y filtro
                $qb = $em->createQueryBuilder();
                $qb->select('a')
                ->from('proyectobackendBundle:Fragmento', 'a')
                ->join('a.idAdjetivo', 'r')    
                ->where("a.id LIKE '%$filtro%' OR
                        a.nombre LIKE '%$filtro%' OR
                        a.primeraLetra LIKE '%$filtro%' OR
                        a.ultimaLetra LIKE '%$filtro%' OR
                        a.dosEspaciosJuntos LIKE '%$filtro%' OR
                        a.tresLetrasJuntas LIKE '%$filtro%' OR
                        a.ratioDadasEliminadas LIKE '%$filtro%' OR
                        a.letrasDadas LIKE '%$filtro%' OR                            
                        a.ratioVocalesConsonantes LIKE '%$filtro%' OR
                        r.nombre LIKE '%$filtro%'")
                ->orderBy($sort, $direction);
                $pagination = $paginator->paginate(
                $qb->getQuery(),
                $this->get('request')->query->get('page', $page)/*page number*/,
                10/*limit per page*/
                );
            }
        }
        return $this->render('proyectobackendBundle:Fragmento:index.html.twig', array(
            'pagination' => $pagination,
            'filtro' => $filtro,
            'sort' => $sort,
            'direction' => $direction,
        ));
    }

    /**
     * Crear una nueva entidad Fragmento.
     * @param Request 
     * @return vistaShow 
     */
    public function createAction(Request $request)
    {
        $entity  = new Fragmento();
        $form = $this->createForm(new FragmentoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fragmento_show', array('id' => $entity->getId())));
        }

        return $this->render('proyectobackendBundle:Fragmento:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Visualizar un formulario para crear una nueva entidad Fragmento.
     * @return vistaNew 
     */
    public function newAction()
    {
        $entity = new Fragmento();
        $form   = $this->createForm(new FragmentoType(), $entity);

        return $this->render('proyectobackendBundle:Fragmento:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Visualizar una entidad Fragmento.
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

        $entity = $em->getRepository('proyectobackendBundle:Fragmento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fragmento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('proyectobackendBundle:Fragmento:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'admin' => $admin,
        ));
    }

    /**
     * Visualizar un formulario para editar una entidad Fragmento existente.
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

        $entity = $em->getRepository('proyectobackendBundle:Fragmento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fragmento entity.');
        }

        $editForm = $this->createForm(new FragmentoType(), $entity);

        return $this->render('proyectobackendBundle:Fragmento:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'admin' => $admin,
        ));    
        
    }

    /**
     * Actualizar una entidad Framento editada.
     * @param int Identificador del registro a actualizar
     * @param Request 
     * @return llamarShowAction
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('proyectobackendBundle:Fragmento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fragmento entity.');
        }

        $editForm = $this->createForm(new FragmentoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fragmento_show', array('id' => $id)));
        }

        return $this->render('proyectobackendBundle:Fragmento:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Eliminar una entidad Fragmento.
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
                $entity = $em->getRepository('proyectobackendBundle:Fragmento')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Fragmento entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('fragmento'));
    }

    /**
     * Crear un formulario para eliminnar una entidad Fragmento por id
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
