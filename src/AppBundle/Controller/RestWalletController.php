<?php

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use AppBundle\Entity\Wallet;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Repository\WalletRepository;
use AppBundle\Form\Type\WalletType;
use FOS\RestBundle\View\RouteRedirectView;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @RouteResource("wallet", pluralize=false)
 */
class RestWalletController extends FOSRestController implements ClassResourceInterface
{
  
  /**
   * @param Request $request
   * @return mixed
   * @throws \Doctrine\ORM\NoResultException
   * @throws \Doctrine\ORM\NonUniqueResultException
   * @Annotations\Get("/wallet/{user}/address")
   * @ParamConverter("user", class="AppBundle:User")
   * @ApiDoc(
   *     input="AppBundle\Form\Type\WalletType",
   *     output="AppBundle\Entity\Wallet",
   *     statusCodes={
   *         201 = "Returned when a new Wallet has been successful created",
   *         404 = "Return when not found"
   *     }
   * )
   */
  public function getAction(UserInterface $user)
  {
    if ($user !== $this->getUser()) {
      throw new AccessDeniedHttpException();
    }
    
    $wallet = $this->getWalletRepository()->createFindOneByIdQuery($user->getId())->getOneOrNullResult();
    
    if ($wallet === null) {
      return new View(null, Response::HTTP_NOT_FOUND);
    }
    
    return $wallet->getPublicAddress();
  }
  
  /**
   * @param Request $request
   * @param UserInterface $user
   * @return View|\Symfony\Component\Form\Form
   * @throws \Doctrine\ORM\NoResultException
   * @throws \Doctrine\ORM\NonUniqueResultException
   * @Annotations\Post("/wallet/{user}/address")
   * @ParamConverter("user", class="AppBundle:User")
   *
   * @ApiDoc(
   *     input="AppBundle\Form\Type\WalletType",
   *     output="AppBundle\Entity\Wallet",
   *     statusCodes={
   *         201 = "Returned when a new Wallet has been successful created",
   *         404 = "Return when not found"
   *     }
   * )
   */
  public function postAction(Request $request, UserInterface $user)
  {
    if ($user !== $this->getUser()) {
      throw new AccessDeniedHttpException();
    }
  
    $wallet = $this->getWalletRepository()->createFindOneByIdQuery($user->getId())->getOneOrNullResult();
    
    
    if ($wallet === null) {
      return $this->createAddress($request);
    }elseif($user->getId() != $request->get('user_id')){
      throw new AccessDeniedHttpException();
    }else {
      return $this->updateAddress($request, $wallet);
    }
  }
  
  private function createAddress(Request $request){
    
    $form = $this->createForm(WalletType::class, null, [
      'csrf_protection' => false,
    ]);
  
    $form->submit($request->request->all());
  
    if (!$form->isValid()) {
      return $form;
    }
    /**
     * @var $wallet Wallet
     */
    $wallet = $form->getData();
  
    $em = $this->getDoctrine()->getManager();
    $em->persist($wallet);
    $em->flush();

    $routeOptions = [
      'id' => $wallet->getId(),
      '_format' => $request->get('_format'),
    ];
//    return $this->routeRedirectView('/', $routeOptions, Response::HTTP_CREATED);
    return new JsonResponse(JsonResponse::HTTP_CREATED);
  }
  
  private function updateAddress(Request $request, Wallet $wallet){
    $form = $this->createForm(WalletType::class, $wallet, [
      'csrf_protection' => false,
    ]);

    $form->submit($request->request->all());
    if (!$form->isValid()) {
      return $form;
    }

    $em = $this->getDoctrine()->getManager();
    $em->flush();

    $routeOptions = [
      'user_id' => $wallet->getUserId(),
      '_format' => $request->get('_format'),
    ];
//    return $this->routeRedirectView('get_wallet', $routeOptions, Response::HTTP_NO_CONTENT);
    return new JsonResponse(JsonResponse::HTTP_OK);
  }
  
  /**
   * @return WalletRepository
   */
  private function getWalletRepository()
  {
    return $this->get('crv.doctrine_entity_repository.wallet');
  }
}







//   /**
//    * @param Request $request
//    * @param UserInterface $user
//    * @return View|\Symfony\Component\Form\Form
//    * @throws \Doctrine\ORM\NoResultException
//    * @throws \Doctrine\ORM\NonUniqueResultException
//    * @Annotations\Put("/wallet/{user}/address")
//    * @ParamConverter("user", class="AppBundle:User")
//    *
//    * @ApiDoc(
//    *     input="AppBundle\Form\Type\WalletType",
//    *     output="AppBundle\Entity\Wallet",
//    *     statusCodes={
//    *         204 = "Returned when an existing Wallet has been successful updated",
//    *         400 = "Return when errors",
//    *         404 = "Return when not found"
//    *     }
//    * )
//    */
//   public function putAction(Request $request, UserInterface $user)
//   {
//     if ($user !== $this->getUser()) {
//       throw new AccessDeniedHttpException();
//     }
    
//     *
//      * @var $wallet Wallet
     
//     $wallet = $this->getWalletRepository()->createFindOneByIdQuery($user->getId())->getSingleResult();
//     if ($wallet === null) {
//       return new View(null, Response::HTTP_NOT_FOUND);
//     }elseif($user->getId() != $request->get('user_id')){
//       throw new AccessDeniedHttpException();
//     }
//     $form = $this->createForm(WalletType::class, $wallet, [
//       'csrf_protection' => false,
//     ]);
//     $form->submit($request->request->all());
//     if (!$form->isValid()) {
//       return $form;
//     }
//     $em = $this->getDoctrine()->getManager();
//     $em->flush();
//     $routeOptions = [
//       'user_id' => $wallet->getUserId(),
//       '_format' => $request->get('_format'),
//     ];
// //    return $this->routeRedirectView('get_wallet', $routeOptions, Response::HTTP_NO_CONTENT);
//     return new JsonResponse(JsonResponse::HTTP_OK);
//   }